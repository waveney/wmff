CREATE TABLE `Articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SN` text NOT NULL,
  `Type` smallint(6) NOT NULL,
  `Link` text NOT NULL,
  `Text` text NOT NULL,
  `Image` text NOT NULL,
  `ImageHeight` int(11) NOT NULL,
  `ImageWidth` int(11) NOT NULL,
  `Importance` tinyint(4) NOT NULL,
  `StartDate` int(11) NOT NULL,
  `StopDate` int(11) NOT NULL,
  `Format` tinyint(4) NOT NULL,
  `UsedOn` text NOT NULL,
  `HideTitle` tinyint(4) NOT NULL,
  `RelOrder` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
