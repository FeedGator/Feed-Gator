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

// no direct access
defined('_JEXEC') or die('Restricted access');

// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'feedgator.php');

define('SPIE_CACHE_AGE', 60*10); 

require_once( JPATH_COMPONENT.DS.'inc'.DS.'xajax.inc.php');
require_once( JPATH_COMPONENT.DS.'inc'.DS.'simplepie'.DS.'simplepie.inc');

// Require specific controller if requested
if($controller = JRequest::getVar('controller')) {
	require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
}

// Create the controller
$classname	= 'FeedgatorController'.$controller;
$config = array();
$config['default_task'] = 'feeds';
$controller = new $classname( $config );

$task = JRequest::getCmd('task');
	
switch (strtolower($task))
{
	case 'add'  :
	case 'new'  :
		$controller->editContent(false);
		break;
	case 'edit' :
		$controller->editContent(true);
		break;
	case 'apply' :
	case 'save' :
		$controller->save();
		break;
	case 'publish' :
		$controller->publishFeeds(1,'publish');
		break;
	case 'unpublish' :
		$controller->publishFeeds(0,'unpublish');
		break;
	case 'front_yes' :
		$controller->frontpageFeeds(1,'front_yes');
		break;
	case 'front_no' :
		$controller->frontpageFeeds(0,'front_no');
		break;
	default :
		$controller->execute( $task );
		break;
}
// Redirect if set by the controller
$controller->redirect();

?>
