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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( JApplicationHelper::getPath( 'toolbar_html' ) );

switch ($task)
{
	case 'add':
		TOOLBAR_feedgator::_EDIT(false);
		break;
		
	case 'edit':
		TOOLBAR_feedgator::_EDIT(true);
		break;

	case 'about':
		TOOLBAR_feedgator::_ABOUT();
		break;
	
	case 'support':
		TOOLBAR_feedgator::_SUPPORT();
		break;

	default:
		TOOLBAR_feedgator::_DEFAULT();
		break;
}