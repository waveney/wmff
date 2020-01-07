CREATE TABLE `EmailLog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Date` int(11) NOT NULL,
  `FromAddr` text NOT NULL,
  `ToAddr` text NOT NULL,
  `Subject` text NOT NULL,
  `TextBody` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
