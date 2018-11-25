<?php
header("content-type:text/html;charset=utf-8");
error_reporting(E_ALL);//调试请打开报错，实际操作可以屏蔽！

use BLangPack\BLangPack;
require_once(__DIR__ . '/../autoload.php');
set_time_limit(1800);//超时设置
//两个目录都需要写权限，否则无法生成和替换
$dir = __DIR__ . '/web/';//要生成语言包的目录
$langpackdir = __DIR__ . '/lang/';//生成语言包存放目录
$test = new BLangPack($dir,$langpackdir);
$test->langpackfilename ='zh';//语言包文件名
$test->extension ='php';//文件扩展名
if($test->run()){
    echo "执行完毕";
}else{
    echo "执行失败";
}