<?php

/**
 * 通用工具包
 *
 * @author wuqingcheng
 * @date 2013.05.18
 * @email wqc200@gmail.com
 */

namespace Dang;

class Utility{
    static function multiPage($itemTotal, $pageSize, $currentPage) {
        $itemTotal = intval($itemTotal);
        if ($currentPage < 1){
            $currentPage = 1;
        }
        $pageTotal = ceil($itemTotal / $pageSize);
        if ($pageTotal < 1){
            $pageTotal = 1;
        }
        if ($currentPage > $pageTotal)
            $currentPage = $pageTotal;
        $pageStart = ( $currentPage - 1 ) * $pageSize;
        $pageEnd = $pageStart + $pageSize;
        $pageEnd = $pageEnd > $pageTotal ? $pageTotal : $pageEnd;
        $pagePrev = $currentPage - 1;
        if ($pagePrev < 0) {
            $pagePrev = "";
        }
        $pageNext = $currentPage + 1;
        if ($currentPage = $pageTotal) {
            $pageNext = "";
        }
        
        $multi = array();
        $multi['item_total'] = $itemTotal;
        $multi['page_total'] = $pageTotal;
        /*
        $multi['page_start'] = $pageStart;
        $multi['page_end'] = $pageEnd;
        $multi['page_next'] = $pageNext;
        $multi['page_prev'] = $pagePrev;
        */
        $multi['current_page'] = $currentPage;
        $multi['page_size'] = $pageSize;
        return $multi;
    }
    
    static function buildTags($tags)
    {
        //$tags = "以不断;创新/的经营(理念)为先导，建立[学习型]组织和优势团队，引进专业人才、创建开放式的企业管理模式";
        //$tags = preg_replace("/(([\x{4e00}-\x{9fa5}]),)/usi", "\\2 ", $tags);
        //$tags = preg_replace("/(,([\x{4e00}-\x{9fa5}]))/usi", " \\2", $tags);
        //$tags = preg_replace("/([0-9a-z]),([^0-9a-z])/usi", "\\1 \\3", $tags);
        //$tags = preg_replace("/([^0-9a-z]),([0-9a-z])/usi", "\\1 \\3", $tags);
        mb_internal_encoding("UTF-8");
        mb_regex_encoding("UTF-8");
        $tags = mb_ereg_replace("、|；|;|，|（|\(|\)|）|\[|\]|\/|\|", " ", $tags);
        //$tags = preg_replace("/[;\/\(\)\[\]]/usi", " ", $tags);
        $tags = trim($tags);

        return $tags;
    }

    static function buildTitle($title)
    {
        mb_internal_encoding("UTF-8");
        $title = mb_ereg_replace("、|；|，", " ", $title);
        $title = preg_replace("/[;\/\(\)\[\]]/", " ", $title);
        $title = trim($title);

        return $title;
    }

    static function writeFile($filename, $data, $method='a')
    {
        $handle = @fopen($filename, $method);
        if($handle)
        {
            fputs($handle, $data);
            fclose($handle);
        }
    }
    
    static function getFileExtension($filename)
    {    
        $imagetypes = array('1'=>'gif','2'=>'jpg','3'=>'png','4'=>'swf','5'=>'psd','6'=>'bmp','7'=>'tiff','8'=>'tiff','9'=>'jpc','10'=>'jp2','11'=>'jpx','12'=>'jb2','13'=>'swc','14'=>'iff','15'=>'wbmp','16'=>'xbm',);
        $typeid = 0;
        $imagetype = '';
        if(function_exists('exif_imagetype')) {
            $typeid = exif_imagetype($filename);
            if($typeid > 0 && isset($imagetypes[$typeid])) {
                $imagetype = $imagetypes[$typeid];
                return $imagetype;
            }
        }
        if (function_exists('getimagesize')) {
            $_tmps = getimagesize($filename);
            $typeid = (int) $_tmps[2];
            if($typeid > 0) {
                $imagetype = $imagetypes[$typeid];
                return $imagetype;
            }
        } 
        
        if(($fh = @fopen($filename, "rb"))) {
            $strInfo = unpack("C2chars", fread($fh,2));
            fclose($fh);
            $fileTypes = array(7790=>'exe',7784=>'midi',8297=>'rar',255216=>'jpg',7173=>'gif',6677=>'bmp',13780=>'png',);
            if(isset($fileTypes[intval($strInfo['chars1'] . $strInfo['chars2'])])){
                $imagetype = $fileTypes[intval($strInfo['chars1'] . $strInfo['chars2'])];
                return $imagetype;
            }
        }
        
        //png等使用下面的方法获取扩展名
        $typelist = array(
            array("FFD8FFE1","jpg"),
            array("89504E47","png"),
            array("47494638","gif"),
            array("49492A00","tif"),
            array("424D","bmp"),
            array("41433130","dwg"),
            array("38425053","psd"),
            array("7B5C727466","rtf"),
            array("3C3F786D6C","xml"),
            array("68746D6C3E","html"),
            array("44656C69766572792D646174","eml"),
            array("CFAD12FEC5FD746F","dbx"),
            array("2142444E","pst"),
            array("D0CF11E0","xls/doc"),
            array("5374616E64617264204A","mdb"),
            array("FF575043","wpd"),
            array("252150532D41646F6265","eps/ps"),
            array("255044462D312E","pdf"),
            array("E3828596","pwl"),
            array("504B0304","zip"),
            array("52617221","rar"),
            array("57415645","wav"),
            array("41564920","avi"),
            array("2E7261FD","ram"),
            array("2E524D46","rm"),
            array("000001BA","mpg"),
            array("000001B3","mpg"),
            array("6D6F6F76","mov"),
            array("3026B2758E66CF11","asf"),
            array("4D546864","mid")
        );
        
        if(!file_exists($filename)) throw new \Exception("File $filename not found!");
        
        $file = @fopen($filename, "rb");
        if(!$file) throw new \Exception("Read file $filename refuse!");
        $bin = fread($file, 15); //只读15字节 各个不同文件类型，头信息不一样。
        fclose($file);
        foreach($typelist as $v)
        {
            $blen=strlen(pack("H*", $v[0])); //得到文件头标记字节数
            $tbin=substr($bin, 0, intval($blen)); ///需要比较文件头长度
            if(strtolower($v[0]) == strtolower(array_shift(unpack("H*", $tbin)))){
                $imagetype = $v[1];
                return $imagetype;
            }
        }
        
        return false;
    }
    
    static function prepareDir($dir, $mode = 0777)
    {
        if(!$dir){
            return false;
        }
    
        if (substr(PHP_OS, 0, 3) == 'WIN'){
            $dir = str_replace('/', DIRECTORY_SEPARATOR, $dir);
        }
    
        $stack = array(basename($dir));
    
        $path = null;
        while ( ($d = dirname($dir) ) ) {
            if ( !is_dir($d) ) {
                $stack[] = basename($d);
                $dir = $d;
            } else {
                $path = $d;
                break;
            }
        }
    
        if ( ( $path = realpath($path) ) === false )
            return false;
    
        $created = array();
        for ( $n = count($stack) - 1; $n >= 0; $n-- )
        {
            $s = $path . DIRECTORY_SEPARATOR. $stack[$n];
            if (is_dir($s)){
                continue;
            }
    
            //$oldumask=umask(0);
            if (!mkdir($s, $mode)) {
                for ( $m = count($created) - 1; $m >= 0; $m-- )
                    rmdir($created[$m]);
                return false;
            }
            //umask($oldumask);
            $created[] = $s;
            $path = $s;
        }
        
        return true;
    }
}

?>
