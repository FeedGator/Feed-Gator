CREATE TABLE IF NOT EXISTS `#__feedgator` (
          `id` int(10) NOT NULL auto_increment,
          `title` varchar(100) NOT NULL default 'Untitled',
          `feed` text NOT NULL default '',
		  `trim_to` int(10) NOT NULL default '120',
          `sectionid` int(10) NOT NULL default '0',
          `catid` int(10) NOT NULL default '0',
          `default_author` varchar(100) NULL,
		  `created_by` int(11) NOT NULL default '0',
          `created` datetime NOT NULL default '0000-00-00 00:00:00',
		  `checked_out` int(11) unsigned NOT NULL default '0',
		  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
          `last_run` datetime NOT NULL default '0000-00-00 00:00:00',
          `published` tinyint(1) NOT NULL default '0',
          `front_page` tinyint(1) NOT NULL default '0',
          `shortlink` tinyint(1) NOT NULL default '0',
          `onlyintro` tinyint(1) NOT NULL default '0',
          PRIMARY KEY  (`id`)
        ) TYPE=MyISAM;
