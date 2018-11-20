CREATE TABLE `TaxiCompanies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Authority` tinyint(4) NOT NULL,
  `SN` text,
  `Phone` text NOT NULL,
  `Website` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
