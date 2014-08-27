CREATE TABLE IF NOT EXISTS `#__joomlabacklinkchecker` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `link_checkurl` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `link_url` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `link_titletext` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `link_linktext` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `link_keywords` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `back_link` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `back_linkurl` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `link_owner` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `linkowner_detail` text COLLATE utf8_unicode_ci NOT NULL,
  `link_email` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `link_date` datetime NOT NULL,
  `check_status` int(11) NOT NULL DEFAULT '0',
  `check_date` datetime NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__joomlabacklinkchecker_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;