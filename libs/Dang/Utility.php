<?php

/**
 * 通用工具包
 *
 * @author wuqingcheng
 * @date 2013.05.18
 * @email wqc200@gmail.com
 */

namespace Dang;

class Utility
{
    static function buildTags($tags)
    {
        //$tags = "以不断;创新/的经营(理念)为先导，建立[学习型]组织和优势团队，引进专业人才、创建开放式的企业管理模式";
        $tags = preg_replace("/(([\x{4e00}-\x{9fa5}]),)/usi", "\\2 ", $tags);
        $tags = preg_replace("/(,([\x{4e00}-\x{9fa5}]))/usi", " \\2", $tags);
        //$tags = preg_replace("/([0-9a-z]),([^0-9a-z])/usi", "\\1 \\3", $tags);
        //$tags = preg_replace("/([^0-9a-z]),([0-9a-z])/usi", "\\1 \\3", $tags);
        mb_internal_encoding("UTF-8");
        mb_regex_encoding("UTF-8");
        $tags = mb_ereg_replace("、|；|;|，|（|\(|\)|）|\[|\]|\/", " ", $tags);
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
}

?>
