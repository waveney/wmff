CREATE TABLE `ContactCats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SN` text NOT NULL,
  `OpenState` tinyint(4) NOT NULL,
  `Description` text NOT NULL,
  `Email` text NOT NULL,
  `RelOrder` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
