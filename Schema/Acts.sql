CREATE TABLE `Acts` (
  `ActId` int(11) NOT NULL AUTO_INCREMENT,
  `SN` text,
  `ShortName` text NOT NULL,
  `Notes` text NOT NULL,
  `Contact` text NOT NULL,
  `Type` text NOT NULL,
  `Email` text NOT NULL,
  `Website` text NOT NULL,
  `Website2` text NOT NULL,
  `Facebook` text NOT NULL,
  `Twitter` text NOT NULL,
  `Instagram` text NOT NULL,
  `Blurb` text NOT NULL,
  `Phone` text NOT NULL,
  `Mobile` text NOT NULL,
  PRIMARY KEY (`ActId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
