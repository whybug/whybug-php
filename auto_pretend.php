<?php
// Search for a Whybugfile file.
set_include_path(get_include_path() . DIRECTORY_SEPARATOR . join(DIRECTORY_SEPARATOR, array('/etc/', __DIR__ . '/')));

require_once 'vendor/autoload.php';
Whybug\Tracker::trackErrors(parse_ini_file('Whybugfile.ini'));

