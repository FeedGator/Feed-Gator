<?php
/**
* FeedGator - Aggregate RSS newsfeed content into a Joomla! database
* @version 2.0 beta1
* @package FeedGator
* @author Stephen Simmons (inquiries@simmonstech.net)
* @now continued and modified by Remco Boom & Stephane Koenig and others
* @Joomla 1.5 Version by J. Kapusciarz (mrjozo)
* @copyright (C) 2005 by Stephen Simmons - All rights reserved
* @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
*
**/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
jimport('joomla.application.component.controller');

/**
 * Feedgator Component Controller
 *
 * @since 1.5
 */
class FeedgatorController extends JController
{
	function __construct( $config = array())
	{
		$task = JRequest::getCmd('task');
		if ($task != 'cron') {
			$xajax = new xajax();
			$xajax->registerFunction( array('importfeed', $this, 'importfeed') );
			$xajax->registerFunction( array('importall', $this, 'importall') );
		}
		parent::__construct( $config );
		if ($task != 'cron')
			$xajax->processRequests();
	}
	function about()
	{
		$view	= &$this->getView('Feedgator','html');
		$view->display('about');
	}
	function support()
	{
		$view	= &$this->getView('Feedgator','html');
		$view->display('support');
	}
	function feeds()
	{
		global $mainframe;
		$db			=& JFactory::getDBO();
		$context			= 'com_feedgator.feeds';
		$option				= JRequest::getCmd( 'option' );
		$limit      = $mainframe->getUserStateFromRequest( $context.'viewlistlimit', 'limit', 10 ,'int');
		$limitstart = $mainframe->getUserStateFromRequest( $context.'view'.$option.'limitstart', 'limitstart', 0 ,'int');
		$search     = $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' ,'word');
		$search				= JString::strtolower($search);
		$filter_order		= $mainframe->getUserStateFromRequest( $context.'filter_order',		'filter_order',		'',	'cmd' );
		$filter_order_Dir	= $mainframe->getUserStateFromRequest( $context.'filter_order_Dir',	'filter_order_Dir',	'',	'word' );
		if (!$filter_order) {
			$filter_order = 'f.id';
		}
		$order = ' ORDER BY '. $filter_order .' '. $filter_order_Dir .', f.id';
		$where = array();
		if ($search) {
			$where[] = '(LOWER( f.title ) LIKE '.$db->Quote( '%'.$db->getEscaped( $search, true ).'%', false ) .
			' OR f.id = ' . (int) $search . ')';
		}
		$where = (count($where) ? ' WHERE '.implode(' AND ', $where) : '');
		$db->setQuery( 'SELECT count(*) FROM #__feedgator AS f'.
		' LEFT JOIN #__categories AS cc ON cc.id = f.catid' .
		' LEFT JOIN #__sections AS s ON s.id = f.sectionid' .
		$where );
		$total = $db->loadResult();
		if (!$db->query()) {
			JError::raiseError( 500, $db->getErrorMsg() );
			return false;
		}
		// Create the pagination object
		jimport('joomla.html.pagination');
		$pagination = new JPagination($total, $limitstart, $limit);
		$db->setQuery( 'SELECT f.*,cc.title AS cat_name, s.title AS section_name, u.name AS editor FROM #__feedgator f'.
		' LEFT JOIN #__categories AS cc ON cc.id = f.catid' .
		' LEFT JOIN #__sections AS s ON s.id = f.sectionid' .
		' LEFT JOIN #__users AS u ON u.id = f.checked_out '.
		$where.
		$order ,$pagination->limitstart,$pagination->limit);
		$rows = $db->loadObjectList();
		// If there is a database query error, throw a HTTP 500 and exit
		if ($db->getErrorNum()) {
			JError::raiseError( 500, $db->stderr() );
			return false;
		}
		// table ordering
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order']		= $filter_order;
		// search filter
		$lists['search'] = $search;
		$view	= &$this->getView('Feedgator','html');
		$view->assignRef('rows', $rows);
		$view->assignRef('page', $pagination);
		$view->assignRef('search', $search);
		$view->assignRef('lists', $lists);
		$view->display();
	}
	function editContent($edit)
	{
		global $mainframe;
		// Initialize variables
		$db				= & JFactory::getDBO();
		$user			= & JFactory::getUser();
		$cid			= JRequest::getVar( 'cid', array(0), '', 'array' );
		JArrayHelper::toInteger($cid, array(0));
		$id				= JRequest::getVar( 'id', $cid[0], '', 'int' );
		$option			= JRequest::getCmd( 'option' );
		$nullDate		= $db->getNullDate();
		$contentSection	= '';
		$sectionid		= 0;
		$model = &$this->getModel( 'feed' );
		$feed = $model->getData();
		if ($model->isCheckedOut( $user->get('id') )) {
			$msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'The feed' ), $feed->title );
			$mainframe->redirect( 'index.php?option=com_feedgator', $msg );
		}
		if ($id) {
			$sectionid = $feed->sectionid;
		}
		if ( $sectionid == 0 ) {
			$where = "\n WHERE section NOT LIKE '%com_%'";
		} else {
			$where = "\n WHERE section = '$sectionid'";
		}
		// get the type name - which is a special category
		if ($feed->sectionid){
			$query = "SELECT name"
			. "\n FROM #__sections"
			. "\n WHERE id = $feed->sectionid"
			;
			$db->setQuery( $query );
			$section = $db->loadResult();
			$feedSection = $section;
		} else {
			$query = "SELECT name"
			. "\n FROM #__sections"
			;
			$db->setQuery( $query );
			$section = $db->loadResult();
			$feedSection = $section;
		}
		if ($id) {
			$model->checkout($user->get('id'));
			jimport('joomla.utilities.date');
			$createdate = new JDate($feed->created);
			$feed->created 		= $createdate->toUnix();
		} else {
			if ( !$sectionid && @$_POST['filter_sectionid'] ) {
				$sectionid = $_POST['filter_sectionid'];
			}
			if ( @$_POST['catid'] ) {
				$row->catid 	= $_POST['catid'];
				$category 	 = & JTable::getInstance('category');
				$category->load($row->catid);
				$sectionid = $category->section;
			} else {
				$row->catid 	= NULL;
			}
			$row->sectionid 	= $sectionid;
		}
		$javascript = "onchange=\"changeDynaList( 'catid', sectioncategories, document.adminForm.sectionid.options[document.adminForm.sectionid.selectedIndex].value, 0, 0);\"";
		$query = "SELECT s.id, s.title"
		. "\n FROM #__sections AS s"
		. "\n ORDER BY s.ordering";
		$db->setQuery( $query );
		if ( $sectionid == 0 ) {
			$sections[] = JHTML::_('select.option', '-1', '- '.JText::_('Select Section').' -', 'id', 'title');
			$sections = array_merge( $sections, $db->loadObjectList() );
			$lists['sectionid'] = JHTML::_('select.genericlist',  $sections, 'sectionid', 'class="inputbox" size="1" '.$javascript, 'id', 'title', intval($feed->sectionid));
		} else {
			$sections = $db->loadObjectList();
			$lists['sectionid'] = JHTML::_('select.genericlist',  $sections, 'sectionid', 'class="inputbox" size="1" '.$javascript, 'id', 'title', intval($feed->sectionid));
		}
		$sections = $db->loadObjectList();
		$sectioncategories 			= array();
		$sectioncategories[-1] 		= array();
		$sectioncategories[-1][] = JHTML::_('select.option', '-1', JText::_( 'Select Category' ), 'id', 'title');
		foreach($sections as $section) {
			$sectioncategories[$section->id] = array();
			$query = "SELECT id, title"
			. "\n FROM #__categories"
			. "\n WHERE section = '$section->id'"
			. "\n ORDER BY ordering"
			;
			$db->setQuery( $query );
			$rows2 = $db->loadObjectList();
			foreach($rows2 as $row2) {
				$sectioncategories[$section->id][] = JHTML::_('select.option', $row2->id, $row2->title, 'id', 'title');
			}
		}
		// get list of categories
		if ( !$feed->catid && !$feed->sectionid ) {
			$categories[] = JHTML::_('select.option', '-1', JText::_( 'Select Category' ), 'id', 'title');
			$lists['catid'] = JHTML::_('select.genericlist',  $categories, 'catid', 'class="inputbox" size="1"', 'id', 'title');} else {
				$query = "SELECT id, title"
				. "\n FROM #__categories"
				. $where
				. "\n ORDER BY ordering"
				;
				$db->setQuery( $query );
				$categories[] = JHTML::_('select.option', '-1', JText::_( 'Select Category' ), 'id', 'title');
				$categories 		= array_merge( $categories, $db->loadObjectList() );
				$lists['catid'] = JHTML::_('select.genericlist',  $categories, 'catid', 'class="inputbox" size="1"', 'id', 'title', intval($feed->catid));
			}
			$view	= &$this->getView('Feedgator','html');
			$view->assignRef('feed', $feed);
			$view->assignRef('lists', $lists);
			$view->assignRef('sectioncategories', $sectioncategories);
			$view->display('new');
	}
	function save()
	{

		JRequest::checkToken() or die( 'Invalid Token' );
		$post	= JRequest::get('post');
		$cid	= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$post['id'] = (int) $cid[0];
		$model = $this->getModel('feed');
		if ($model->store($post)) {
			$msg = JText::_( 'Feed Saved' );
		} else {
			$msg = JText::_( 'Error Saving Feed' );
		}
		$model->checkin();
		$link = 'index.php?option=com_feedgator';
		$this->setRedirect($link, $msg);
	}
	function publishFeeds($publish = 1, $action = 'publish')
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to '.$action ) );
		}
		$model = $this->getModel('feed');
		if(!$model->publish($cid, $publish)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		$this->setRedirect( 'index.php?option=com_feedgator' );
	}
	/**
	* Changes the frontpage state of one or more feeds
	*
	*/
	function frontpageFeeds($frontpage = 1, $action = 'front_yes')
	{
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to '.$action ) );
		}
		$model = $this->getModel('feed');
		if(!$model->frontpage($cid, $frontpage)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		$this->setRedirect( 'index.php?option=com_feedgator' );
	}
	function remove()
	{
		global $mainframe;
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		// Initialize variables
		$db			= & JFactory::getDBO();
		$cid		= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		if (count($cid) < 1) {
			$msg =  JText::_('Select an item to delete');
			$mainframe->redirect('index.php?option=com_feedgator', $msg, 'error');
		}
		$model = $this->getModel('feed');
		if(!$model->delete($cid, $frontpage)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}
		$msg = JText::sprintf('Item(s) deleted', count($cid));
		$mainframe->redirect('index.php?option=com_feedgator', $msg);
	}
	/**
	* Cancels an edit operation
	*/
	function cancel()
	{
		global $mainframe;
		// Check for request forgeries
		JRequest::checkToken() or die( 'Invalid Token' );
		// Initialize variables
		$db	= & JFactory::getDBO();
		// Check the article in if checked out
		$model = $this->getModel('feed');
		$model->checkin();
		$mainframe->redirect('index.php?option=com_feedgator');
	}
	function importall(){
		$db			= & JFactory::getDBO();
		$db->setQuery( "SELECT id FROM #__feedgator WHERE published = '1'" );
		$feedIds = $db->loadResultArray();
		$formData['cid'] = $feedIds;
		return $this->importfeed( $formData );
	}
	function importfeed( $formData = '') {
		$db = & JFactory::getDBO();
		$fgConfig = JComponentHelper::getParams ('com_feedgator');
		$objResponse = new xajaxResponse();
		$cid		= (empty($formData['cid'])) ? JRequest::getVar( 'cid', array(), 'get', 'array' ) : $formData['cid'];
		$mosMsg = '';
		$startTime = time();
		$adminMsg = '';
		if ($fgConfig->get('email_admin') == '1') {
			$adminMsg .= "<b>Results of the last Feed Gator import run:</b>\n\n";
			$adminMsg .= '<div id="feedinfo"><h1>START Feed Gator Import Processing: ' . date('D F j, Y, H:i:s T') . "</h1>\n";
		}
		$objResponse->addScript("resetMsgArea();");
		$objResponse->addAppend("fgmsgarea", "innerHTML", $fgConfig->get('target') . "<br />");
		$processed = 0;
		$totItems = 0;
		
		$cacheDir = JPATH_BASE .DS.'cache';
		if ( !$fgConfig->get('use_sp_cache') || !is_writable( $cacheDir ) ) {
			$cache_exists = false;
		}else{
			$cache_exists = true;
		}
		
		
		foreach($cid as $feedId) {
			$nItems = 0;
			$model = &$this->getModel( 'feed' );
			$model->setId($feedId);
			$feed = $model->getData();
			if ( $feed->published ) {
				$curFeed = $feed->title;
				// process the feed with SimplePie
				$rssDoc = new SimplePie();
				$myfeed = $feed->feed;
				if (get_magic_quotes_gpc()){
					$myfeed = stripslashes($myfeed);
				}
				$rssDoc->set_feed_url($myfeed);
				$forcefsock = $fgConfig->get('force_fsockopen');
				if ($forcefsock == '1') {
					$rssDoc->force_fsockopen(true);
				}
				if($cache_exists) {
					$rssDoc->set_cache_location($cacheDir);
					$rssDoc->caching = true;
					$rssDoc->set_cache_duration(60 * SPIE_CACHE_AGE);
				} else {
					$rssDoc->caching = false;
				}
				$rssDoc->init();
				$rssDoc->handle_content_type();
				
				if (($rssDoc->get_type()) & SIMPLEPIE_TYPE_NONE) {
				} else {
					$channelTitle = $rssDoc->get_title();
					$itemArray = $rssDoc->get_items();
					
					if (is_array($itemArray)) {
						foreach ($itemArray as $item) {
							$content = array();
							$content['title'] = trim($item->get_title());
							$item_description = trim($item->get_description());
							

							if ($fgConfig->get('show_html') && ($item_content = trim($item->get_content()))) {
								if(!empty($item_content)){
									$item_description = $item_content; 
								}
							}
							
							if ($fgConfig->get('strip_html_tags')) { 
									$item_description = strip_tags($item_description); 	
									$content['title'] = strip_tags($content['title']);								
							}
							
							$alias = JFilterOutput::stringURLSafe($content['title']);
							
							$content['introtext'] = $item_description;
							if ( JString::strpos( $content['introtext'], '<br/>' ) === 0 ) {
								$content['introtext'] = substr( $content['introtext'], 5 );
							}

							if (!empty($feed->trim_to) || strlen($content['introtext']) > intval($feed->trim_to)){
								$content['introtext'] = FeedgatorHelper::makeTeaser($content['introtext'], $feed->trim_to);
							}

							$intro_length = strlen($content['introtext']);
							if(!$feed->onlyintro && $intro_length < strlen($item_description)) {
								$content['fulltext'] = substr($item_description,$intro_length);
							} else {
								$content['fulltext'] = '';
							}
							$content['sectionid'] = $feed->sectionid;
							$content['catid'] = $feed->catid;
							$content['metakey'] = '';

							if ($fgConfig->get('save_feed_cats')) {
								if ($category = $item->get_category())
								{
									$content['metakey'] .= $category->get_label();
								}
							}
							if ($fgConfig->get('save_sect_cats')) {
								$sectionName = FeedgatorHelper::getFieldName($content['sectionid'], 'sections');
								$catName = FeedgatorHelper::getFieldName($content['catid'], 'categories');
								$content['metakey'] .= empty($content['metakey'])? "$sectionName, $catName": ",$sectionName, $catName";
							}
							if ($fgConfig->get('compute_tags')) {
								if ($fgConfig->get('use_yahoo_te') && $fgConfig->get('yahoo_app_id')){
									$content['metakey'] .= empty($content['metakey'])? FeedgatorHelper::extractTerms($item_description): ',' . FeedgatorHelper::extractTerms($item_description);
								} else {
									$content['metakey'] .= empty($content['metakey'])? FeedgatorHelper::generateTags($item_description): ',' . FeedgatorHelper::generateTags($item_description);
								}
							}
							if ($fgConfig->get('show_orig_link')) {
								$origLink = $item->get_permalink();
								$linkTxt = $fgConfig->get('orig_link_text');
								$target = '';
								if ($fgConfig->get('target_frame') != 'none') {
									$target = 'target="';
									$target .= $fgConfig->get('target_frame') == 'custom'? $fgConfig->get('custom_frame'): $fgConfig->get('target_frame');
									$target .= '"';
								}
								if (!empty($origLink)){
									$readonlink = '<p>';
									if ($feed->shortlink == '1'){
										$readonlink .= '<strong>'.$linkTxt.'</strong> <a class="rssreadon" rel="external" title="'.$content['title'].'" href="'.$origLink.'" '.$target.'>'.$channelTitle.'</a>';
									}else{
										$readonlink .= '<strong>'.$linkTxt.'</strong> <a class="rssreadon" rel="external" title="'.$content['title'].'" href="'.$origLink.'" '.$target.'>'.$origLink.'</a>';
									}
									if($feed->onlyintro == '1' or empty($content['fulltext'])){
										$content['introtext'] .= $readonlink.'</p>';
									} else {
										$content['fulltext'] .= $readonlink.'</p>';
									}

								}
							}
							
							if(trim(str_replace('-','',$alias)) == '') {
								$alias = null;
							}
		
							if (!empty($content['title']) && !empty($alias)) {
								$query = "SELECT COUNT(id) FROM #__content WHERE
								alias = '". $alias ."'
								AND (state = '1' OR state = '0')";
								$db->setQuery( $query );
								$count = $db->loadResult();
							} elseif (!empty($content['title'])){
								$query = "SELECT COUNT(id) FROM #__content WHERE
								title = '". mysql_real_escape_string( $content['title'])."'
								AND (state = '1' OR state = '0')";
								$db->setQuery( $query );
								$count = $db->loadResult();
							} else {
								$query = "SELECT COUNT(id) FROM #__content WHERE
								introtext = '".mysql_real_escape_string( $content['introtext']) ."'
								AND (state = '1' OR state = '0')";
								$db->setQuery( $query );
								$count = $db->loadResult();
							}

							if (intval($count) == 0)
							{
								$config =& JFactory::getConfig();
								$tzoffset = $config->getValue('config.offset');
								$itemDate =& JFactory::getDate($item->get_date(), $tzoffset);
								$itemDate = $itemDate->toMySQL();
								$content['publish_up'] = $itemDate;
								
								if($content['publish_up'] < '2000-01-01 00:00:00')
									$content['publish_up'] = date('Y-m-d H:i:s', time());
								if ($fgConfig->get('save_author')) {
									if ($author = $item->get_author()) {
										$content['created_by_alias']= $author->get_name();
									}
									if (empty($content['created_by_alias']) && $fgConfig->get('missing_author') == '0')
									$content['created_by_alias'] = $channelTitle;
									elseif (empty($content['created_by_alias']) && $fgConfig->get('missing_author') == '1')
									$content['created_by_alias'] = $feed->default_author;
								}
								$content['state'] = intval($fgConfig->get('auto_publish'));
								$publishDays = intval($fgConfig->get('publish_days'));
								if ($content['state'] > 0 && $publishDays != 0 && !empty($publishDays))
								$content['publish_down'] = date('Y-m-d H:i:s', time() + ($publishDays * 24 * 60 * 60));
								$row = & JTable::getInstance('content');
								if (!$row->bind( $content )) {
									$mosMsg = $title . '***ERROR: bind' . $db->getErrorMsg();
									$objResponse->addAppend("fgmsgarea", "innerHTML", '<br />' . $mosMsg);
									return $objResponse->getXML();
								}
								$row->id = (int) $row->id;
								$user		= & JFactory::getUser();
								$row->created_by 	= $row->created_by ? $row->created_by : $user->get('id');
								if ($row->created && strlen(trim( $row->created )) <= 10) {
									$row->created 	.= ' 00:00:00';
								}
								
								$row->created = $itemDate;
								// Make sure the data is valid
								if (!$row->check()) {
									$e = '';
									foreach ($row->getErrors() as $error) {
										$e .= $error.'<br/>';
									}
									$mosMsg = '***ERROR*(check)*  Feed - '.$content['title'].':' . $db->getErrorMsg().'<br/>'.$e;
									$objResponse->addAppend("fgmsgarea", "innerHTML", '<br />' . $mosMsg);
									//return $objResponse->getXML();
									continue;
								}
								// Store the content to the database
								if (!$row->store()) {
									$mosMsg = $title . '***ERROR:' . $db->stderr();
									$objResponse->addAppend("fgmsgarea", "innerHTML", '<br />' . $mosMsg);
									return $objResponse->getXML();
								}
								// Check the article and update item order
								$row->checkin();
								$row->reorder('catid = '.(int) $row->catid.' AND state >= 0');
								require_once (JPATH_ADMINISTRATOR.DS.'components'.DS.'com_frontpage'.DS.'tables'.DS.'frontpage.php');
								$fp = new TableFrontPage($db);
								if ($feed->front_page)
								{
									// Is the item already viewable on the frontpage?
									if (!$fp->load($row->id))
									{
										// Insert the new entry
										$query = 'INSERT INTO #__content_frontpage' .
										' VALUES ( '. (int) $row->id .', 1 )';
										$db->setQuery($query);
										if (!$db->query())
										{
											$mosMsg = $title . '***ERROR:' . $db->getErrorMsg();
											$objResponse->addAppend("fgmsgarea", "innerHTML", '<br />' . $mosMsg);
											return $objResponse->getXML();
										}
										$fp->ordering = 1;
									}
								}
								$fp->reorder();
								$cache = & JFactory::getCache('com_content');
								$cache->clean();
								$nItems++;
							}
						}
					}
				}
				// update last_run status
				$last_run = $publishdate = date('Y-m-d H:i:s');
				$db->setQuery( "UPDATE #__feedgator SET last_run = '$last_run' WHERE id = '$feedId'" );
				$db->query();
				$feedMsg = "<b>$nItems</b> New content item(s) imported for <b>$curFeed</b> ($channelTitle), using the SimplePie parser.";
				$objResponse->addAppend("fgmsgarea", "innerHTML", $feedMsg . '<br />');
				if ($fgConfig->get('email_admin') == '1') $adminMsg .= '<span class="feedmsg">' . $feedMsg . "</span>\n";
				$processed++;
				$totItems += $nItems;
			}
		}
		if (!$processed) {
			$adminMsg .= "Nothing to process. Check your settings.";
			$objResponse->addAppend("fgmsgarea", "innerHTML", $adminMsg . "<br />");
		}
		$endTime = time();
		$procTime = $endTime - $startTime;
		if ($fgConfig->get('email_admin') == '1') {
			$adminMsg .= '<h1>END: ' . date('D F j, Y, H:i:s T') . "</h1></div>\n";
			$adminMsg .= '<h2>' . $processed . " Feeds processed in $procTime seconds.</h2>\n";
			$adminMsg .= '<h2>' . $totItems . ' Content items imported.</h2>';
			FeedgatorHelper::sendAdminEmail($adminMsg);
		}
		$objResponse->addAppend("fgmsgarea", "innerHTML", "<br /><b>$totItems</b> Content items imported in $procTime seconds.<br />");
		$closeLink = '<br /><a href="javascript:closeMsgArea();">Close this window</a><br />';
		$objResponse->addAppend("fgmsgarea", "innerHTML", $closeLink);
		return $objResponse->getXML();
	}
}
