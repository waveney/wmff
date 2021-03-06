CREATE TABLE `General` (
  `Year` int(11) NOT NULL,
  `Version` text NOT NULL,
  `Freeze` tinyint(11) NOT NULL,
  `DanceProgAvail` tinyint(11) NOT NULL,
  `MusicProgAvail` int(11) NOT NULL,
  `SideProgLevel` tinyint(4) NOT NULL,
  `ActProgLevel` tinyint(11) NOT NULL,
  `OtherProgLevel` tinyint(4) NOT NULL,
  `DanceState` smallint(11) NOT NULL,
  `MusicState` smallint(11) NOT NULL,
  `OtherState` smallint(11) NOT NULL,
  `TradeState` smallint(11) NOT NULL,
  `StewardState` smallint(11) NOT NULL,
  `DateFri` int(11) NOT NULL,
  `MonthFri` int(11) NOT NULL,
  `FirstDay` int(11) NOT NULL,
  `LastDay` int(11) NOT NULL,
  `TradeDates` text NOT NULL,
  `PriceChange1` int(11) NOT NULL,
  `PriceChange2` int(11) NOT NULL,
  `PriceComplete_4` tinyint(4) NOT NULL,
  `PriceComplete_3` tinyint(4) NOT NULL,
  `PriceComplete_2` tinyint(4) NOT NULL,
  `PriceComplete_1` tinyint(4) NOT NULL,
  `PriceComplete0` tinyint(4) NOT NULL,
  `PriceComplete1` tinyint(4) NOT NULL,
  `PriceComplete2` tinyint(4) NOT NULL,
  `PriceComplete3` tinyint(4) NOT NULL,
  `PriceComplete4` tinyint(4) NOT NULL,
  `PriceComplete5` tinyint(4) NOT NULL,
  `PriceComplete6` tinyint(4) NOT NULL,
  `PriceComplete7` tinyint(4) NOT NULL,
  `PriceComplete8` tinyint(4) NOT NULL,
  `PriceComplete9` tinyint(4) NOT NULL,
  `PriceComplete10` tinyint(4) NOT NULL,
  `FamilyState` tinyint(4) NOT NULL,
  `SpecialState` tinyint(4) NOT NULL,
  `SatDanceStart` int(11) NOT NULL,
  `SunDanceStart` int(11) NOT NULL,
  `SatDanceEnd` int(11) NOT NULL,
  `SunDanceEnd` int(11) NOT NULL,
  `SatMusicStart` int(11) NOT NULL,
  `SunMusicStart` int(11) NOT NULL,
  `SatMusicEnd` int(11) NOT NULL,
  `SunMusicEnd` int(11) NOT NULL,
  `FriMusicStart` int(11) NOT NULL,
  `FriMusicEnd` int(11) NOT NULL,
  `TicketControl` tinyint(4) NOT NULL,
  `CampingControl` tinyint(4) NOT NULL,
  `Prefix` text NOT NULL,
  `WeekendPass` int(11) NOT NULL,
  `FridayPass` int(11) NOT NULL,
  `SaturdayPass` int(11) NOT NULL,
  `SundayPass` int(11) NOT NULL,
  `WeekendPass1` int(11) NOT NULL,
  `FridayPass1` int(11) NOT NULL,
  `SaturdayPass1` int(11) NOT NULL,
  `SundayPass1` int(11) NOT NULL,
  `WeekendPass2` int(11) NOT NULL,
  `FridayPass2` int(11) NOT NULL,
  `SaturdayPass2` int(11) NOT NULL,
  `SundayPass2` int(11) NOT NULL,
  `ProgrammeBook` int(11) NOT NULL,
  `CampingCost` int(11) NOT NULL,
  `CampingPrice1Day` int(11) NOT NULL,
  `BookingFee` text NOT NULL,
  `CampingGateFee` int(11) NOT NULL,
  `CampingPrice2Day` int(11) NOT NULL,
  `CampingPrice3Day` int(11) NOT NULL,
  `CampingPrice4Day` int(11) NOT NULL,
  `WeekendPassCode` text NOT NULL,
  `FridayPassCode` text NOT NULL,
  `SaturdayPassCode` text NOT NULL,
  `SundayPassCode` text NOT NULL,
  `NotUsed` int(11) NOT NULL,
  `CampingCode_TFSS` text NOT NULL,
  `CampingCode_TFSx` text NOT NULL,
  `CampingCode_TFxx` text NOT NULL,
  `CampingCode_Txxx` text NOT NULL,
  `CampingCode_xFxx` text NOT NULL,
  `CampingCode_xFSS` text NOT NULL,
  `CampingCode_xFSx` text NOT NULL,
  `CampingCode_xxSx` text NOT NULL,
  `CampingCode_xxSS` text NOT NULL,
  `CampingCode_xxxS` text NOT NULL,
  `TradeMainDate` int(11) NOT NULL,
  `TradeLastDate` int(11) NOT NULL,
  `WeekendText` text NOT NULL,
  `FridayText` text NOT NULL,
  `SaturdayText` text NOT NULL,
  `SundayText` text NOT NULL,
  `Years2Show` tinyint(4) NOT NULL,
  PRIMARY KEY (`Year`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
