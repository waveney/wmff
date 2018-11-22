CREATE TABLE `Overlaps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Sid1` int(11) NOT NULL,
  `Sid2` int(11) NOT NULL,
  `Cat1` tinyint(4) NOT NULL,
  `Cat2` tinyint(4) NOT NULL,
  `OType` tinyint(4) NOT NULL,
  `Major` tinyint(4) NOT NULL,
  `Days` tinyint(4) NOT NULL,
  `Active` tinyint(4) NOT NULL,
  `Notes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
