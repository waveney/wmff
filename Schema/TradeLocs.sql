CREATE TABLE `TradeLocs` (
  `TLocId` int(11) NOT NULL AUTO_INCREMENT,
  `SN` text,
  `HasPower` tinyint(4) NOT NULL,
  `Pitches` int(11) NOT NULL,
  `Notes` text NOT NULL,
  `InUse` tinyint(4) NOT NULL,
  `Days` tinyint(4) NOT NULL,
  `ArtisanMsgs` tinyint(4) NOT NULL,
  `prefix` tinyint(4) NOT NULL,
  `InvoiceCode` int(11) NOT NULL,
  PRIMARY KEY (`TLocId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
