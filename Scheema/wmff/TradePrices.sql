CREATE TABLE `TradePrices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SN` text,
  `ListOrder` int(11) NOT NULL,
  `Colour` text NOT NULL,
  `BasePrice` text NOT NULL,
  `PerDay` tinyint(4) NOT NULL,
  `Deposit` int(11) NOT NULL,
  `Addition` tinyint(4) NOT NULL,
  `NeedCharityNum` tinyint(4) NOT NULL,
  `NeedPublicHealth` tinyint(4) NOT NULL,
  `NeedInsurance` tinyint(4) NOT NULL,
  `NeedRiskAssess` tinyint(4) NOT NULL,
  `ArtisanMsgs` tinyint(4) NOT NULL,
  `TOpen` tinyint(4) NOT NULL,
  `Description` text NOT NULL,
  `SalesCode` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
