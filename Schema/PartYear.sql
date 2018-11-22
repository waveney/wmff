CREATE TABLE `PartYear` (
  `SideId` int(11) NOT NULL,
  `Year` int(11) NOT NULL,
  `Invite` int(11) NOT NULL,
  `Coming` int(11) NOT NULL,
  `Fri` tinyint(4) NOT NULL,
  `Sat` tinyint(1) NOT NULL,
  `Sun` tinyint(1) NOT NULL,
  `FriDance` int(11) NOT NULL DEFAULT '0',
  `SatDance` int(11) NOT NULL DEFAULT '3',
  `SunDance` int(11) NOT NULL DEFAULT '4',
  `Procession` tinyint(1) NOT NULL,
  `ProcessionOrder` int(11) NOT NULL,
  `Arrive` text NOT NULL,
  `Depart` text NOT NULL,
  `Invited` text NOT NULL,
  `Reminder` tinyint(1) NOT NULL,
  `Insurance` tinyint(1) NOT NULL,
  `Performers` int(11) NOT NULL,
  `CarPark` int(11) NOT NULL,
  `YNotes` text NOT NULL,
  `PrivNotes` text NOT NULL,
  `syId` int(11) NOT NULL AUTO_INCREMENT,
  `Share` tinyint(4) NOT NULL,
  PRIMARY KEY (`syId`),
  UNIQUE KEY `syId` (`syId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;