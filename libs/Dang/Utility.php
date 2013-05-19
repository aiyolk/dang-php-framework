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
        mb_internal_encoding("UTF-8");
        $tags = mb_ereg_replace("、|；", " ", $tags);
        $tags = preg_replace("/[;\/]/", " ", $tags);
        $tags = trim($tags);

        return $tags;
    }

}

?>
