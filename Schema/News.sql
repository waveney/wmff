CREATE TABLE `News` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `display` tinyint(5) NOT NULL,
  `SN` text COLLATE latin1_general_ci,
  `Type` tinyint(4) NOT NULL,
  `content` text CHARACTER SET latin1 NOT NULL,
  `image` text CHARACTER SET latin1 NOT NULL,
  `caption` text CHARACTER SET latin1 NOT NULL,
  `author` text CHARACTER SET latin1 NOT NULL,
  `created` int(11) NOT NULL,
  `Link` text COLLATE latin1_general_ci NOT NULL,
  `LinkText` text COLLATE latin1_general_ci NOT NULL,
  `Participant` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
