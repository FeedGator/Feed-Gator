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

class FeedgatorHelper {
	// Shorten article descriptions based on maximum length
	function makeTeaser($text, $trimTo){

		$fgConfig = JComponentHelper::getParams ('com_feedgator');

		if (empty($trimTo) || strlen($text) <= intval($trimTo))
		return($text);

		// end neatly at the end of a word
		$ret = substr($text, 0, $trimTo);
		$lastSpace = strrpos($ret, " ");
		if ($lastSpace)
		$ret = substr($ret, 0, $lastSpace);

		// a cheap way to see if original string contains some HTML
		// nested tags could be a problem, but this will work in most cases for href and img tags
		// assumes tags are properly closed in the source text
		// the point of this bit is to avoid truncating text and leaving tags unclosed
		// this could use some work to make it smarter though...
		if ($fgConfig->get('xhtml_clean') && preg_match('/<.+=/', $ret, $regs))
		{
			if (!strrpos($ret, '>') || (strrpos($ret, '>') < strrpos($ret, '<')))
			{
				$startPos = strlen($ret) - 1;
				$endTag = strpos(substr($text, $startPos), '>');
				$ret .= substr($text, $startPos+1, $endTag);

				// fix broken <p>, <div>, <span> tags
				// now add back some text to account for the tags
				/* for the next version...maybe use html_tidy...
				$numTags = preg_match_all('/(<[^<].+>)/', $ret, $regs);
				for ($i = 1, $tagLength = 0; $i <= $numTags; $i++) $tagLength += strlen($regs[$i]);
				$diff = $trimTo - (strlen($ret) - $tagLength);
				if ($diff > 0) $ret .= makeTeaser(substr($text, strlen($ret)-1), $diff, true);
				*/
			}
		}
		return $ret;
	}


	function getFieldName($id, $table) {

		$db = & JFactory::getDBO();
		$query = "SELECT title FROM #__$table WHERE id = '". $id ."'";
		$db->setQuery( $query );
		$name = $db->loadResult();
		return $name;
	}



	function extractTerms($text) {


		$fgConfig = JComponentHelper::getParams ('com_feedgator');


		$text = html_entity_decode( $text, ENT_QUOTES );
		$text = urlencode(utf8_encode(strip_tags($text)));

		if (!trim($text)) return '';

		$request = 'http://api.search.yahoo.com/ContentAnalysisService/V1/termExtraction';
		$request .= '?context='.$text.'&output=php&appid='.$fgConfig->get('yahoo_app_id');

		$response = file_get_contents($request);

		// If no response, then try the internal tag generation.
		if ($response === false) return self::generateTags($text);

		$respArray = unserialize($response);
		$resultSet = $respArray['ResultSet'];
		$results = $resultSet['Result'];

		$results = self::removeIgnoreWords($results, 1);

		$results = is_array($results) ? array_slice($results, 0, $fgConfig->get('max_tags')) : array();

		$terms = implode(',', $results);

		//$terms = utf8_decode($terms);

		return $terms;
	}

	function removeIgnoreWords($results, $utf = false) {

		$fgConfig = JComponentHelper::getParams ('com_feedgator');

		if ($fgConfig->get('use_ignore_list') == '1') {
			$ignore_words = $fgConfig->get('ignore_list');
			$ignore_words = $utf ? utf8_encode($ignore_words) : $ignore_words;
			$ignoreArray = explode(',', $ignore_words);
			$results = array_diff($results, $ignoreArray);
		}
		return $results;
	}

