CREATE TABLE `Sides` (
  `SideId` int(11) NOT NULL AUTO_INCREMENT,
  `SN` text,
  `ShortName` text NOT NULL,
  `Category` tinyint(4) NOT NULL,
  `IsASide` tinyint(4) NOT NULL,
  `IsAnAct` tinyint(4) NOT NULL,
  `IsOther` tinyint(4) NOT NULL,
  `Importance` int(11) NOT NULL,
  `Type` text NOT NULL,
  `HasAgent` tinyint(4) NOT NULL,
  `AgentName` text NOT NULL,
  `AgentEmail` text NOT NULL,
  `AgentPhone` text NOT NULL,
  `AgentMobile` text NOT NULL,
  `Contact` text NOT NULL,
  `Email` text NOT NULL,
  `Phone` text NOT NULL,
  `Mobile` text NOT NULL,
  `Address` text NOT NULL,
  `PostCode` text NOT NULL,
  `AltContact` text NOT NULL,
  `AltEmail` text NOT NULL,
  `AltPhone` text NOT NULL,
  `AltMobile` text NOT NULL,
  `Location` text NOT NULL,
  `Likes` text NOT NULL,
  `Dislikes` text NOT NULL,
  `DataCheck` text NOT NULL,
  `Notes` text NOT NULL,
  `SideStatus` smallint(6) NOT NULL,
  `Workshops` text NOT NULL,
  `Surface_Tarmac` tinyint(1) NOT NULL,
  `Surface_Flagstones` tinyint(1) NOT NULL,
  `Surface_Grass` tinyint(1) NOT NULL,
  `Surface_Stage` tinyint(1) NOT NULL,
  `Surface_Brick` tinyint(4) NOT NULL,
  `StagePA` text NOT NULL,
  `MorrisAnimal` text NOT NULL,
  `Photo` text NOT NULL,
  `ImageHeight` int(11) NOT NULL,
  `ImageWidth` int(11) NOT NULL,
  `Video` text NOT NULL,
  `CostumeDesc` text NOT NULL,
  `Description` text NOT NULL,
  `Blurb` text NOT NULL,
  `Website` text NOT NULL,
  `Facebook` text NOT NULL,
  `Twitter` text NOT NULL,
  `Instagram` text NOT NULL,
  `ProcessionalDance` tinyint(1) NOT NULL,
  `NoiseLevel` tinyint(1) NOT NULL,
  `MinStage` text NOT NULL,
  `Overlaps` text NOT NULL,
  `OverlapD1` int(11) NOT NULL,
  `OverlapD2` int(11) NOT NULL,
  `OverlapM1` int(11) NOT NULL,
  `OverlapM2` int(11) NOT NULL,
  `AccessKey` text NOT NULL,
  `Party Tricks` text NOT NULL,
  `Pre2017` text NOT NULL,
  `Share` tinyint(11) NOT NULL,
  `NeedBank` tinyint(4) NOT NULL,
  `SortCode` text NOT NULL,
  `Account` text NOT NULL,
  `AccountName` text NOT NULL,
  `DocDirNum` int(11) NOT NULL,
  `RelOrder` int(11) NOT NULL,
  PRIMARY KEY (`SideId`),
  KEY `SideNum` (`SideId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;