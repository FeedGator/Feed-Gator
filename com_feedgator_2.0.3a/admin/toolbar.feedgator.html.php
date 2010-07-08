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



class TOOLBAR_feedgator {



	function _EDIT($edit) 

	{

		$cid = JRequest::getVar( 'cid', array(0), '', 'array' );

		$cid = intval($cid[0]);

		$text = ( $edit ? JText::_( 'Edit' ) : JText::_( 'New' ) );

		JToolBarHelper::title( JText::_( 'Feed' ).': <small><small>[ '. $text.' ]</small></small>', 'addedit.png' );

		JToolBarHelper::save();

		if ( $edit ) {

			JToolBarHelper::cancel( 'cancel', 'Close' );

		} else {

			JToolBarHelper::cancel();

		}

		JToolBarHelper::help( 'screen.content.edit' );

	}

	

	function _ABOUT()

	{

		JToolBarHelper::title( JText::_( 'About FeedGator' ), 'systeminfo.png' );

		JToolBarHelper::custom( 'support', 'help.png', 'help_f2.png', 'Help', false );

	}

	

	function _SUPPORT()

	{

		JToolBarHelper::title( JText::_( 'Feed Gator Help and Support' ), 'help_header.png' );

	}

	

	function _DEFAULT()

	{

		JToolBarHelper::title( JText::_( 'Manage RSS Feeds' ), 'addedit.png' );

		// button hack

		$bar = & JToolBar::getInstance('toolbar');

		$bar->appendButton( 'Link', 'refresh', 'Import All', '#" onclick="javascript: importall()"', false, false);

		$bar->appendButton( 'Link', 'upload', 'Import', '#" onclick="javascript: importfeed()"', true, false);

		//

		JToolBarHelper::publishList('publish', 'Enable');

		JToolBarHelper::unpublishList('unpublish', 'Disable');

		JToolBarHelper::addNew();

		JToolBarHelper::editList();

		JToolBarHelper::deleteList();

		JToolBarHelper::preferences( 'com_feedgator', '450' );

		JToolBarHelper::custom( 'support', 'help.png', 'help_f2.png', 'Help', false );

		

	}

	

}