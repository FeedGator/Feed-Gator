<?php defined('_JEXEC') or die('Restricted access'); ?>


<div class="fgnarrow"><h1>Feed Gator RSS news feed aggregator component for Joomla!</h1>';

<strong>Official</strong> FeedGator development and support has seemed to stop so a group of people have begun working on it seperatly and hosting of the files has changed to the link below.  A fork of the component may soon follow.<br /><br />


Your Installed Version: 2.0 beta1
</strong> <a href="http://joomlacode.org/gf/project/feedgator/forum/?action=ForumBrowse&forum_id=6729">Check for latest release</a><br />
<!--
* FeedGator - Aggregate RSS newsfeed content into a Joomla! database
* @version 2.0 beta1
* @package FeedGator
* @author Stephen Simmons (inquiries@simmonstech.net)
* @now continued and modified by Remco Boom & Stephane Koenig and others
* @Joomla 1.5 Version by J. Kapusciarz (mrjozo)
* @copyright (C) 2005 by Stephen Simmons - All rights reserved
* @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
-->



<p><strong>RSS news feed aggregator for Joomla!</strong> Imports RSS
feeds into your Joomla! database as regular content items, so you can
get more control of the syndicated content on your site. Display RSS
content in blog format, or any other method supported by Joomla! Turn
your site into a sophisticated news reader.</p>



<p>This component is what drives the news section of many Joomla!
websites. Feed Gator has lots of features to give you the power to
manipulate the imported content in useful ways: Be sure to check the
release notes for important information.</p> 

<h3 class="green">Features Include</h3>

<div id="featureleft">

<ul>

<li>RSS feed content can be stored in any section or category</li>

<li><strong>Robust RSS fetching:</strong>

<li>Feed Gator uses the <a href="http://simplepie.org"
target="_blank">SimplePie</a> parser to process feeds. SimplePie is by
far the best parser out there and supports many different feed types:
RSS 0.90 to 2.0, Atom 0.3 and 1.0. In addition it also supports a number
of namespaced elements out of the box, like Dublin Core, iTunes RSS and
XHTML 1.0 For a full list check the <a
href="http://simplepie.
org/wiki/faq/what_versions_of_rss_or_atom_do_you_support"
target="_blank">SimplePie FAQ item</a> on this subject.

</li>

<li>Sophisticated automatic Term Extraction (Tagging) using Yahoo's term extraction API</li>

</ul>

</div>

<div id="featureright">

<ul>

<li>Optionally include trackback links to the original content</li>

<li>Trackback links optionally follow accessibility guidelines</li>

<li>Optionally auto-publish content</li>

<li>Ability to specify the number of days content remains published</li>

<li>CSS-based, skinnable admin interface!</li>

<li>Properly handles duplicates</li>

<li>Automatic feed caching</li>

<li>Imports can be automated using cron</li>

<li>Easy to read HTML reports </li>

</ul>

</div>

<hr />

<h2>Installation</h2>

Install as you would any other Joomla component. Installers->Components, browse to the .zip file, and click upload/install<br /><br />



<h2>Release Notes</h2>

<p><strong>1-January-2007 - Version SP_0.3</strong><br />
- FIXED  - Trackback link was not showing in the intro text anymore. It is now.<br />
- FIXED  - cron.feedgator.php now also working on PHP installation on Windows systems. Thank to Jens Kirk Foged from
Sunburst WebConsult for fixing this.<br />
- IMPROVED - If a feed title was missing Feed Gator wouldn't process the feed even if it's a valid feed anyway. Feed Gator now uses a more robust method to
             determine if a feed is valid or not and will process feeds without a title.<br />
- IMPROVED - Feed Gator now does a better job determining if an item has been imported earlier. This should avoid endless importing of items that have already
             been imported.<br />
- ADDED  - A backend option under the feed properties to set if the feed article content is only stored in the intro text (and NOT in the full article text). 
           This option is disabled (set to 0) by default but if enabled it will only store the intro text in the Joomla content.<br />
- REMOVED - Verbose trackback link from the configuration menu. This option was obsolete and confuses people.<br />
- REMOVED - The language tab from the configuration menu because it is not being used.<br />

   <br />

<p><strong>24-December-2007 - Version SP_0.2</strong><br />

