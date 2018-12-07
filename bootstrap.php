<?php

define('ROOT_PATH', getcwd());
define('VIEWS_PATH', ROOT_PATH . '/templates/');
define('UPLOAD_DIR', ROOT_PATH . '/uploads/');

require_once(ROOT_PATH . '/core/view.php');
require_once(ROOT_PATH . '/core/db_tools.php');
require_once(ROOT_PATH . '/core/validate.php');
require_once(ROOT_PATH . '/data/projects.php');
require_once(ROOT_PATH . '/data/tasks.php');
require_once(ROOT_PATH . '/project/functions.php');

$user = [
    'id' => 1,
    'name' => 'Константин',
    'avatar' => 'assets/img/user-pic.jpg'
];
