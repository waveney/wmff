CREATE TABLE `OtherLinks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `LinkType` int(11) NOT NULL,
  `SN` text,
  `URL` text NOT NULL,
  `Image` text NOT NULL,
  `Year` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
