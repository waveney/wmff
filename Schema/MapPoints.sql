CREATE TABLE `MapPoints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SN` text NOT NULL,
  `Type` int(11) NOT NULL,
  `Lat` text NOT NULL,
  `Lng` text NOT NULL,
  `MapImp` text NOT NULL,
  `Notes` text NOT NULL,
  `InUse` tinyint(4) NOT NULL,
  `Link` text NOT NULL,
  `AddText` int(11) NOT NULL,
  `Directions` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
