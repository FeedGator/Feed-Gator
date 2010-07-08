<?php defined('_JEXEC') or die('Restricted access'); ?>

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



<p>Are you <strong class="blue">having problems</strong> using Feed Gator? Do you <strong class="blue">have questions</strong> about how to use Feed Gator? Do you need <strong class="blue">custom content aggregation or other development services?</strong> Do you want to configure Feed Gator to <strong class="blue">run automatically?</strong></p>

<p><strong>Here's the help you're looking for:</strong></p>

<h2 class="green">Joomlacode.org</h2>

<p>For general help using Feed Gator, the most current Frequently Asked
Questions, or to submit feature requests or suggestions...or to just let
me know how much you love the component :)...please visit <a
href="http://joomlacode.org/gf/project/feedgator">http://joomlacode.
org/gf/project/feedgator</a></strong>. <br />

  <br />

We try to help everyone but please be patient if you don't immediately
receive an answer. If you want to ask a question about your installation
of if you want to report a problem, please always mention the version of
your Joomla! installation and Feed Gator version!</p>

<hr />

<h2 class="green">Configuring automatic imports using cron</h2>

In order to have Feed Gator import your RSS feeds automatically at
regular intervals you must have the ability to run cron jobs on your
server. If you're not sure what a cron job is, just do a Google search
or contact your hosting provider for assistance.<br />

<br />I'm working on a way to run Feed Gator automatically for users
without access to the cron utility, but for now, cron is the only
option.<br /><br />

When you installed Feed Gator, a file called cron.feedgator.php was saved to your server in the /administrator/components/com_feedgator directory. All you need to do to run Feed Gator via cron is to execute this file with PHP from the command line - you don't need to modify the file in any way. Executing Feed Gator via cron is the same as clicking the "Import All" link from the administrative interface. All of your settings are preserved.<br /><br />

Here's an example of a cron entry to run automatic Feed Gator updates:<br />

<pre>

*/30 * * * * /usr/local/bin/php /var/www/your.web.site/htdocs/administrator/components/com_feedgator/cron.feedgator.php >> /dev/null</pre>

<p>This example would run the Feed Gator import every 30 minutes of every hour of every day. You may wish to run imports at different intervals, so just consult the cron documentation for specifics on how to do this.</p>

<p><b>NOTE: cron.feedgator.php is designed to be run from the directory that it was installed to. If you move the file to another directory, you will need to edit the file so that '$sitepath' gets set to the proper location.</b></p>

<hr />

<h2 class="green">Report a bad feed</h2>

If you have recieved an error while trying to import a feed, please let me know. I investigate EVERY feed that is reported, because I want Feed Gator to work with ALL feeds, even ones that don't conform to the RSS validation standard. I'm serious about this. BUT please follow these steps before reporting a feed. </h4>

<p>1. First make sure the feed URL is correct - I hate wasting time checking bogus URLs. You can do this by copy and pasting the feed URL into your browser's address bar. If you see an error when trying to view the feed with a browser, then it cannot be imported using Feed Gator. If you see a web site instead of a feed when you view the URL in a browser, then it cannot be imported. This will also help to make sure you've typed the URL correctly. </p>

<p>2. If the URL is a legitimate feed URL, try to import it a few times before reporting it. Some busy or slow servers can occasionally cause Feed Gator to time out waiting for the feed to be fetched. This is not a bug or a bad feed.</p>

<p>Once you're sure the feed URL is correct, you can post it on the support forum and we'll take a look at it.</p>

<p>If you are having a problem with a feed, but don't see the error
message mentioned above, then please report the feed by starting a new
topic in the forum at <a
href="http://joomlacode.org/gf/project/feedgator">http://joomlacode.
org/gf/project/feedgator</a>. </p>

<hr />

<h2 class="green">Custom development services or support</h2>

If you need personalized priority support for Feed Gator, I would be
happy to help you get things running for a small fee. Please contact me on the forum</a>. I can
usually respond to these types of requests within a few hours.<br /><br/>


