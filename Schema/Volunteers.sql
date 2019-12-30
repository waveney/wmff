CREATE TABLE `Volunteers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SN` text,
  `Email` text NOT NULL,
  `Phone` text NOT NULL,
  `Address` text NOT NULL,
  `PostCode` text NOT NULL,
  `Over18` tinyint(4) NOT NULL,
  `Birthday` text NOT NULL,
  `ContactName` text NOT NULL,
  `ContactPhone` text NOT NULL,
  `DBS` text NOT NULL,
  `Relation` smallint(6) NOT NULL,
  `AccessKey` text NOT NULL,
  `Status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
