# Zero Web Data Tools
Web-based data tools for Zero Motorcycles.

## Functions include:
 * Web wrapper for the [Python-based log parser](https://github.com/zero-motorcycle-community/zero-log-parser)
 * Basic analyzer of summary data of uploaded logs
 * VIN decoder utility
 * Access to spreadsheets able to analyze a given log

## Web server must:
 * Run PHP
 * Have Python 2.7 installed
 * Allow uploads to `/logs/` directory

## Web folder structure
 * `/` root folder
   * Contains the page to access to all tools.
   * Contains the wrapper for the log parser, the log analyzer, related web files, and the downloadable spreadsheet file in Excel format (`.xls`).
 * `/images/`
   * Storage of background and help images
 * `/logs/`
   * Where both uploaded `.bin` files are stored, as well as the parsed `.txt` versions of the same
 * `/vindecoder/`
   * Files specifically related to the VIN decoder
 * `/python/`
   * Should contain the most current version of the [Python zero-log-parser](https://github.com/zero-motorcycle-community/zero-log-parser)

**NOTE:** Some files may require adjustment to folder paths based on where it's placed on the web server.
The current design is that all web files reside in a folder on the server called `/zerologparser/`

## Log Parser Wrapper
This function allows uploading a downloaded Zero motorcycle log file (`.bin`) to the server,
which will then run the Python parsing script against the log file and return the text version.

## Log Analyzer
Uploaded log files are stored on the server and summarized for basic motorcycle information.

## VIN Decoder
A VIN decoder specfically for Zero Motorcycles VINs, based on information about the VIN
published by Zero in the various [Owner's Manuals](http://www.zeromotorcycles.com/owner-resources/).

## Spreadsheets
In both .XLS and Google Sheets versions, these spreadsheets allow detailed ride analysis from
the data available in the parsed text log files.
