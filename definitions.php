<?php

// HTML CONSTANTS
define("HTML_RESET", "<div class='page_reset'>&nbsp;</div>");

// URL CONSTANTS
define("CLASSES_PATH", $_SERVER['DOCUMENT_ROOT'] . "classes/*.class.php");
define("API_ROOT", $_SERVER['DOCUMENT_ROOT'] . "/api/");
define("VENDORS", $_SERVER['DOCUMENT_ROOT'] . "/vendor/");

// DATE FORMATS
define("DATE_DISPLAY", "l, jS F Y");
define("SHORT_DATE", "Y-m-d");
define("SHORT_DISPLAY_DATE", "d/m/Y");
define("TIME_FORMAT", "H:i:s");
define("TIMEINDEX", "U");
define("FULL_DATETIME", "d-m-Y H:i:s");
define("MYSQL_DATETIME", "Y-m-d H:i:s");

// COOKIE CONSTANTS
define("COOKIE_EXPIRE", -1);
define("COOKIE_UNIQUE_EXPIRE", 15552000);

// TEXT

// MYSQL PARAMETERS
define("MYSQL_HOST", "localhost");
define("MYSQL_USERNAME", "");
define("MYSQL_PASSWORD", "");
define("MYSQL_DATABASE", "");
define("MYSQL_PORT", "3306");
