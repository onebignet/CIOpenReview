CIOpenReview ©2015 CIOpenReview.com
OpenReviewScrript ©2011-2012 OpenReviewScript.org

CIOpenReview is free, open source software for creating product or service review websites, written in PHP using the CodeIgniter framework (http://codeigniter.com) and released under the GPLv2 license (see the 'LICENSE' file for more details).

CIOpenReview is a fork of the original OpenReviewScript which was abandoned as of 2012. There have been several changes to the original script:

* CIOpenReview now uses CodeIgniter 3.0 and has updated requirements (see Server Requirements)
* You can now rate in 1/2 star increments
* Debug information is stored in a table on the DB
* Bootstrap-based Theme
* User password hashed with password_hash() (Much more secure)

Newer features are in the works and I am open to any suggestions:
* Adding admin and user logging
* Improved click-through stats (currently show total click for lifetime of review)
* Language translations
* Improvements in user security
* More themes
* Automatic Social Media submissions on new reviews.

IMPORTANT: This software is currently beta, there will probably be some bugs/issues. Please report them ASAP and I will get them cleared up.

Server Requirements
===================
 - PHP version 5.2.4+ (PHP 5.4 Recommended)
 - MySQL 5.1+
 - PHP GD Library
 - PHP cURL Library (libcurl)

Also required for Installation:
 - An FTP client / Shell Access to be able to upload/download script

Installation
============

Installing CIOpenReview is pretty easy:

* Upload the script to the directory that you wish to host it from and update the file permissions

```
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;
```

* You will also want to change the ownership of the files to be the same of the server running them. (On shared hosts, this will be your FTP user. On dedicated hosts
it might be nginx or apache

```
chown -R <user> *
```

* Open the installer page located at

<your_domain_name>/install/