# Gibbon File Cleanup Module
This module allows you to list all files in the upload folder. From this list, you can run a number of searches to find out which resources are in use and which aren't.

NOTE: The list of tables checked is incomplete. You may add the table and column to the list in z-fileclean-ajax.php

## Requirements/Prerequisites

This tool has been tested on Debian Buster. In theory it should work with Windows OSes but there are some known compatibility issues on MAC OSX which are referenced in GibbonEdu's Slack during testing. 

The tool also assumes that you have PHP My Admin installed. Manual searches on the data are possible though the process will take much longer and there are no instructions for this.

## Installation

1. [Download this repo](https://github.com/jian118/gibbonFileCleanup/archive/master.zip) or clone it:
```
git clone git@github.com:jian118/gibbonFileCleanup.git
```
2. Unzip the file either using an archive tool such as unzip (Linux) or WinZip (Windows).
3. Once you have a copy, open the folder and move the contents to the Gibbon modules directory (Gibbon root /modules). The folder you move should be named "File Cleanup".
4. Log into Gibbon as an administrator then go to Admin > System Admin > Manage Modules. At the bottom of the list, you should see an entry for "File Cleanup". Click the "Add" (plus icon) button and the module will be installed.

## Usage
Go to Admin > File Cleanup as a Gibbon adminstrator.
