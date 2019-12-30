CREATE TABLE `TradePitch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Year` int(11) NOT NULL,
  `Loc` int(11) NOT NULL,
  `X` double NOT NULL,
  `Y` double NOT NULL,
  `Angle` double NOT NULL,
  `Posn` int(11) NOT NULL,
  `Xsize` double NOT NULL,
  `Ysize` double NOT NULL,
  `Type` tinyint(4) NOT NULL,
  `SN` text NOT NULL,
  `Colour` text NOT NULL,
  `Font` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
