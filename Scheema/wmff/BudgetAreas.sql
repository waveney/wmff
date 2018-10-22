CREATE TABLE `BudgetAreas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `SN` text NOT NULL,
  `Year` int(11) NOT NULL,
  `Budget` int(11) NOT NULL,
  `CommittedSoFar` int(11) NOT NULL,
  `Who` int(11) NOT NULL,
  `Who2` int(11) NOT NULL,
  `Who3` int(11) NOT NULL,
  `Who4` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
