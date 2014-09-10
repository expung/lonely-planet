Lonely Planet
=============
1. Description

This software has been developed to work as a batch processor for Lonely planet CMS. It can process the input files that contain
the taxonomy and the destinations (both XML files) and output the rendered html docs to an output folder.

The software has been developed in PHP (version 5.5.16) and can run via the command line (see section 3 for usage).

Tests have been included in the tests.php file and make use of the SimpleTest library.

HTML rendering is performed using twig templates.

=============
2. Installation

In order to run the PHP batch processor, PHP 5.5.16 has to be installed.
Please get the file from the following location: http://au2.php.net/get/php-5.5.16.tar.gz/from/a/mirror
If you use a windows machine, please extract the contents of the compressed file into a directory and add the path to PATH env. variable.
(Good guide-> http://www.sitepoint.com/how-to-install-php-on-windows/).

You'll probably need to install Microsoft Visual C++ (http://www.microsoft.com/en-us/download/confirmation.aspx?id=30679) to be able to run PHP.

For your convenience, the needed files have been included in the _need_to_be_installed folder.

Twig and SimpleTest are included in the repository, so no extra need for installation of those libraries.

=============
3. Usage

To run the batch processor, the input XMLs must be defined, along with the output directory.

Usage:
    php batch.php -d [destination.xml] -t [taxonomy.xml] -f [folder to save location]

Example usage: php batch.php -d destinations.xml -t taxonomy.xml -f Guide

To execute the test type: php tests.php