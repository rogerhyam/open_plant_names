# Utilities

These are scripts that may be of use in manipulating, viewing or transforming the files in the data directory. They are arranged by scripting language.

If you have written scripts that work on the data please consider contributing them for others to enjoy.

## PHP

## index.php

The most useful script here at the moment is probably index.php. This provides a primitive user interface to browse around the data in the files. To run simply do the following:

1. Make sure you have PHP installed on your machine. Type PHP -v if you aren't sure.
1. Move into the utils/php directory. cd utils/php.
1. Start PHP's built in web server with: php -S localhost:1753
1. Point your web browser at http://localhost:1753/index.php

## import.php

Used to import into a local MySQL database.

## export.php

Used to export local MySQL database back to the file structure for pushing to GitHub.


