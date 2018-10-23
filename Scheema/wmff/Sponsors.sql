CREATE TABLE `Sponsors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SN` text,
  `Website` text NOT NULL,
  `Description` text NOT NULL,
  `Year` int(11) NOT NULL,
  `Importance` int(11) NOT NULL,
  `Image` text NOT NULL,
  `ImageHeight` int(11) NOT NULL,
  `ImageWidth` int(11) NOT NULL,
  `IandT` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
