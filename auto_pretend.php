<?php
// Include the composer autoloader.
require_once __DIR__ . '/vendor/autoload.php';

// Set paths for a Whybugfile file.
set_include_path(get_include_path() . DIRECTORY_SEPARATOR . join(DIRECTORY_SEPARATOR, array('/etc/', __DIR__ . '/')));

Whybug\Tracker::trackErrors(parse_ini_file('Whybugfile.ini'));

