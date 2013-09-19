<?php
//this is the php file with all of the configuration information for the application.
//this is a local config file, do not change on dev server.

//dsn used to connect to database
define ("DB_DSN", "mysql:dbname=time_tracker");
define ("DB_USERNAME", "root");
define ("DB_PASSWORD", "bapot3844");
//how many records should show per page
define ("PAGE_SIZE", 5);
//table definitions for client path
define ("TBL_CLIENT", "client");
//this is a configuration switch. Currently, the address on client creation is one large varchar field. If you want to switch it to a normalized table, change this to 1. You will have to develop the individual address forms in the UI.
define ("ADDRESS_CONFIG", "0");
define ("TBL_CLIENT_ADDRESS", "client_address");
define ("TBL_CURRENCY", "client_currency");
//table definitions for contact path
define ("TBL_CONTACT", "contact");


//This is a security risk! Login and PW should be stored outside of the file in a prod env.
?>