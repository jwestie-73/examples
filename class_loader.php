<?php

// standard classes from the classes folder
foreach (glob(CLASSES_PATH) as $filename) {
    include $filename;
}

//3rd party (VENDOR) classes
include VENDORS . 'browser_detect/BrowserDetection.php';
include VENDORS . 'fpdf/fpdf.php';
// include VENDORS . 'fpdi/fpdi.php'; // not in use yet.

// bolt on classes
include 'errorhandler/audit_log.class.php';
//include 'errorhandler/errorhandler.class.php'; // Old Audit Log.
include 'login/login.class.php';