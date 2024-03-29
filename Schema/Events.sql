CREATE TABLE `Events` (
  `EventId` int(11) NOT NULL AUTO_INCREMENT,
  `Year` text NOT NULL,
  `Venue` int(11) NOT NULL,
  `Day` tinyint(4) NOT NULL,
  `Start` int(11) NOT NULL,
  `End` int(11) NOT NULL,
  `Setup` int(11) NOT NULL,
  `SlotEnd` int(11) NOT NULL,
  `DoorsOpen` int(11) NOT NULL,
  `SN` text NOT NULL,
  `Type` int(11) NOT NULL,
  `Family` tinyint(4) NOT NULL,
  `NonFest` tinyint(4) NOT NULL,
  `SubEvent` int(11) NOT NULL,
  `Public` tinyint(4) NOT NULL,
  `BigEvent` tinyint(4) NOT NULL,
  `Special` tinyint(4) NOT NULL,
  `LongEvent` tinyint(4) NOT NULL,
  `IsConcert` tinyint(4) NOT NULL,
  `ListDance` tinyint(4) NOT NULL,
  `ListMusic` tinyint(4) NOT NULL,
  `EndDay` tinyint(4) NOT NULL,
  `ExcludeCount` tinyint(4) NOT NULL,
  `IgnoreClash` tinyint(4) NOT NULL,
  `StewardsNeeded` tinyint(4) NOT NULL,
  `Price1` double NOT NULL,
  `Price2` double NOT NULL,
  `Price3` double NOT NULL,
  `DoorPrice` double NOT NULL,
  `TicketCode` text NOT NULL,
  `Side1` int(11) NOT NULL,
  `Side2` int(11) NOT NULL,
  `Side3` int(11) NOT NULL,
  `Side4` int(11) NOT NULL,
  `Act1` int(11) NOT NULL,
  `Act2` int(11) NOT NULL,
  `Act3` int(11) NOT NULL,
  `Act4` int(11) NOT NULL,
  `Other1` int(11) NOT NULL,
  `Other2` int(11) NOT NULL,
  `Other3` int(11) NOT NULL,
  `Other4` int(11) NOT NULL,
  `PerfType1` int(11) NOT NULL,
  `PerfType2` int(11) NOT NULL,
  `PerfType3` int(11) NOT NULL,
  `PerfType4` int(11) NOT NULL,
  `NoPart` tinyint(4) NOT NULL,
  `InvisiblePart` int(11) NOT NULL,
  `Notes` text NOT NULL,
  `Duration` int(11) NOT NULL,
  `Fee` int(11) NOT NULL,
  `Description` text NOT NULL,
  `Blurb` text NOT NULL,
  `BudgetHeading` smallint(6) NOT NULL,
  `Bar` tinyint(4) NOT NULL,
  `Food` tinyint(4) NOT NULL,
  `BarFoodText` text NOT NULL,
  `Owner` int(11) NOT NULL,
  `Owner2` int(11) NOT NULL,
  `Importance` tinyint(4) NOT NULL,
  `Website` text NOT NULL,
  `Image` text NOT NULL,
  `SpecPrice` text NOT NULL,
  `SpecPriceLink` text NOT NULL,
  `Status` tinyint(11) NOT NULL,
  `NeedSteward` tinyint(4) NOT NULL,
  `ExcludePass` tinyint(4) NOT NULL,
  `ExcludeDay` tinyint(4) NOT NULL,
  `StewardTasks` text NOT NULL,
  `SetupTasks` text NOT NULL,
  `NoOrder` tinyint(4) NOT NULL,
  `UseBEnotes` tinyint(4) NOT NULL,
  `StagePA` text NOT NULL,
  `ExcludePA` tinyint(4) NOT NULL,
  `IgnoreMultiUse` tinyint(4) NOT NULL,
  `ShowSubevent` tinyint(4) NOT NULL,
  PRIMARY KEY (`EventId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
