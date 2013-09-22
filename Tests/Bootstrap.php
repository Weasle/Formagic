<?php
error_reporting(-1);
ini_set('display_errors', 1);
putenv('TZ=Europe/Berlin');

require_once dirname(__FILE__) . '/../src/Formagic/Autoloader/Autoloader.php';
Formagic_Autoloader::register(array(
    realpath(dirname(__FILE__) . '/Formagic')
));
