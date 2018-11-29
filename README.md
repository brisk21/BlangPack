# BlangPack
基于PHP实现系统多语言自动替换，语言包自动生成，可设置从中文转换多种语言包。利用百度翻译接口自动翻译，在Rainbow的基础上修改和完善。
# 案例操作
    test.php
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

#本次修复
11.24:  
请求翻译接口超时问题  
修复多层文件夹缺少判断导致报错问题  
修复源文件变量替换问题  
新增echo类型文本替换模式  

替换过程存在冲突问题：  
①短字符串替换了部分长字符串，导致整个翻译只翻译了部分的问题；  
②出现①的问题尤其在php用echo或者print或者直接定义的变量中出现，导致报错

11.25 
深层目录遍历生成并自动替换原有汉语  
替换不能递归问题

11.29  
改用常量定义模式，更方便全局调用  
修复某已知替换问题

#待完善
中文被符号截断后抓取和转义问题；  
待修复变量模式标签内容替换问题诸如 ：
~~~
$status = "<span style='color:green;'>正常</span>" ;。  
