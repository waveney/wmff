CREATE TABLE `Bugs` (
  `BugId` int(11) NOT NULL AUTO_INCREMENT,
  `Who` int(11) NOT NULL,
  `Created` int(11) NOT NULL,
  `SN` text NOT NULL,
  `Description` text NOT NULL,
  `State` int(11) NOT NULL,
  `Response` text NOT NULL,
  `Severity` int(11) NOT NULL,
  `LastUpdate` int(11) NOT NULL,
  PRIMARY KEY (`BugId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