- Implemented a few options in the back panel.<br />
&nbsp;&nbsp;&nbsp;1) Force fsockopen in the Configuration. If Feed Gator doesn't work on your webserver you can try enabling this setting.<br />
&nbsp;&nbsp;&nbsp;2) Create Short link under Feeds. If the trackback link is too long you can set this option to enabled. It will show the Feed title instead.<br />
&nbsp;&nbsp;&nbsp;3) Add link to intro text under Feeds. Enable if you want the trackback link to show in the article intro.<br />
- FIXED  - Feed Gator wouldn't process feed items without a subject. It will now.<br />
- IMPROVED - Feed Gator only looked for a matching item title to determine if it had imported the article already. Right now it will look for a match on both the Title (if available) and Intro text.<br />
- FIXED  - On some servers Feed Gator returned an 'XML error'. This happened mostly on PHP5.x servers and had to do with an empty line above an xajax call.<br />
- ADDED - Automatic cache detection. If you enabled caching in Joomla Feed Gator will try to utilize it. This is expirimental.<br />

  <br />

<p><strong>27-October-2007 - Version SP_0.1</strong><br />

- Implemented the SimplePie parser and removed MagPie/Domit/Pear selection.<br />
- Removed the XHTML Clean options because SimplePie already returns clean code.<br />
- FIXED - A SQL error popped up if Joomla! debug feature was on and New feed was selected.<br />
- IMPROVED - Started to clean up a lot of code. This is much more work than I expected so it's work in progress.<br />

  <br />

<p><strong>25-September-2007 - Version 0.96</strong><br />

- Reverted back to older copy of Snoopy.class.inc because certain feeds were not parsed by MagPie with the newer version.<br />
- Changed link in footer to point to the feedgator project page.<br />
- Changed version check link on the About Feed Gator page to point to the correct forum.<br />

  <br />


<p><strong>30-August-2007 - Version 0.95</strong><br />

- FIXED - error in parsing feeds if PEAR is not installed.<br />
- Added a debugging routine to functions.feedgator.php<br />
- Changed etc directory to etz to avoid problems with Apache filters denying access to anything with etc in directory name.<br />


  <br />


<p><strong>20-August-2007 - Version 0.94_unofficial</strong><br />

- FIXED - a huge pile of bugs. See the above Joomlacode.org link for details<br />

<br />

  Special thanks to Remco Boom and Stephane Koenig for their help.<br />

  <br />


<p><strong>29-September-06 - Version 0.7 alpha</strong><br />

  Upgrade Magpie Parser and fix final register_globals bug

  <br /><br />

<u>CHANGELOG</u><br /><br />

- FIXED - Bug with cron not working with register globals off<br />
- CHANGED - Upgraded Magpie Parser to version to 0.7a<br />

<br />
<strong>NOTE: If upgrading you can just copy files over existing ones</strong><br />

  <br />

<p><strong>27-September-06 - Version 0.6 alpha</strong><br />

  Minor bug fix and feature addition.

  <br /><br />

<u>CHANGELOG</u><br /><br />

- FIXED - Bug with frontpage/publish option<br />
- ADDED - Frontpage option to feed list<br />


<br />
<strong>NOTE: If upgrading you can just copy files over existing ones</strong>


  <br /><br />

<p><strong>26-September-06 - Version 0.5 alpha</strong><br />

  Fixed major problem with 'register_globals' and Joomla 1.0.11.

  <br /><br />

<u>CHANGELOG</u><br /><br />

- FIXED - Editing problems with Joomla 1.0.11<br />
- CHANGED - Default Author can be set per feed<br />
- CHANGED - Front Page can now be set per feed<br />

   <br />

<strong>IMPORTANT:  Database changes have occurred so a reinstall is a must to upgrade.  Please Backup Your Data before upgrading!</strong>


  <br /><br />

<p><strong>20-April-06 - Version 0.4 alpha</strong><br />

Important new features and all known bugs fixed. Tested on Joomla 1.0.8.<br />

<br />

<u>CHANGELOG</u><br /><br />

- FIXED - Global configuration options were not respected for imported content items<br />
- FIXED - Import runs would terminate if a bad feed was encountered - now all feeds will be attempted (introduced in 0.3) <br />
- FIXED - Parser selection would refuse to check all available parsers in some cases <br />
- FIXED - Duplicates were imported if content was marked unpublished<br />
- FIXED - Miscellaneous minor fixes/adjustments</p>

