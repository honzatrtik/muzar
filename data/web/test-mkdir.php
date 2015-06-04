<?php
/**
 * Date: 21/05/15
 * Time: 11:24
 */



var_dump(get_current_user());

$dir = '/data/web/media/upload/26/05';
var_dump(file_exists('/data/web/media/upload/'));
var_dump(is_writable('/data/web/media/upload/'));
touch('/data/web/media/upload/touched');
mkdir($dir, 0775, true);