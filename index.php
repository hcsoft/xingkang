<?php
/**
 * 入口
 *
 *
 *
 * @copyright  Copyright (c) 2014-2020
 * @license
 * @link
 * @since      File available since Release v1.1
 */
$site_url = strtolower('http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/index.php')).'/shop/index.php');
@header('Location: '.$site_url);

