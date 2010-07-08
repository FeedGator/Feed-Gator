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


class TableFeed extends JTable
{

	var $checked_out = 0;
	var $checked_out_time = 0;
	var $sectionid = 0;
	var $id=null;
  	var $title=null;
  	var $feed=null;
  	var $description=null;
	var $trim_to=null;
  	var $catid=null;
  	var $published=null;
  	var $front_page=null;
  	var $shortlink=null;
	var $onlyintro=null;
  	var $default_author=null;
  	var $created=null;
  	var $last_run=null;
	/**
	* @param database A database connector object
	*/
	function TableFeed(&$db)
	{
		parent::__construct( '#__feedgator', 'id', $db );
	}
	
	

}
