CREATE TABLE `OtherPayments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Code` text NOT NULL,
  `Amount` int(11) NOT NULL,
  `State` tinyint(4) NOT NULL,
  `Year` int(11) NOT NULL,
  `IssueDate` int(11) NOT NULL,
  `Source` int(11) NOT NULL,
  `SourceId` int(11) NOT NULL,
  `Notes` text NOT NULL,
  `DueDate` int(11) NOT NULL,
  `PayDate` int(11) NOT NULL,
  `SN` text NOT NULL,
  `Reason` text NOT NULL,
  `PaidTotal` int(11) NOT NULL,
  `History` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
