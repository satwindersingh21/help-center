<?php
//if (strpos($_SERVER['SCRIPT_URI'], 'www') !== false) {
//    if (strpos($_SERVER['SCRIPT_URI'], 'https') !== false) {
//        header("LOCATION:".str_replace("www.","", $_SERVER['SCRIPT_URI']));
//    } else {
//        header("LOCATION:".str_replace("www.","", str_replace("http","https",$_SERVER['SCRIPT_URI'])));
//    }
//}
define('SITE_TITLE', 'PelvicHEP');
define('SITE_URL', 'http://localhost/help-center/');
define('LONG_DATE', 'l, F j, Y \a\t g:i a');
define('SHORT_DATE', 'M j, y  g:i a');
define('DATE_ONLY', 'M j, Y');
define('NICE_DATE', 'l, F j, Y');
define('PAGE_LIMIT', 20);
/*
 * Database Details
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'gurunanak');
define('DB_NAME', 'help_center');

/*
 * SMTP Details
 */

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'er.singhsandy@gmail.com');
define('SMTP_PASS', 'dodeveopment');

/*
 * Status Constants
 */

define('SUCCESS_CODE', 200);
define('INVALID_ACCESS_TOKEN', 459);
define('TOKEN_EXPIRED', 457);
define('CODE_BAD_REQUEST', 400);
define('SYSTEM_ERROR', 500);
define('MISMATCH', 408);
define('USER_ALREADY_EXIST', 520);
define('RECORD_NOT_FOUND', 404);
define('PAGE_NOT_FOUND', 404);
define('EMAIL_DOESNOT_REGISTERED', 413);



