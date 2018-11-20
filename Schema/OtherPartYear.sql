CREATE TABLE `OtherPartYear` (
  `OpyId` int(11) NOT NULL AUTO_INCREMENT,
  `Other` int(11) NOT NULL,
  `Year` int(11) NOT NULL,
  `Payment` int(11) NOT NULL,
  `YNotes` text NOT NULL,
  `Fri` tinyint(4) NOT NULL,
  `Sat` tinyint(4) NOT NULL,
  `Sun` tinyint(4) NOT NULL,
  `Performers` int(11) NOT NULL,
  `CarPark` int(11) NOT NULL,
  PRIMARY KEY (`OpyId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
