# Zero Web Data Tools
Web based data tools for Zero Motorcycles

##Functions include:
 - web wrapper for the Python based log parser
 - basic analyzer of summary data of uploaded logs
 - VIN decoder utility
 - Access to spreadsheets able to analyze a given log

##Web server must:
 - run PHP
 - have Python 2.7 installed
 - allow uploads to logs directory

##Web folder structure
 - /images/ storage of background and help images
 - /logs/ where both uploaded .bin files are stored, as well as the parsed .txt versions of the same
 - /vindecoder/ files specifically related to the VIN decoder
 - /python/ should contain the most current version of the Python zero-log-parser
 - / root folder - contains index page for access to all tools, contains the wrapper for the log parser, 
   the log analyzer, related web files, and the downloadable .xls file.

* Some files may require adjustment to folder paths based on where it's placed on the web server.
Current design is that all web files reside in a folder on the server called "/zerologparser/"

##Log Parser Wrapper
This function allows uploading a downloaded Zero motorcycle log file (.bin) to the server,
which will then run the Python parsing script against the log file and return the text version.

##Log Analyzer
Uploaded log files are stored on the server and summarized for basic motorcycle information.

##VIN Decoder
A VIN decoder specfically for Zero Motorcycles VINs, based on information about the VIN
published by Zero in the various Owner's Manuals.

##Spreadsheets
In both .XLS and Google Sheets versions, these spreadsheets allow detailed ride analysis from
the data available in the parsed text log files.

