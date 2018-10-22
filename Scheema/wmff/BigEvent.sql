CREATE TABLE `BigEvent` (
  `Event` int(11) NOT NULL,
  `Type` text NOT NULL,
  `Identifier` int(11) NOT NULL,
  `BigEid` int(11) NOT NULL AUTO_INCREMENT,
  `EventOrder` int(11) NOT NULL,
  `Notes` text NOT NULL,
  PRIMARY KEY (`BigEid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
