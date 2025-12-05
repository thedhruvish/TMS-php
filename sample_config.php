<?php

// Database Config

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tms-php'); # your database name

// site config
define('SITE_URL', '/tms-php/');
// google auth
define('GOOGLE_CLIENT_ID', ''); # your google client id https://console.developers.google.com
define('GOOGLE_CLIENT_SECRET', ''); # your google client secret https://console.developers.google.com
define('GOOGLE_REDIRECT_URI', 'http://localhost/tms-php/g-callback.php');


define("IS_ALTER_ACTIVE", true);

// telegram bot setup
define("BOT_TOKEN", ""); # your bot token https://t.me/BotFather
define("CHAT_ID", "");


?>