<?php
  include_once("fest.php");

  A_Check('Staff','Photos');

  dostaffhead("Manage Photos",'<script src="/js/jquery-3.2.1.min.js"></script>');

/* Edit images for Sides, Traders, Sponsors
   If not stored appropriately, store in right place afterwards
   If was in store, and there is NOT an .orig file save original as .orig

   Allow croping to square or landscape, Zoom

   Will use cropit jquery plugin to do most of the manipulation

   Select what, and format wanted

   Edit

   Save
*/
