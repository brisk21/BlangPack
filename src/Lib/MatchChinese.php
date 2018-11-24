<?php
/**
 * 获取文件中的中文
 * Date: 2018-04-16
 * Time: 16:10
 */

namespace BLangPack\Lib;
use BLangPack\Lib\ChineseToPy;

class MatchChinese {
    //过滤规则
    public static $preg_match = "/[\x{4e00}-\x{9fa5}]+/u";
    /*
     * 正则匹配
     * @param string $content //字符串
     * @return array //返回字符串中的中文数组
    * */
    public static function pregMatch($content){
        $chinese = [];
        if(empty($content)) return [];
        //去除注释
        $content = preg_replace("@/\*.*?\*/@s", '', str_replace(array("\r\n", "\r"), "\n", $content));
        $content = preg_replace('@\s*//.*$@m','',$content);
        $content = preg_replace('#<!--.*-->#','',$content);
        if (preg_match_all(self::$preg_match,$content,$match))
            $chinese = $match[0];
        return $chinese;
    }

    /*
     * fixme 目录时未能替换下级，需要修复
     *文件中对应语言包替换
     * 兼容php直接echo的替换，一键匹配
     * */
    public static function contentReplaceTowrite($file,$string,$search,$replace = [])
    {
        if (is_array($file)) return false;
        $path_parts = pathinfo($file);

        if (!is_file($file) || $path_parts['extension']<>'php' ){
            return false;
        }
        foreach ($search as $key => $v) {
            $search[$key] = $v;
            $res= ChineseToPy::encodePY($v, 'all');//翻译后的
            if (strpos($string,"'".$v."'")){
                $lang = ' $lang[\''.$res.'\']';
                $string = str_replace("'".$v."'",$lang,$string);
            }elseif (strpos($string,"\"".$v."\"") ){
                $lang = '$lang[\''.$res.'\']';
                $string = str_replace("\"".$v."\"",$lang,$string);
            }else{
                $lang = '<?php echo $lang[\''.$res.'\']; ?>';
                $string = str_replace($v,$lang,$string);
            }
        }
        file_put_contents($file,$string);
    }
}