	function trimTags(&$term, $key)
	{
		$term = trim($term);
		$term = str_replace(array("\n","\r"), ' ', $term);
		$term = preg_replace('/[,.?:;!()=\\*\']/', '', $term);
	}
	function filterTerms($var)
	{
		$fgConfig = JComponentHelper::getParams ('com_feedgator');

		$keep = !empty($var) && $var != '' && $var != NULL && !preg_match('/^\s*$/', $var);
		$min_tag_chars = $fgConfig->get('min_tag_chars');
		if (!empty($min_tag_chars) && intval($fgConfig->get('min_tag_chars')) > 0)
		$keep = $keep && strlen($var) >= intval($fgConfig->get('min_tag_chars'));

		return($keep);
	}
	// use a simple frequency algorithm to compute meta tags
	function generateTags($text) {

		$fgConfig = JComponentHelper::getParams ('com_feedgator');

		$text = strtolower(html_entity_decode(strip_tags($text), ENT_QUOTES));


		if (!trim($text)) return '';

		$words = explode(' ', $text);
		array_walk($words, array('FeedgatorHelper','trimTags'));
		$words = array_filter($words, array('FeedgatorHelper','filterTerms'));

		$words = self::removeIgnoreWords($words);

		$words = array_count_values($words);
		arsort($words);

		$words = is_array($words) ? array_slice($words, 0, $fgConfig->get('max_tags')) : array();
		$words = implode(',', array_keys($words));

		return $words;
	}

	function sendAdminEmail($message = '') {
		global $mainframe;

		$db		=& JFactory::getDBO();
		$fgConfig = JComponentHelper::getParams ('com_feedgator');

		$mailfrom 		= $mainframe->getCfg( 'mailfrom' );
		$fromname 		= $mainframe->getCfg( 'fromname' );

		$query = 'SELECT name, email, sendEmail' .
		' FROM #__users' .
		' WHERE LOWER( usertype ) = "super administrator"';
		$db->setQuery( $query );
		$rows = $db->loadObjectList();

		if ( ! $mailfrom  || ! $fromname ) {
			$fromname = $rows[0]->name;
			$mailfrom = $rows[0]->email;
		}

		$subject = html_entity_decode($fgConfig->get('email_subject'), ENT_QUOTES);
		$email = $fgConfig->get('admin_email');

		if ($fgConfig->get('html_email') == '1') {
			$css = '<style type="text/css">'
			. 'body { color:#000000; font-size: 12px; font-family:Arial, Helvetica, sans-serif;}'
			. '.feedmsg { color:#0400A2; line-height: 1.4em;}'
			. '#feedinfo { border:1px solid #bababa; padding:0 10px;}'
			. 'h1 { color:#618700; font-size: 16px; margin:10px 0 5px 0;}'
			. 'h2 { color:#e56d02; font-size: 14px; margin:5px 10px 0 10px;}'
			. '.small { color:#999999; font-size: 10px;}'
			. '#feedinfo a:link, #feedinfo a:visited { color:#990000;}'
			. '</style>';

			$message = $css . nl2br($message);
		}
		else {
			$message = strip_tags($message);
		}

		JUtility::sendMail($mailfrom, $fromname, $email, $subject, $message, $fgConfig->get('html_email'));
	}

	/*
	* This function was added by Rembo (http://scriptorium.serve-it.nl)
	* Function to do some basic debugging. By calling this function you can write a message to a log file.
	*/

	function dbugme($fg_debugmsg) {
		define("DATE_FORMAT","d-m-Y H:i:s");

		// Define the local path and name for your logfile. Note that depending on your Joomla! installation you don't always
		// have the correct rights to write somewhere in the Joomla directories. This is often true for Joomla! installations
		// from control panels like the one from Installatron.
		// In such cases just place the logfile in the root of your website or in a self created directory.
		define("FG_LOG_FILE","/home/fxincome/domains/fxincome.com/public_html/test/fg_debug.html");

		$logfileHeader='
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
	<html>
	<head>
   		<title>Feedgator debug log</title>
	</head>
	<body>
	  	<table cellpadding="0" cellspacing="1" border="1">
    		<tr>
      			<th>Date</th>
      			<th>Message</th></tr>'."\n";

		$actualTime = date(DATE_FORMAT);

		$logEntry = " <tr>
        			<td>$actualTime</td>
                		<td>$fg_debugmsg</td>
    				</tr>\n";

		if (!file_exists(FG_LOG_FILE)) {
			$logFile = fopen(FG_LOG_FILE,"w");
			fwrite($logFile, $logfileHeader);
		} else {
			$logFile = fopen(FG_LOG_FILE,"a");
		}

		fwrite($logFile,$logEntry);
		fclose($logFile);
	}
}

?>
