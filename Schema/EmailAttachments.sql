CREATE TABLE `EmailAttachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `EmailId` int(11) NOT NULL,
  `AttName` int(11) NOT NULL,
  `AttBody` blob NOT NULL,
  `AttFileName` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
