<?php

/**
 * @version 4.5.07
 * @author Jules
 * @license GNU/GPL version 2 or any later version
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

define('NV_IS_FILE_ADMIN', true);

$allow_func = array('main', 'log');
