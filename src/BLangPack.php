<?php
/**
 * 自动生成语言包
 */
namespace BLangPack;

use BLangPack\Exception\BLangPackException;
use BLangPack\Lib\MatchChinese;
use BLangPack\Lib\AutoTranslate;
use BLangPack\Lib\ChineseToPy;


class BLangPack {
    const VERSION = '1.0.0';
    /**
     * 读取的目录
     * @var string
     */
    public $filedir;
    /**
     * 语言包文件存放路径
     * @var string
     */
    public $langpackdir;
    /**
     * 语言包文件名
     * @var string
     */
    public $langpackfilename = 'zh';
    /*
     * 语言包文件后缀
     * */
    public $extension='php';
    /*
     * 默认生成的语言
     * */
    public $langArr = ['en'];

    public $chinese_arr = [];
    public function __construct($dir,$langpackdir)
    {
        if(!is_dir($dir)) exit("The read file directory does not exist\n");
        if(!is_dir($langpackdir)) exit("The language package generated directory does not exist\n");
        $this->filedir = $dir;
        $this->langpackdir = $langpackdir;
    }

    /*
     * 目录读取
     * @return string //返回目录中所有文件
     * */
    public function scanDir($filedir){
        $files = array();
        if($handle = @opendir($filedir)) { //注意这里要加一个@，不然会有warning错误提示：）
            while(($file = readdir($handle)) !== false) {
                if($file != ".." && $file != ".") { //排除根目录；
                    if(is_dir($filedir."/".$file)) { //如果是子文件夹，就进行递归
                        $files[$file] = $this->scanDir($filedir."/".$file);
                    } else { //不然就将文件的名字存入数组；
                        $files[] = $filedir."/".$file;
                    }

                }
            }
            closedir($handle);
            return $files;
        }
        return $files;
    }

    //查找中文
    protected function MatchChinese($file)
    {
        $data = $this->loadFile($file);
        $chinese = MatchChinese::pregMatch($data);//查找中文字符串
        MatchChinese::contentReplaceTowrite($file, $data, $chinese);//替换变量
        return $chinese;
    }
    function t1($dir)
    {
        $files = [];
        foreach(glob($dir.'*.php') as $filename)
        {
            $files[] = $filename;
        }
        return $files;
    }

    /**
     * PHP 非递归实现查询该目录下所有文件
     * @param $dir
     * @return array
     */
    function scanfiles($dir)
    {
        if (! is_dir ( $dir ))
            return array ();

        // 兼容各操作系统
        $dir = rtrim ( str_replace ( '\\', '/', $dir ), '/' ) . '/';

        // 栈，默认值为传入的目录
        $dirs = array ( $dir );
        // 放置所有文件的容器
        $rt = array ();
        do {
            // 弹栈
            $dir = array_pop ( $dirs );
            // 扫描该目录
            $tmp = scandir ( $dir );
            foreach ( $tmp as $f ) {
                // 过滤. ..
                if ($f == '.' || $f == '..')
                    continue;
                // 组合当前绝对路径
                $path = $dir . $f;
                // 如果是目录，压栈。
                if (is_dir ( $path )) {
                    array_push ( $dirs, $path . '/' );
                } else if (is_file ( $path )) { // 如果是文件，放入容器中
                    $rt [] = $path;
                }
            }

        } while ( $dirs ); // 直到栈中没有目录
        return $rt;
    }

    //获取原文
    public function getSourceStr($file_arr)
    {
        $chineseArr = [];
        foreach ($file_arr as $file){
            if (!is_file($file)) continue;
            $chinese = $this->MatchChinese($file);
            if (empty($chinese)) continue;
            $chineseArr[] = $chinese;
        }
        return $chineseArr;
    }

    /*
    *执行
    *@return bool
     * fixme 缺少深度遍历和匹配
    * */
    public function run(){
        try {
            $files = $this->scanfiles($this->filedir);
            $ZhArr = $this->getSourceStr($files);
            $chineseArr=[];
            //合并一下数组
            foreach ($ZhArr as $item){
                $chineseArr =  array_merge($chineseArr,$item);
            }
            $chineseArr = array_flip($chineseArr);//翻转
            $chineseArr = array_keys($chineseArr);
            $chinese = [];
            foreach ($chineseArr as $key => $v) {
                $chinese[ChineseToPy::encodePY($v, 'all')] = $v;
                unset($chineseArr[$key]);
            }

            $this->CreateLangPack($chinese, $this->extension);
            if (!empty($this->langArr)) {
                foreach ($this->langArr as $lang) {
                    $chinese_new = [];
                    $this->langpackfilename = $lang;
                    /*if(count($chinese) >2000)
                    {
                        $chinese_tmp = array_slice($chinese, 2000);
                    }else{
                        $translate_str = implode('\n',$chinese);
                        $translate = AutoTranslate::translate($translate_str, 'zh', $lang);
                        print_r($translate);
                    }*/
                    foreach ($chinese as $key => $v) {
                        $chinese_new[$key] = AutoTranslate::translate($v, 'zh', $lang);
                    }
                    $this->CreateLangPack($chinese_new, $this->extension);
                }
            }
        } catch (BLangPackException $e) {
return $e->getMessage();
}
return true;
}

/*
 * 读取文件
 * @param string $sFilename //文件路径
 * @return string //返回文件内容
*/
public function loadFile($sFilename, $sCharset = 'UTF-8')
{
    if (floatval(phpversion()) >= 4.3) {
        $sData = file_get_contents($sFilename);
    } else {
        if (!file_exists($sFilename)) return -3;
        $rHandle = fopen($sFilename, 'r');
        if (!$rHandle) return -2;

        $sData = '';
        while(!feof($rHandle))
            $sData .= fread($rHandle, filesize($sFilename));
        fclose($rHandle);
    }
    /*if ($sEncoding = mb_detect_encoding($sData, 'auto', true) != $sCharset) {
        $sData = mb_convert_encoding($sData, $sCharset,"UTF-8");
    }*/
    $path_parts = pathinfo($sFilename);
    if($path_parts['extension'] == 'html'){
        $sData = strip_tags($sData);
        if(empty($this->extension)) $this->extension = 'php';
    }else if($path_parts['extension'] == 'js'){
        if(empty($this->extension)) $this->extension = 'js';
    }
    return $sData;
}

/*
 * 生成语言包
 * */
private function CreateLangPack($data = [],$file_type = 'php'){
    $file_type = strtoupper($file_type);

    switch($file_type){
        case 'PHP':
            $langpackfilename = $this->langpackfilename.'.php';
            $info = "<?php return " . var_export($data, true) . "; ?>";
            break;
        case 'JS':
            $langpackfilename = $this->langpackfilename.'.js';
            $info = '';
            foreach($data as $key => $v){
                $info .= " var $key='$v';";
                unset($data[$key]);
            }
            break;
        default:
            echo '语言包扩展未知';
            exit;
    }
    unset($data);
    if(!file_put_contents($this->langpackdir.$langpackfilename,$info)){
        return false;
    };
    return true;
}
}