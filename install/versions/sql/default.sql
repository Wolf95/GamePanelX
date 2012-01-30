CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `relid` int(10) unsigned NOT NULL,
  `typeid` smallint(5) unsigned NOT NULL,
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `user_type` enum('admin','user') NOT NULL DEFAULT 'admin',
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `activity_types` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_added` datetime NOT NULL,
  `orig_login` datetime NOT NULL,
  `status` enum('active','suspended','closed') NOT NULL DEFAULT 'active',
  `logged_in` enum('Y','N') NOT NULL DEFAULT 'N',
  `receive_email` enum('Y','N') NOT NULL DEFAULT 'Y',
  `last_login` datetime NOT NULL,
  `last_response` datetime NOT NULL,
  `notes` text NOT NULL,
  `orig_ip` varchar(48) NOT NULL,
  `orig_host` varchar(48) NOT NULL,
  `last_ip` varchar(48) NOT NULL,
  `last_host` varchar(48) NOT NULL,
  `username` varchar(48) NOT NULL,
  `password` varchar(255) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `language` varchar(255) NOT NULL DEFAULT 'english',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `archives` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `supported` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `networkid` int(10) unsigned NOT NULL,
  `cfgid` int(10) unsigned NOT NULL,
  `date_created` datetime NOT NULL,
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `status` enum('none','complete','running','failed') NOT NULL DEFAULT 'none',
  `installation_status` enum('none','complete','running','failed') NOT NULL DEFAULT 'none',
  `description` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `cfg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `max_slots` int(12) NOT NULL DEFAULT '12',
  `port` int(12) NOT NULL,
  `date_added` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  `type` enum('game','voip','other') NOT NULL DEFAULT 'other',
  `available` enum('Y','N') NOT NULL DEFAULT 'Y',
  `based_on` enum('cmd','cfg') NOT NULL DEFAULT 'cmd',
  `is_steam` enum('Y','N') NOT NULL DEFAULT 'N',
  `is_punkbuster` enum('Y','N') NOT NULL DEFAULT 'N',
  `notes` text NOT NULL,
  `description` text NOT NULL,
  `cmd_line` text NOT NULL,
  `automagical` blob NOT NULL,
  `size` varchar(36) NOT NULL,
  `short_name` varchar(36) NOT NULL,
  `query_name` varchar(64) NOT NULL,
  `steam_name` varchar(128) NOT NULL,
  `long_name` varchar(255) NOT NULL,
  `mod_name` varchar(255) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `style` varchar(255) NOT NULL,
  `log_file` varchar(255) NOT NULL,
  `reserved_ports` varchar(255) NOT NULL,
  `tcp_ports` varchar(255) NOT NULL,
  `udp_ports` varchar(255) NOT NULL,
  `executable` varchar(255) NOT NULL,
  `map` varchar(255) NOT NULL,
  `setup_cmd` varchar(255) NOT NULL,
  `working_dir` varchar(255) NOT NULL,
  `setup_dir` varchar(255) NOT NULL,
  `config_file` varchar(255) NOT NULL,
  `pid_file` varchar(255) NOT NULL,
  `cfg_default` text NOT NULL,
  `cfg_ip` varchar(64) NOT NULL,
  `cfg_port` varchar(64) NOT NULL,
  `cfg_max_slots` varchar(64) NOT NULL,
  `cfg_map` varchar(64) NOT NULL,
  `cfg_password` varchar(64) NOT NULL,
  `cfg_internet` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `cfg_addons` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `srvid` int(11) unsigned NOT NULL,
  `networkid` int(11) unsigned NOT NULL,
  `date_added` datetime NOT NULL,
  `type` enum('mod','mappack','other') NOT NULL,
  `available` enum('Y','N') NOT NULL DEFAULT 'Y',
  `status` enum('complete','running') NOT NULL DEFAULT 'running',
  `notes` text NOT NULL,
  `remove_dirs` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `target` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `addon_hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `cfg_configs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `srvid` int(11) unsigned NOT NULL,
  `name` varchar(64) NOT NULL,
  `dir` varchar(128) NOT NULL,
  `description` varchar(255) NOT NULL,
  `rmcmd1` varchar(64) NOT NULL,
  `rmcmd2` varchar(64) NOT NULL,
  `rmcmd3` varchar(64) NOT NULL,
  `rmcmd4` varchar(64) NOT NULL,
  `rmcmd5` varchar(64) NOT NULL,
  `rmcmd6` varchar(64) NOT NULL,
  `rmcmd7` varchar(64) NOT NULL,
  `rmcmd8` varchar(64) NOT NULL,
  `rmcmd9` varchar(64) NOT NULL,
  `rmcmd10` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `cfg_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `srvid` int(10) unsigned NOT NULL,
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `required` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `client_edit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `usr_def` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `simpleid` tinyint(3) unsigned NOT NULL,
  `cmd_line` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cfg_file` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `name` varchar(64) NOT NULL,
  `default_value` varchar(128) NOT NULL,
  `description` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `cfg_rcon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `srvid` int(11) NOT NULL,
  `cmd` varchar(255) NOT NULL,
  `allow_client` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `billing_clientid` int(10) unsigned NOT NULL,
  `date_added` datetime NOT NULL,
  `orig_login` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `last_response` datetime NOT NULL,
  `status` enum('active','suspended','closed') NOT NULL DEFAULT 'active',
  `logged_in` enum('Y','N') NOT NULL DEFAULT 'N',
  `notes` text NOT NULL,
  `orig_ip` varchar(48) NOT NULL,
  `orig_host` varchar(48) NOT NULL,
  `last_ip` varchar(48) NOT NULL,
  `last_host` varchar(48) NOT NULL,
  `username` varchar(48) NOT NULL,
  `password` varchar(255) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `street_address1` varchar(255) NOT NULL,
  `street_address2` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zip_code` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `language` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `configuration` (
  `setting` varchar(36) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `domains` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `last_updated_by` int(10) unsigned NOT NULL,
  `updatenum` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `date_added` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  `is_enabled` enum('Y','N') NOT NULL DEFAULT 'Y',
  `ip` varchar(20) NOT NULL,
  `mx` varchar(255) NOT NULL,
  `domain` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `loadavg` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `networkid` int(11) unsigned NOT NULL,
  `mem_total` int(11) unsigned NOT NULL,
  `mem_free` int(11) unsigned NOT NULL,
  `date_added` datetime NOT NULL,
  `cpu` decimal(6,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `network` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` int(11) unsigned NOT NULL,
  `available` enum('Y','N') NOT NULL DEFAULT 'Y',
  `physical` enum('Y','N') NOT NULL DEFAULT 'N',
  `os` enum('linux','windows','other') NOT NULL DEFAULT 'other',
  `linux_flavor` enum('arch','centos','debian','fedora','generic','gentoo','ubuntu') NOT NULL DEFAULT 'generic',
  `date_added` datetime NOT NULL,
  `conn_user` blob NOT NULL,
  `conn_pass` blob NOT NULL,
  `conn_port` blob NOT NULL,
  `ip` varchar(20) NOT NULL,
  `location` varchar(48) NOT NULL,
  `datacenter` varchar(48) NOT NULL,
  `description` varchar(255) NOT NULL,
  `accounts_dir` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `notify` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` int(10) unsigned NOT NULL,
  `relid` int(10) unsigned NOT NULL,
  `seen` enum('Y','N') NOT NULL DEFAULT 'N',
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `notify_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `notify_type` varchar(36) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(48) NOT NULL,
  `href` varchar(48) NOT NULL,
  `image` varchar(128) NOT NULL,
  `image_text` varchar(48) NOT NULL,
  `popup_text` varchar(128) NOT NULL,
  `sort_order` int(12) NOT NULL,
  `template` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `servers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `billingid` int(10) unsigned NOT NULL,
  `domainid` int(10) unsigned NOT NULL,
  `port` int(10) unsigned NOT NULL,
  `max_slots` smallint(5) unsigned NOT NULL,
  `networkid` int(10) unsigned NOT NULL,
  `system_priority` tinyint(3) NOT NULL,
  `system_cpucore` tinyint(3) NOT NULL,
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL,
  `type` enum('game','voip','other') NOT NULL DEFAULT 'other',
  `status` enum('active','suspended','closed') NOT NULL DEFAULT 'active',
  `creation_status` enum('complete','running','failed') NOT NULL DEFAULT 'running',
  `update_status` enum('complete','running','failed') NOT NULL DEFAULT 'complete',
  `enable_priority` enum('Y','N') NOT NULL DEFAULT 'N',
  `enable_cpucore` enum('Y','N') NOT NULL DEFAULT 'N',
  `show_cmd_line` enum('Y','N') NOT NULL DEFAULT 'N',
  `client_file_man` enum('Y','N') NOT NULL DEFAULT 'Y',
  `cmd_line` text NOT NULL,
  `notes` text NOT NULL,
  `server` varchar(36) NOT NULL,
  `rcon_password` varchar(64) NOT NULL,
  `subdomain` varchar(255) NOT NULL,
  `config_file` varchar(255) NOT NULL,
  `log_file` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `map` varchar(255) NOT NULL,
  `executable` varchar(255) NOT NULL,
  `working_dir` varchar(255) NOT NULL,
  `setup_dir` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `servers_addons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_added` datetime NOT NULL,
  `is_installed` enum('Y','N') NOT NULL DEFAULT 'Y',
  `srvid` int(11) NOT NULL,
  `addonid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `servers_cfg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `srvid` int(10) unsigned NOT NULL,
  `itemid` int(10) unsigned NOT NULL,
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `client_edit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `item_order` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `item_value` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `servers_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `srvid` int(11) NOT NULL,
  `opt1_name` varchar(255) NOT NULL,
  `opt1_edit` enum('Y','N') NOT NULL DEFAULT 'N',
  `opt1_value` varchar(255) NOT NULL,
  `opt2_name` varchar(255) NOT NULL,
  `opt2_edit` enum('Y','N') NOT NULL DEFAULT 'N',
  `opt2_value` varchar(255) NOT NULL,
  `opt3_name` varchar(255) NOT NULL,
  `opt3_edit` enum('Y','N') NOT NULL DEFAULT 'N',
  `opt3_value` varchar(255) NOT NULL,
  `opt4_name` varchar(255) NOT NULL,
  `opt4_edit` enum('Y','N') NOT NULL DEFAULT 'N',
  `opt4_value` varchar(255) NOT NULL,
  `opt5_name` varchar(255) NOT NULL,
  `opt5_edit` enum('Y','N') NOT NULL DEFAULT 'N',
  `opt5_value` varchar(255) NOT NULL,
  `opt6_name` varchar(255) NOT NULL,
  `opt6_edit` enum('Y','N') NOT NULL DEFAULT 'N',
  `opt6_value` varchar(255) NOT NULL,
  `opt7_name` varchar(255) NOT NULL,
  `opt7_edit` enum('Y','N') NOT NULL DEFAULT 'N',
  `opt7_value` varchar(255) NOT NULL,
  `opt8_name` varchar(255) NOT NULL,
  `opt8_edit` enum('Y','N') NOT NULL DEFAULT 'N',
  `opt8_value` varchar(255) NOT NULL,
  `opt9_name` varchar(255) NOT NULL,
  `opt9_edit` enum('Y','N') NOT NULL DEFAULT 'N',
  `opt9_value` varchar(255) NOT NULL,
  `opt10_name` varchar(255) NOT NULL,
  `opt10_edit` enum('Y','N') NOT NULL DEFAULT 'N',
  `opt10_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `networkid` int(11) unsigned NOT NULL,
  `date_created` datetime NOT NULL,
  `type` enum('game','voip','other') NOT NULL DEFAULT 'other',
  `available` enum('Y','N') NOT NULL DEFAULT 'Y',
  `is_default` enum('Y','N') NOT NULL DEFAULT 'N',
  `automagical` enum('Y','N') NOT NULL DEFAULT 'N',
  `status` enum('none','complete','running','failed') NOT NULL DEFAULT 'none',
  `installation_status` enum('none','complete','running','failed') NOT NULL DEFAULT 'none',
  `server` varchar(36) NOT NULL,
  `template_hash` varchar(128) NOT NULL,
  `description` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `threadid` int(11) unsigned NOT NULL DEFAULT '0',
  `userid` int(11) unsigned NOT NULL,
  `response_userid` int(11) unsigned NOT NULL,
  `priority` enum('low','medium','high') NOT NULL DEFAULT 'medium',
  `category` enum('support','billing','sales') NOT NULL DEFAULT 'support',
  `date_added` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  `status` enum('open','closed') NOT NULL DEFAULT 'open',
  `opened_by` enum('user','admin') NOT NULL DEFAULT 'user',
  `response_type` enum('user','admin') NOT NULL DEFAULT 'admin',
  `read_admin` enum('Y','N') NOT NULL DEFAULT 'N',
  `read_user` enum('Y','N') NOT NULL DEFAULT 'N',
  `subject` varchar(255) NOT NULL,
  `ticket_text` text NOT NULL,
  `internal_notes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;








INSERT INTO `notify_types` (`id`, `notify_type`) VALUES
(1, 'New Client'),
(2, 'New Server'),
(3, 'New Template'),
(4, 'Template Finished'),
(5, 'New Client Ticket'),
(6, 'Client Ticket Updated');

INSERT INTO `cfg_rcon` (`id`, `srvid`, `cmd`, `allow_client`) VALUES
(1, 1, 'kickid %playerid%', 'Y'),
(2, 1, 'banid %playerid%', 'Y');

INSERT INTO `cfg_items` (`id`, `srvid`, `deleted`, `required`, `client_edit`, `usr_def`, `simpleid`, `cmd_line`, `cfg_file`, `name`, `default_value`, `description`) VALUES
(1, 1, 0, 0, 0, 0, 0, 1, 1, '+fps_max', '500', 'Max Server-Side FPS'),
(2, 1, 0, 0, 0, 0, 0, 1, 1, '-tickrate', '66', 'Tickrate'),
(3, 1, 0, 1, 0, 0, 3, 1, 0, '+maxplayers', '64', 'Maximum connected players'),
(4, 1, 0, 1, 1, 0, 4, 1, 0, '+map', 'de_dust2', 'Game Map'),
(5, 1, 0, 1, 0, 0, 0, 1, 0, '-game', 'cstrike', 'Specify the Steam game type'),
(76, 5, 0, 0, 0, 1, 0, 1, 0, '+set', '+map_rotate', ''),
(75, 5, 0, 0, 0, 1, 0, 1, 0, '+exec', 'server.cfg', ''),
(37, 1, 0, 0, 0, 1, 1, 1, 0, '+ip', '', ''),
(74, 5, 0, 0, 0, 1, 0, 1, 0, '+set', 'sv_pure 1', ''),
(72, 6, 0, 0, 0, 1, 1, 1, 0, '+set net_ip', '', ''),
(73, 6, 0, 0, 0, 1, 3, 1, 0, '+set ui_maxclients ', '12', ''),
(70, 6, 0, 0, 1, 1, 0, 1, 0, '+set', '+map_rotate', ''),
(69, 6, 0, 0, 0, 1, 0, 1, 0, '+exec', 'server.cfg', ''),
(68, 6, 0, 0, 0, 1, 0, 1, 0, '+set', 'sv_pure 1', ''),
(67, 6, 0, 0, 0, 1, 0, 1, 0, '+set', 'punkbuster 1', ''),
(55, 13, 0, 0, 0, 1, 2, 1, 0, '+port', '27015', ''),
(54, 13, 0, 0, 0, 1, 1, 1, 0, '+ip', '', ''),
(66, 2, 0, 0, 0, 1, 0, 1, 0, '-game', 'czero', 'Specify the Steam game type'),
(65, 2, 0, 0, 0, 1, 0, 1, 0, '-tickrate', '66', ''),
(64, 2, 0, 0, 1, 1, 4, 1, 0, '+map', 'de_dust2_cz', ''),
(63, 2, 0, 0, 0, 1, 3, 1, 0, '+maxplayers', '12', ''),
(48, 1, 0, 0, 0, 1, 2, 1, 0, '+port', '27015', ''),
(62, 2, 0, 0, 0, 1, 2, 1, 0, '+port', '27015', ''),
(56, 13, 0, 0, 0, 1, 3, 1, 0, '+maxplayers', '64', ''),
(57, 13, 0, 0, 0, 1, 4, 1, 0, '+map', 'de_dust2', ''),
(58, 13, 0, 0, 0, 1, 0, 1, 0, '-game', 'cstrike', 'Specify the Steam game type'),
(61, 2, 0, 0, 0, 1, 1, 1, 0, '+ip', '', ''),
(60, 13, 0, 0, 0, 1, 0, 1, 0, '-tickrate', '66', ''),
(77, 5, 0, 0, 0, 1, 3, 1, 0, '+set ui_maxclients', '12', ''),
(78, 5, 0, 0, 0, 1, 1, 1, 0, '+set net_ip', '', ''),
(79, 5, 0, 0, 0, 1, 0, 1, 0, '+set', 'punkbuster 1', ''),
(80, 7, 0, 0, 0, 1, 0, 1, 0, '+set', 'punkbuster 1', ''),
(81, 7, 0, 0, 0, 1, 0, 1, 0, '+set', 'sv_pure 1', ''),
(82, 7, 0, 0, 0, 1, 0, 1, 0, '+exec', 'server.cfg', ''),
(83, 7, 0, 0, 0, 1, 0, 1, 0, '+set', '+map_rotate', ''),
(84, 7, 0, 0, 0, 1, 3, 1, 0, '+set ui_maxclients', '12', ''),
(85, 7, 0, 0, 0, 1, 1, 1, 0, '+set net_ip', '', ''),
(86, 16, 0, 0, 0, 1, 0, 1, 0, '-game', 'dod', 'Specify the Steam game type'),
(87, 16, 0, 0, 0, 1, 1, 1, 0, '+ip', '', ''),
(88, 16, 0, 0, 0, 1, 2, 1, 0, '+port', '27015', ''),
(89, 16, 0, 0, 0, 1, 3, 1, 0, '+maxplayers', '12', ''),
(90, 9, 0, 0, 0, 1, 0, 1, 0, '-game', 'dods', 'Specify the Steam game type'),
(91, 9, 0, 0, 0, 1, 1, 1, 0, '+ip', '', ''),
(92, 9, 0, 0, 0, 1, 2, 1, 0, '+port', '27015', ''),
(93, 9, 0, 0, 0, 1, 3, 1, 0, '+maxplayers', '12', ''),
(94, 9, 0, 0, 0, 1, 4, 1, 0, '+map', '', ''),
(95, 16, 0, 0, 0, 1, 4, 1, 0, '+map', '', ''),
(96, 6, 0, 0, 0, 1, 2, 1, 0, '+set net_port', '28960', ''),
(97, 5, 0, 0, 0, 1, 2, 1, 0, '+set net_port', '28960', ''),
(98, 7, 0, 0, 0, 1, 2, 1, 0, '+set net_port', '28960', ''),
(99, 15, 0, 0, 0, 1, 1, 1, 0, '+ip', '', ''),
(100, 15, 0, 0, 0, 1, 2, 1, 0, '+port', '27015', ''),
(101, 15, 0, 0, 0, 1, 3, 1, 0, '+maxplayers', '32', ''),
(102, 15, 0, 0, 0, 1, 4, 1, 0, '+map', 'cp_dustbowl', ''),
(103, 15, 0, 0, 0, 1, 0, 1, 0, '-game', 'tf', 'Specify the Steam game type'),
(104, 3, 0, 0, 0, 1, 0, 1, 0, '-d', '', ''),
(105, 14, 0, 0, 0, 1, 0, 1, 0, '-ini', 'murmur.ini', ''),
(106, 20, 0, 0, 0, 1, 0, 1, 0, 'voice_ip', '', ''),
(107, 20, 0, 0, 0, 1, 2, 1, 0, 'default_voice_port', '9987', ''),
(108, 20, 0, 0, 0, 1, 4, 1, 0, 'map', 'default', ''),
(109, 20, 0, 0, 0, 1, 3, 1, 0, 'virtualserver_maxclients', '12', '');


INSERT INTO `cfg_configs` (`id`, `srvid`, `name`, `dir`, `description`, `rmcmd1`, `rmcmd2`, `rmcmd3`, `rmcmd4`, `rmcmd5`, `rmcmd6`, `rmcmd7`, `rmcmd8`, `rmcmd9`, `rmcmd10`) VALUES
(12, 1, 'mapcycle.txt', 'cstrike/', 'Map Cycle file', '', '', '', '', '', '', '', '', '', ''),
(2, 1, 'server.cfg', 'cstrike/cfg/', 'Main server config', 'ip', 'port', 'maxplayers', 'fps_max', 'tickrate', '', '', '', '', ''),
(5, 0, '', '', 'wefewf', '', '', '', '', '', '', '', '', '', ''),
(11, 1, 'maplist.txt', 'cstrike/', 'Map List file', '', '', '', '', '', '', '', '', '', ''),
(4, 3, 'server.cfg', 'cstrike/cfg/', '', 'ip', 'port', 'maxplayers', 'fps_max', 'tickrate', '', '', '', '', ''),
(13, 1, 'motd.txt', 'cstrike/', 'Message of the Day HTML file', '<script>', '', '', '', '', '', '', '', '', '');


INSERT INTO `cfg` (`id`, `max_slots`, `port`, `date_added`, `last_updated`, `type`, `available`, `based_on`, `is_steam`, `is_punkbuster`, `notes`, `description`, `cmd_line`, `automagical`, `size`, `short_name`, `query_name`, `steam_name`, `long_name`, `mod_name`, `nickname`, `style`, `log_file`, `reserved_ports`, `tcp_ports`, `udp_ports`, `executable`, `map`, `setup_cmd`, `working_dir`, `setup_dir`, `config_file`, `pid_file`, `cfg_default`, `cfg_ip`, `cfg_port`, `cfg_max_slots`, `cfg_map`, `cfg_password`, `cfg_internet`) VALUES
(1, 64, 27015, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'Y', 'N', '', 'Remake of Counter-Strike: 1.6 with the Source engine', './%executable% -game %mod_name% +map %map% +maxplayers %max_slots% -ip %ip% +port %port%', '', '3054600', 'cs_s', 'cssource', 'Counter-Strike Source', 'Counter-Strike: Source', 'cstrike', 'CS Source', 'FPS', '%working_dir%/', '27000,27020,27019', '1200,27000_27039', '1200,27000_27039', 'srcds_run', 'de_dust2', '', 'css', '', 'server.cfg', '', '// server name\r\n\r\nhostname "%hostname%" \r\n\r\n\r\n\r\n// rcon passsword\r\n\r\nrcon_password "%rconpassword%" \r\n\r\n\r\n\r\n// Server password\r\n\r\nsv_password "%privatepassword%" \r\n\r\n\r\n\r\n// server cvars\r\n\r\nmp_friendlyfire 0 \r\n\r\nmp_footsteps 1 \r\n\r\nmp_autoteambalance 1 \r\n\r\nmp_autokick 0 \r\n\r\nmp_flashlight 0 \r\n\r\nmp_tkpunish 1 \r\n\r\nmp_forcecamera 0 \r\n\r\nsv_alltalk 0 \r\n\r\nsv_pausable 0 \r\n\r\nsv_cheats 0 \r\n\r\nsv_consistency 1 \r\n\r\nsv_allowupload 1 \r\n\r\nsv_allowdownload 1 \r\n\r\nsv_maxspeed 320 \r\n\r\nmp_limitteams 2 \r\n\r\nmp_hostagepenalty 5 \r\n\r\nsv_voiceenable 1 \r\n\r\nmp_allowspectators 1 \r\n\r\nmp_chattime 10 \r\n\r\nsv_timeout 65 \r\n\r\n\r\n\r\n// round specific cvars\r\n\r\nmp_freezetime 6 \r\n\r\nmp_roundtime 5 \r\n\r\nmp_startmoney 800 \r\n\r\nmp_c4timer 45 \r\n\r\nmp_fraglimit 0 \r\n\r\nmp_maxrounds 0 \r\n\r\nmp_winlimit 0 \r\n\r\nmp_playerid 0 \r\n\r\nmp_spawnprotectiontime 5 \r\n\r\n\r\n\r\n// bandwidth rates/settings\r\n\r\nsv_minrate 0 \r\n\r\nsv_maxrate 0 \r\n\r\ndecalfrequency 10 \r\n\r\nsv_maxupdaterate 60 \r\n\r\nsv_minupdaterate 10 \r\n\r\n\r\n\r\n// server logging\r\n\r\nlog off \r\n\r\nsv_logbans 0 \r\n\r\nsv_logecho 1 \r\n\r\nsv_logfile 1 \r\n\r\nsv_log_onefile 0 \r\n\r\n\r\n\r\n// operation\r\n\r\nsv_lan 0 \r\n\r\nsv_region 255 \r\n\r\n\r\n\r\n// execute ban files\r\n\r\nexec banned_user.cfg \r\n\r\nexec banned_ip.cfg ', '-ip', '-port', '+maxplayers', '+map', '+sv_password', '+sv_lan'),
(2, 32, 27015, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'Y', 'N', '', 'Remake of Counter-Strike: 1.6 with bots and new maps', './%executable% -game %mod_name% +map %map% +maxplayers %max_slots% -ip %ip% +port %port%', '', '1104044', 'cs_cz', 'cs', 'czero', 'Counter-Strike: Condition Zero', 'czero', 'CZ', 'FPS', '%working_dir%/', '27000,27020,27019', '1200,27000_27039', '1200,27000_27039', 'hlds_run', 'de_dust2_cz', '', 'czero', 'czero', 'server.cfg', '', '// server name\r\n\r\nhostname "%hostname%" \r\n\r\n\r\n\r\n// rcon passsword\r\n\r\nrcon_password "%rconpassword%" \r\n\r\n\r\n\r\n// Server password\r\n\r\nsv_password "%privatepassword%" \r\n\r\n\r\n\r\n// server cvars\r\n\r\nmp_friendlyfire 0 \r\n\r\nmp_footsteps 1 \r\n\r\nmp_autoteambalance 1 \r\n\r\nmp_autokick 0 \r\n\r\nmp_flashlight 0 \r\n\r\nmp_tkpunish 1 \r\n\r\nmp_forcecamera 0 \r\n\r\nsv_alltalk 0 \r\n\r\nsv_pausable 0 \r\n\r\nsv_cheats 0 \r\n\r\nsv_consistency 1 \r\n\r\nsv_allowupload 1 \r\n\r\nsv_allowdownload 1 \r\n\r\nsv_maxspeed 320 \r\n\r\nmp_limitteams 2 \r\n\r\nmp_hostagepenalty 5 \r\n\r\nsv_voiceenable 1 \r\n\r\nmp_allowspectators 1 \r\n\r\nmp_chattime 10 \r\n\r\nsv_timeout 65 \r\n\r\n\r\n\r\n// round specific cvars\r\n\r\nmp_freezetime 6 \r\n\r\nmp_roundtime 5 \r\n\r\nmp_startmoney 800 \r\n\r\nmp_c4timer 45 \r\n\r\nmp_fraglimit 0 \r\n\r\nmp_maxrounds 0 \r\n\r\nmp_winlimit 0 \r\n\r\nmp_playerid 0 \r\n\r\nmp_spawnprotectiontime 5 \r\n\r\n\r\n\r\n// bandwidth rates/settings\r\n\r\nsv_minrate 0 \r\n\r\nsv_maxrate 0 \r\n\r\ndecalfrequency 10 \r\n\r\nsv_maxupdaterate 60 \r\n\r\nsv_minupdaterate 10 \r\n\r\n\r\n\r\n// server logging\r\n\r\nlog off \r\n\r\nsv_logbans 0 \r\n\r\nsv_logecho 1 \r\n\r\nsv_logfile 1 \r\n\r\nsv_log_onefile 0 \r\n\r\n\r\n\r\n// operation\r\n\r\nsv_lan 0 \r\n\r\nsv_region 255 \r\n\r\n\r\n\r\n// execute ban files\r\n\r\nexec banned_user.cfg \r\n\r\nexec banned_ip.cfg ', '+ip', '+port', '+maxplayers', '+map', '', ''),
(3, 8, 3784, '2010-05-20 19:56:51', '0000-00-00 00:00:00', 'voip', 'Y', 'cfg', 'N', 'N', '', 'Voice Communication Server', './%executable% -d', '', '', 'vent', 'ventrilo', '', 'Ventrilo', '', 'Vent', '', 'ventrilo_srv.log', '3784', '3784', '3784', 'ventrilo_srv', '', '', '', '', 'ventrilo_srv.ini', 'ventrilo_srv.pid', '', '', '', '', '', '', ''),
(4, 64, 16567, '2010-05-25 17:09:11', '0000-00-00 00:00:00', 'game', 'Y', 'cfg', 'N', 'Y', '', '', './%executable% ', '', '', 'bf_2', 'bf2', '', 'Battlefield 2', 'bf2', 'BF2', 'FPS', '', '16567', '16567', '16567', 'start.sh', 'strike_at_karkand', '', '', '', 'serversettings.con', '', '', 'sv.serverIP', 'sv.serverPort', 'sv.maxPlayers', '', 'sv.password', 'sv.internet'),
(5, 64, 28960, '2010-06-03 15:18:59', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'N', 'N', '', '', './%executable% +set net_ip %ip% +set net_port %port% +set dedicated %opt2% +set ui_maxclients "%max_players%" +set punkbuster %opt3% +set sv_pure %opt4% +exec %opt1%', '', '', 'cod2', 'cod2', '', 'Call of Duty 2', '', '', 'FPS', 'games_mp.log', '', '28960', '20500,20510,28960', 'cod2_lnxded', 'mp_strike', '', '', '', '', '', '', '', '', '', '', '', ''),
(6, 24, 28960, '2010-06-03 15:19:08', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'N', 'N', '', '', './%executable% +set net_ip %ip% +set net_port %port% +set dedicated %opt2% +set ui_maxclients "%max_players%" +set punkbuster %opt3% +set sv_pure %opt4% +exec %opt1%', '', '', 'cod4', 'cod4', '', 'Call of Duty 4', '', '', 'FPS', 'heya', '20500,20510,28960', '28960', '20500,20510,28960', 'cod4_lnxded', 'mp_strike', '', 'cod4', 'pbsetup', '', '', '', '', '', '', '', '', ''),
(7, 32, 28960, '2010-06-03 15:19:15', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'N', 'N', '', '', './%executable% +set net_ip %ip% +set net_port %port% +set dedicated %opt2% +set ui_maxclients "%max_players%" +set punkbuster %opt3% +set sv_pure %opt4% +exec %opt1% %opt5%', '', '', 'cod_waw', 'cod3', '', 'Call of Duty: World at War', '', '', 'FPS', '', '', '28960', '20500,20510,28960', 'codwaw_lnxded', 'mp_castle', '', '', '', '', '', '', '', '', '', '', '', ''),
(9, 32, 27015, '2010-06-03 15:19:30', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'Y', 'N', '', '', './%executable% -game dod -ip %ip% -port %port% +maxplayers %max_players% +map %default_map% +exec %opt1% -tickrate %opt4% +fps_max %opt5% +tv_enable %opt6% +tv_port %opt7%', '', '', 'dod_s', 'dodsource', 'dods', 'Day of Defeat: Source', 'dods', '', 'FPS', 'orangebox/dod/logs', '27020,27040,27041', '27015,27020,27040,27041', '1200,27015,26900', 'srcds_run', 'dod_anzio', '', 'orangebox', '', '', '', '', '', '', '', '', '', ''),
(10, 8, 27015, '2010-06-03 15:19:37', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'Y', 'N', '', '', './%executable% -game left4dead -ip %ip% -port %port% +map %default_map% +exec %opt1%', '', '', 'l4d', 'left4dead', 'l4d_full', 'Left 4 Dead', 'left4dead', '', 'FPS', 'l4d/left4dead/logs', '27020,27039', '27015,27020,27040,27041', '1200,27015,26900', 'srcds_run', 'l4d_airport01_greenhouse', '', 'l4d', '', '', '', '', '', '', '', '', '', ''),
(11, 8, 27015, '2010-06-03 15:19:43', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'Y', 'N', '', '', './%executable% -game left4dead2 -ip %ip% -port %port% +map %default_map% +exec %opt1%', '', '', 'l4d_2', 'left4dead2', 'left4dead2', 'Left 4 Dead 2', 'left4dead2', '', 'FPS', '%working_dir%/logs', '27015,27020,27040,27041,1200', '27015,27020,27040,27041', '1200,27015,26900', 'srcds_run', 'c2m1_highway', '', 'l4d/left4dead2', '', '', '', '', '', '', '', '', '', ''),
(12, 16, 27960, '2010-06-03 15:19:48', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'N', 'N', '', '', './%executable% +set net_ip %ip% +set net_port %port% +set dedicated %opt3% +set sv_punkbuster %opt2% +map %default_map% +exec %opt1% +set sv_maxclients %max_players%', '', '', 'ws_et', 'et', '', 'Wolfenstein: Enemy Territory', '', '', 'FPS', '%working_dir%/etconsole.log', '27950,27952,27960,27965', '27950,27952,27960,27965', '27950,27952,27960,27965', 'etded', 'oasis', '', 'etmain', '', '', '', '', 'net_ip', 'net_port', 'sv_maxclients', 'map', '', 'dedicated'),
(13, 32, 27015, '2010-06-29 19:02:30', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'Y', 'N', '', '', './%executable% -game %mod_name% +ip %ip% +port %port% +map %map% +maxplayers %max_slots%', '', '710400', 'cs_16', 'cs', 'cstrike', 'Counter-Strike: 1.6', 'cstrike', 'CS 1.6', 'FPS', '', '27015', '27015', '27015', 'hlds_run', 'de_dust2', '', '', '', 'server.cfg', '', '', '+ip', '+port', '+maxplayers', '+map', '+sv_password', '+sv_lan'),
(14, 100, 64738, '2010-09-15 19:18:00', '0000-00-00 00:00:00', 'voip', 'Y', 'cfg', 'N', 'N', '', 'VOiP Server for the Mumble client', './%executable%', '', '', 'mrm', '', '', 'Murmur', '', '', '', 'murmur.log', '64738', '64738', '64738', 'murmur.x86', '', '', '', '', 'murmur.ini', 'murmur.pid', '', 'host=', 'port=', 'users=', '', 'serverpassword=', ''),
(15, 32, 27015, '2010-09-22 15:31:45', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'Y', 'N', '', '', './%executable% -game %mod_name% +map %map% +maxplayers %max_slots% -ip %ip% +port %port%', '', '', 'tf2', 'tf2', 'tf', 'Team Fortress 2', 'tf', 'TF2', 'FPS', '', '27015', '27015,1200', '27015', 'srcds_run', 'cp_dustbowl', '', 'orangebox', '', 'server.cfg', '', '', 'ip', 'port', 'maxplayers', 'map', 'sv_password', 'sv_lan'),
(16, 32, 27015, '2010-09-23 21:52:16', '0000-00-00 00:00:00', 'game', 'Y', 'cmd', 'N', 'N', '', 'The original Day of Defeat', './%executable% -game %mod_name% +ip %ip% +port %port% +maxplayers %max_slots% +map %default_map%', '', '', 'dod', 'dod', 'dod', 'Day of Defeat', 'dod', 'DOD', 'FPS', '', '27015', '27015', '27015', 'hlds_run', 'dod_avalanche', '', '', '', 'server.cfg', '', '', '+ip', '+port', '+maxplayers', '+map', '+sv_password', '+sv_lan'),
(19, 12, 8767, '2011-10-19 08:37:07', '0000-00-00 00:00:00', 'voip', 'Y', 'cmd', 'N', 'N', '', '2nd edition of TeamSpeak', './%executable% start', '', '', 'ts2', 'ts2', '', 'Teamspeak 2', '', 'TS2', '', '', '8767,14534,51234', '14534,51234', '8767', 'teamspeak2-server_startscript', '', '', '', '', 'server.ini', '', '', '', '', '', '', '', ''),
(20, 12, 9987, '2011-10-19 08:47:04', '0000-00-00 00:00:00', 'voip', 'Y', 'cmd', 'N', 'N', '', '3rd edition of TeamSpeak', './%executable%', '', '', 'ts3', 'ts3', '', 'Teamspeak 3', '', 'TS3', '', '', '9987,14534,51234', '14534,51234', '9987', 'ts3server_minimal_runscript.sh', '', '', '', '', 'server.ini', '', '', '', '', '', '', '', '');



INSERT INTO `activity_types` (`id`, `name`, `description`) VALUES
(1, 'restart_server', 'Restart a Server'),
(2, 'stop_server', 'Stop a Server'),
(3, 'create_server', 'Create a New Server'),
(4, 'delete_server', 'Delete a Server'),
(5, 'suspend_server', 'Suspend a Server'),
(6, 'unsuspend_server', 'Unsuspend a Server'),
(7, 'reinstall_server', 'Reinstall all Server Files'),
(8, 'install_addon', 'Install a new addon on a server'),
(9, 'update_server_details', 'Update Server Settings'),
(10, 'update_startup_smp', 'Update Startup Settings (Simple)'),
(11, 'update_startup_adv', 'Update Startup Settings (Advanced)');
