CREATE TABLE `Documents` (
  `DocId` int(11) NOT NULL AUTO_INCREMENT,
  `SN` text NOT NULL,
  `Who` int(11) NOT NULL,
  `Created` int(11) NOT NULL,
  `Dir` int(11) NOT NULL,
  `Filename` text NOT NULL,
  `filesize` int(11) NOT NULL,
  `State` tinyint(11) NOT NULL DEFAULT '0',
  `Access` int(11) NOT NULL DEFAULT '666',
  PRIMARY KEY (`DocId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
