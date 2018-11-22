CREATE TABLE `CampsiteUse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SN` text NOT NULL,
  `Number` int(11) NOT NULL,
  `Who` text NOT NULL,
  `Priority` int(11) NOT NULL,
  `Year` int(11) NOT NULL,
  `Notes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
