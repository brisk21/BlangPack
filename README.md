# BlangPack
基于PHP实现系统多语言自动替换，语言包自动生成，可设置从中文转换多种语言包。利用百度翻译接口自动翻译，在Rainbow的基础上修改和完善。
# 案例操作
    test.php
    <?php
    header("content-type:text/html;charset=utf-8");
    error_reporting(E_ALL);//调试请打开报错，实际操作可以屏蔽！   
    
    use BLangPack\BLangPack;;
    require_once(__DIR__ . '/Autoload.php');
    set_time_limit(1800);//超时设置
    $dir = __DIR__.'/test/admin/card/';
    $langpackdir = __DIR__.'/test/admin/card/';
    $test = new Rainbow($dir,$langpackdir);
    $test->langpackfilename ='zh';
    if($test->run()){
        echo "success";
    }else{
        echo "fail";
    }

#本次修复
请求翻译接口超时问题  
修复多层文件夹缺少判断导致报错问题  
修复源文件变量替换问题  
新增echo类型文本替换模式  

#待完善
深层目录遍历生成并自动替换原有汉语  
自定义文件类型模式替换，目前替换php较为可靠  
修复同名替换了部分问题  
替换不能递归问题