<p>- ADDED - Automatic import runs via cron<br />
- ADDED - Alternate meta tag computation algorithm using frequency analysis<br />
- ADDED - Ability to specify the minimum number of characters in computed tags<br />
- ADDED - Excluded words list for meta tag computation<br />
- ADDED - Email reports sent to admins after import (with HTML option)<br />
- ADDED - Ability to choose parser search order<br />
- ADDED - More robust handling of invalid feeds<br />
- ADDED - Additional status message to import status window<br />
- ADDED - Help and Support page to admin back end </p>

<p>- CHANGED - Progress bar now ticks in a pretty green<br />
  - CHANGED - Replaced 

 enabled icon on Feed Manager page <br />

 - CHANGED - Uninstall no longer deletes tables, to make future upgrades easier</p>

 <br />

<p><strong>06-Mar-06 - Version 0.3 alpha</strong><br />

  A bug fix release with a few new features (all known and reported bugs fixed). Tested on Joomla 1.0.8.<br />

  <br />

  <u>CHANGELOG</u><br />

  - FIXED - Some data was not being properly escaped, could cause a hang during import operation<br />

  - FIXED - Xajax path problem on sites being run from a sub-directory caused hang during import<br />

  <br />

  - ADDED - Imported items may now be promoted to the front page<br />

  - ADDED - Target frames may be specified for external links<br />

  <br />

  - CHANGED - Better handling of invalid or problematic feeds, with 'report bad feed' feature<br />

  - CHANGED - Import progress bar ticker now ticks more slowly to help prevent filling screen for long runs<br />

  - CHANGED - Category and Section names are displayed instead of IDs in feed listing<br />

  - CHANGED - Minor changes to feed listing for improved usability<br />

  - CHANGED - Terminology for feeds from 'published' to 'enabled'<br />

  <br />

  <br />


  <strong>27-Feb-06 - Version 0.2 alpha</strong><br />

  A fairly major features release with some minor bug fixes

  <br />

  <br />

  <u>CHANGELOG</u><br />

  - FIXED - Leaving Trim Intro parameter blank on Edit Feed page caused nothing to be stored in intro column<br />

  - FIXED - Misaligned table columns on feeds listing page. Now properly aligned left.<br />

  - FIXED - Broken image on some admin pages<br />

  - FIXED - Warning if PEAR XML/RSS package is not installed<br />

  <br />

  - ADDED - Completely new integrated backend administration<br />

  - ADDED - Integration with Yahoo's Term Extraction API for sophisticated tagging of content items<br />

  - ADDED - Replaced messy post-import status messages with clean AJAX-based log status window<br />

  - ADDED - Many new global configuration features:<br />

  - optionally include orignal link in content items, and make it conform to accessibility guidelines<br />

  - optionally extract most relevant tags in content and store as meta keywords<br />

  - optionally auto-publish content<br />

  - ability to set # of days content items will remain published<br />

  - optionally produce clean XHTML from content items using HTML Tidy<br />

  - ability to capture or set author_alias<br />

  - ability to capture feed category and store as tag<br />

  - ability to store Joomla section and category as tags<br />

  - descriptive help text for all parameters<br />

  <br />

  - CHANGED - Temporarily reduced cache expiration for magpieRSS from 1 hour to 15 minutes for testing puposes<br />

  - CHANGED - Some code refactoring and clean up<br />

  <br />


  <strong>23-Feb-06 - Version 0.1 alpha</strong><br />

  - Initial public release <br />

  - Core features in place<br />

  - Apparently stable<br />

  <br />

  <br />

I hope you find this component useful. If you have any questions or
problems, I'll do my best to help you out, just stop by the forum: <a
href="http://joomlacode.org/gf/project/feedgator">http://joomlacode.
org/gf/project/feedgator</a>.

  <br />

  <br />

The usual disclaimer stuff: I will not in any way be held responsible
for any dire outcomes resulting from using this software, but if good
things happen because you used it, we want all the credit :)

  <br />

  <br />

</p>
</div>

<form action="index2.php" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="option" value="com_feedgator" />
<input type="hidden" name="task" value="" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>