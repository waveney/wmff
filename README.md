# wmff
Wimborne Minster Folk festival Website

This is the master data for the website, no data is loaded here (ever)

Installation:

Needs to be at a webspace root

Php needs the webspace root in the php include path as well as pear to be installed.  
Needs Pear::Mail which needs Pear::Net_SMTP

Needs mysql database "wmff" at localhost

The following empty directories need to be created:
int/Store int/OldStore int/PAspecs int/Insurance int/Temp int/LogFiles

Does not include the data from the database, and most uploaded images under /images


Branches:
master - all code changes to be against this branch
staging - for extensive tests of a nearly live update for large changes
live - the live real site


