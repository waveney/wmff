CREATE TABLE `InvoiceCodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` int(11) NOT NULL,
  `SN` text NOT NULL,
  `Notes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
