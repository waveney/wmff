CREATE TABLE `PerformerTypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SN` text NOT NULL,
  `ListState` tinyint(4) NOT NULL,
  `Year` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
