<?php

/**
* FeedGator - Aggregate RSS newsfeed content into a Joomla! database
* @version 2.0 RC1
* @package FeedGator
* @author Stephen Simmons (inquiries@simmonstech.net)
* @now continued and modified by Remco Boom & Stephane Koenig and others
* @Joomla 1.5 Version by J. Kapusciarz (mrjozo)
* @copyright (C) 2005 by Stephen Simmons - All rights reserved
* @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
*
**/


define( '_JEXEC', 1 );

define('DS', DIRECTORY_SEPARATOR);
define('JPATH_BASE', substr(__FILE__,0,strrpos(__FILE__, DS."components")));
define('JPATH_COMPONENT', JPATH_BASE .DS.'components'.DS.'com_feedgator');

require_once( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once( JPATH_BASE .DS.'includes'.DS.'framework.php' );
require_once( JPATH_BASE .DS.'includes'.DS.'helper.php' );
require_once( JPATH_BASE .DS.'includes'.DS.'toolbar.php' );

$mainframe =& JFactory::getApplication('site');
$mainframe->initialise();

require_once ( JPATH_COMPONENT.DS.'controller.php');
require_once ( JPATH_COMPONENT.DS.'helpers'.DS.'feedgator.php');


define('SPIE_CACHE_AGE', 60*10); 


require_once( JPATH_COMPONENT.DS.'inc'.DS.'xajax.inc.php');
require_once( JPATH_COMPONENT.DS.'inc'.DS.'simplepie'.DS.'simplepie.inc');

JRequest::setVar('task','cron','get');

$controller = new FeedgatorController();
$controller->importall();

echo 'Import finished';



?>

