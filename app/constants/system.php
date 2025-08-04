<?php

if($_SERVER["SERVER_NAME"] == '127.0.0.1' || $_SERVER["SERVER_NAME"] == 'localhost') {

    define("DATABASETYPE", "mysql");
    define("DATABASENAME", "auser");
    define("SERVER", "localhost");
    define("URL_SERVER", "http://localhost:8888/auser/");
    define("USER", "root");
    define("PWD", "root");

} else {

    define("DATABASETYPE", "mysql");
    define("DATABASENAME", "auser");
    define("SERVER", "localhost");
    define("URL_SERVER", "https://auserlabcr.it/");
    define("USER", "admin_auser");
    define("PWD", "0_jIDmqQjgc61w&w");

}


/* Root */
if($_SERVER["SERVER_NAME"] == '127.0.0.1' || $_SERVER["SERVER_NAME"] == 'localhost') {

    define("ROOT", "/auser/");
    define("UPLOADDIR", "/Applications/MAMP/htdocs/auser/");

} else {

    define("ROOT", "/");
    define("UPLOADDIR", "/var/www/vhosts/devpws.it/auser/");
}

define("ROOTDOCUMENT", ROOT.'documents/');
define("FILES", ROOT . "files/");

define("SESSIONDURATION", 'PT12H');
define("LINKDURATION", 'PT1H');
define('CARTDURATION', 30);

/* Cookie */
define("COOKIETOKENSESSION", "cookie_token_session");

#Root Session
define("SESSIONROOT", "Auser");
define("SESSIONROOTFRONTEND", "AuserFrontend");

#Default Language
define("DEFAULTLANGUAGE", "it");

#Aes
define("AES", "0dd62947-c0f0-4e98-aa5e-f3c02b415a90");
define("IV", 'e84d9f0c-418b-43fd-adca-bf3073fb0aee');
#Email
//define("EMAILHOST", "mail.devpws.it");
//define("EMAILSMPTAUTH", true);
//define("EMAILUSERNAME", "auser@devpws.it");
//define("EMAILPWD", "Q?2SXzrk)G~H");
//define("EMAILPORT", 587);
//define("EMAILSENDER", "auser@devpws.it");
//define("EMAILSENDERAVATAR", "EVENT | Auser");

define("EMAILHOST", "mail.kapeeto.com");
define("EMAILSMPTAUTH", true);
define("EMAILUSERNAME", "hello@kapeeto.com");
define("EMAILPWD", "RizGdZ+=;x5q");
define("EMAILPORT", 587);
define("EMAILSENDER", "hello@kapeeto.com");
define("EMAILSENDERAVATAR", "Kapeeto");
// Auser SDK ID
define("ZOOMSDKKEY", "TiT3ypxRHaXOGibUSb7A");
// Nebbia SDK ID
// define("ZOOMSDKKEY", "N8li2FGOSFCxiMrTMbEL8Q");
