# music-archive
**music-archive** is a PHP application for administrating a music collection using PostgreSQL.

## Requirements
- PHP enabled web server (e.g. Apache)
- PHP 5.5+ with PostgreSQL support
- PostgreSQL
- A graphical browser with Javascript enabled (e.g. Firefox)
- PHPUnit (only for development needed)

## Installation
1. Create a new database user with a password set.
2. Create a new database.
3. Set up the permissions of the new database.
4. Make sure that password authentication for the new database and the new user is used.
5. Edit *php/config.php.tmpl* with the details of the database connection and save it as *php/config.php*.
6. Setup user authentication for the web server. A template file *_htaccess* is provided. Modify it and save it as *.htaccess*.
7. Import the database structure. It is defined in the file 'database/schema.sql'.

## Invocation

If you have installed the application in the root folder of your web server you can access it from the same host under **http://localhost/js/**. If you have installed it under another path the URL changes accordingly.

## Known Issues
- The **Random** button is not fully functional yet.

