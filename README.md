# wmff
Wimborne Minster Folk festival Website

This is the master data for the website, no data is loaded here (ever)

Needs php 7

Installation:

Needs to be at a webspace root

Php needs the webspace root in the php include path.  

If the mysql (or equivalent compatible) database is not to be called wmff, copy and edit Example_Config.ini into Configuration.ini on your system

Create an appropriate database user and edit into the Configuration ini

Then run int/Initialise.php - (when written) this will create appropriate subdirectories and populate the database with appropriate initial tables and values.

It will (soon?) track changes to the structure of the database and automatically update as appropriate.

Does not include the data from the database, and most uploaded images under /images


Branches:
* master - all code changes to be against this branch
* live - the live real site


