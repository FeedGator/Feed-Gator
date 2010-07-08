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

jimport('joomla.application.component.model');


class FeedgatorModelFeed extends JModel
{

	var $_id = null;

	var $_data = null;


	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid', array(0), '', 'array');
		$edit	= JRequest::getVar('edit',true);
		if($edit)
		$this->setId((int)$array[0]);
	}

	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;
	}


	function &getData()
	{

		if ($this->_loadData())
		{

		}
		else  $this->_initData();

		return $this->_data;
	}


	function isCheckedOut( $uid=0 )
	{
		if ($this->_loadData())
		{
			if ($uid) {

				return ($this->_data->checked_out && $this->_data->checked_out != $uid);
			} else {
				return $this->_data->checked_out;
			}
		}
	}


	function checkin()
	{
		if ($this->_id)
		{
			$feed = & $this->getTable();
			if(! $feed->checkin($this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return false;
	}


	function checkout($uid = null)
	{
		if ($this->_id)
		{
			if (is_null($uid)) {
				$user	=& JFactory::getUser();
				$uid	= $user->get('id');
			}

			$feed = & $this->getTable();
			if(!$feed->checkout($uid, $this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			return true;
		}
		return false;
	}


	function store($data)
	{
		$row =& $this->getTable();

		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		$row->created = gmdate('Y-m-d H:i:s');


		if (!$row->id) {
			$where = 'catid = ' . (int) $row->catid ;
		}

		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

	function delete($cid = array())
	{
		$result = false;

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM #__feedgator'
			. ' WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}


	function publish($cid = array(), $publish = 1)
	{
		$user 	=& JFactory::getUser();

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__feedgator'
			. ' SET published = '.(int) $publish
			. ' WHERE id IN ( '.$cids.' )'
			. ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ) )'
			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}

	function frontpage($cid = array(), $frontpage = 1)
	{
		$user 	=& JFactory::getUser();

		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__feedgator'
			. ' SET front_page = '.(int) $frontpage
			. ' WHERE id IN ( '.$cids.' )'
			. ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ) )'
			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}


	function _loadData()
	{
		if (empty($this->_data))
		{
			$query = 'SELECT *'.
			' FROM #__feedgator ' .
			' WHERE id = '.(int) $this->_id;
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			return (boolean) $this->_data;
		}
		return true;
	}

	function _initData()
	{
		if (empty($this->_data))
		{
			$feed = new stdClass();
			$feed->id					= 0;
			$feed->checked_out			= 0;
			$feed->sectionid 			= 0;
			$feed->checked_out_time			= 0;
			$feed->title=null;
			$feed->feed=null;
			$feed->description=null;
			$feed->trim_to=null;
			$feed->sectionid=null;
			$feed->catid=null;
			$feed->published=null;
			$feed->front_page=null;
			$feed->shortlink=null;
			$feed->introlink=null;
			$feed->onlyintro=null;
			$feed->default_author=null;
			$feed->created=null;
			$feed->last_run=null;
			$this->_data					= $feed;
			return (boolean) $this->_data;
		}
		return true;
	}
}