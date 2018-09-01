<?php
  include_once("fest.php");
  
  A_Check('SysAdmin');

  $tables = [
'Acts',
'ActYear',
'Articles',
'BandMembers',
'BigEvent',
'BudgetAreas',
'Bugs',
'CampsiteUse',
'DanceTypes',
'Directories',
'Documents',
'EmailProformas',
'Events',
'EventTypes',
'FestUsers',
'Galleries',
'GallPhotos',
'General',
'InvoiceCodes',
'Invoices',
'LogFile',
'MapPoints',
'MapPointTypes',
'MasterData',
'MusicTypes',
'News',
'OtherLinks',
'OtherPart',
'OtherPartYear',
'Overlaps',
'Participant',
'PartYear',
'Sides',
'SideYear',
'SignUp',
'Sponsors',
'Stewards',
'TaxiCompanies',
'Tickets',
'TimeLine',
'Trade',
'TradeLocs',
'TradePrices',
'TradeYear',
'Venues',
'Water'
];

echo "Starting<p>";
foreach ($tables as $tab) {
  $res = $db->query("ALTER TABLE `$tab` CHANGE `SName` `SN` TEXT");
  if (!$res) echo $db->error . "<br>";
  if ($res) echo "Changed $tab<br>";
}

echo "Bespoke changes<br>";
$db->query("ALTER TABLE `Invoices` CHANGE `BName` `BZ` TEXT");
$db->query("ALTER TABLE `Invoices` CHANGE `CName` `Contact` TEXT");

echo "Finished<p>";
?>
