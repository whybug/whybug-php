<?php
// Include the composer autoloader, Whybug is expected in ~/.composer/vendor/whybug/whybug-php.
require_once __DIR__ . '/../../autoload.php';

// Set paths for a Whybugfile file.
set_include_path(get_include_path() . DIRECTORY_SEPARATOR . join(DIRECTORY_SEPARATOR, array('/etc/', __DIR__ . '/')));

Whybug\Tracker::trackErrors(parse_ini_file('Whybugfile.ini'));

