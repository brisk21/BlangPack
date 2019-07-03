<?php
/**
 * 简单的自动加载
 * Created by PhpStorm.
 * User: wbs
 * Date: 2018/11/25
 * Time: 14:42
 */
spl_autoload_register(function($class){
    $path = str_replace('BLangPack\\',DIRECTORY_SEPARATOR,$class);
    $file = str_replace('//',DIRECTORY_SEPARATOR,__DIR__.DIRECTORY_SEPARATOR.$path.'.php');
    if (file_exists($file)){
        require $file;
    }
});