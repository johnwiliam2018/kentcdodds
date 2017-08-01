<?php
define('DEFAULT_TIMEZONE', 'Europe/Warsaw');

define('HTTP_SERVER', "http://" . $_SERVER["SERVER_NAME"]);
// define('HTTP_CATALOG_SERVER', HTTP_SERVER . "/2017/vehix/");
define('HTTP_CATALOG_SERVER', HTTP_SERVER . "/");

define('DIR_WS_CONFIGURE', DIR_FS_DOCUMENT_ROOT . 'config/');
define('DIR_WS_LIBRARY', DIR_FS_DOCUMENT_ROOT . 'library/');
define('DIR_WS_FUNCTIONS', DIR_FS_DOCUMENT_ROOT . 'library/functions/');
define('DIR_WS_CLASSES', DIR_FS_DOCUMENT_ROOT . 'library/classes/');
define('DIR_WS_BOX', DIR_FS_DOCUMENT_ROOT . 'library/box/');
define('CACHE_DIR', DIR_WS_LIBRARY . 'cache/');

//Session
define('SESSION_NAME', 'images_api');
define('SESSION_USER_ID', 'images_user_id');
define('SESSION_WRITE_DIRECTORY', DIR_WS_LIBRARY . 'cache/');

define('USE_PCONNECT', 'false');
define('STORE_SESSIONS', 'mysql');
define('CHARSET', 'utf8');

define('EMAIL_TYPE', 'sendmail');
// "mail", "sendmail", or "smtp"
//define('SNED_EMAIL_PATH', 'D:/xampp/htdocs/appevolution/test/sendmail/sendmail -t');
define('SNED_EMAIL_PATH', 'usr/sbin/sendmail -t -i');
define('EMAIL_ADMIN_NAME', 'Admin');
define('EMAIL_ADMIN_ADDRESS', 'info@carsoup.com/');
// cy8Hdrzv$Qu]

/* upload file */
define('DIR_WS_UPLOAD', DIR_FS_DOCUMENT_ROOT . 'uploads/images/');
define('HTTP_WS_UPLOAD', HTTP_CATALOG_SERVER . 'uploads/images/');

// Password Min, Max Length
define('USER_PASSWORD_MIN_LENGTH', 6);
define('USER_PASSWORD_MAX_LENGTH', 30);

define("VENUE_QRCODE_BASIC_URL", HTTP_CATALOG_SERVER . "qr/");

define('AVATAR_IMAGE_WIDTH', 1280);
define('AVATAR_IMAGE_HEIGHT', 0);

define('DEFAULT_MALE_AVATAR', HTTP_WS_UPLOAD . "avatar/male_avatar.png");
define('DEFAULT_FEMALE_AVATAR', HTTP_WS_UPLOAD . "avatar/female_avatar.png");

define('PRODUCT_IMAGE_COUNT', 3);

$no_login_pages = array(
	"login.php",
	"logout.php",
	"signup.php",
	"forgotpassword.php",
	"resetpassword.php",
	"autoscrape.php"
);

$device_colors = array(
	"phone" => array('#3498DB', '#49A9EA'),
	"tablet" => array('#E74C3C', '#E95E4F'),
	"computer" => array('#9B59B6', '#B370CF')
);

define('ASSETS_ORIGINAL_VERSION', '1.0');
define('ASSETS_CUSTOM_VERSION', time());//'1.0.2');

?>