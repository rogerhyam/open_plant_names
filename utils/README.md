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

## config.php

Some of the scripts use credentials that shouldn't be checked into GitHub. The config.php file therefore includes a file called open_secrets.php in the directory below the installation root, like this:

require_once('../../../open_secrets.php');

If you are going to use the database dependent scripts here you need to create this PHP file. There is a template version of the open_secrets.php file in the utils/php director simply copy that and add your local credentials to the copy. One workflow might be:

cd utils/php
cp open_secrets.php ../../../open_secrets.php
nano ../../../open_secrets.php

This assumes you have a MySQL database and user with rights to that database set up.

## import.php

Used to import all the data files into a local MySQL database. Having set up a database and created a credentials file as described above you would simply cd in the utils/php directory and run a command like this:

php -d memory_limit=1G import.php

You might like to adjust the memory_limit depending on your machine. It might take a while but not forever.

## export.php

Used to export local MySQL database back to the file structure for pushing to GitHub. From the utils/php directory run something like:

php -d memory_limit=1G import.php





