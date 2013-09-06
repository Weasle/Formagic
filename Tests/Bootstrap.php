<?php
error_reporting(-1);
ini_set('display_errors', 1);
putenv('TZ=Europe/Berlin');
set_include_path(realpath(dirname(__FILE__) . '/..') . PATH_SEPARATOR . get_include_path());

require_once('Formagic/Formagic.php');

Formagic::addBaseDir(realpath(dirname(__FILE__) . '/MockClasses'));
