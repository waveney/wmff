# wmff
Wimborne Minster Folk festival Website

This is the master data for the website, no data is loaded here (ever)

Installation:

Needs to be at a webspace root

Php needs the webspace root in the php include path as well as pear to be installed.  

Needs Pear::Mail which needs Pear::Net_SMTP (on live systems only)

Needs mysql (or equivalent compatible) database "wmff" at localhost and closed to non-direct access

The following empty directories need to be created:

int/Store int/OldStore int/PAspecs int/Insurance int/Temp int/LogFiles int/Contracts

For a non live site create an empty file int/testing - this prevents it sending emails

Does not include the data from the database, and most uploaded images under /images


Branches:
* master - all code changes to be against this branch
* live - the live real site


