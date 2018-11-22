CREATE TABLE `LogFile` (
  `LogId` int(11) NOT NULL AUTO_INCREMENT,
  `Who` int(11) NOT NULL,
  `changed` text NOT NULL,
  `What` text NOT NULL,
  PRIMARY KEY (`LogId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
