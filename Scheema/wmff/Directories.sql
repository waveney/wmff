CREATE TABLE `Directories` (
  `DirId` int(11) NOT NULL AUTO_INCREMENT,
  `SN` text NOT NULL,
  `Created` int(11) NOT NULL,
  `Who` int(11) NOT NULL,
  `Parent` int(11) NOT NULL,
  `State` tinyint(4) NOT NULL DEFAULT '0',
  `AccessLevel` int(11) NOT NULL,
  `AccessSections` text NOT NULL,
  PRIMARY KEY (`DirId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
