<?php

/**
 * Mvc 工具包
 *
 * @author wuqingcheng
 * @date 2013.04.22
 * @email wqc200@gmail.com
 */
class Dang_Mvc_Utility
{
    static function paramUrlToMvc($param)
    {
        $param = preg_replace("/[_-]([a-z]){1}/e",
             "strtoupper('\\1')",
             $param);
        $param = preg_replace("/[_-]([0-9]){1}/e",
             "\\1",
             $param);

        return $param;
    }

    static function paramMvcToUrl($param)
    {
        $param = preg_replace("/([a-z0-9]{1})([A-Z]){1}/e",
             "strtolower('\\1-\\2')",
             $param);
        $param = strtolower($param);
        
        return $param;
    }

    /*
     * 将数组转换成url字符串
     * 
     * 参考：http_build_query
     */
    static function appendParams($array, $parent='')
    {
        $params = array();
        foreach ($array as $k => $v)
        {
            if (is_array($v))
                $params[] = append_params($v, (empty($parent) ? urlencode($k) : $parent . '[' . urlencode($k) . ']'));
            else
                $params[] = (!empty($parent) ? $parent . '[' . urlencode($k) . ']' : urlencode($k)) . '=' . urlencode($v);
        }

        $sessid = session_id();
        if (!empty($parent) || empty($sessid))
            return implode('&', $params);

        // Append the session ID to the query string if we have to.
        $sessname = session_name();
        if (ini_get('session.use_cookies'))
        {
            if (!ini_get('session.use_only_cookies') && (!isset($_COOKIE[$sessname]) || ($_COOKIE[$sessname] != $sessid)))
                $params[] = $sessname . '=' . urlencode($sessid);
        }
        elseif (!ini_get('session.use_only_cookies'))
            $params[] = $sessname . '=' . urlencode($sessid);

        return implode('&', $params);
    }

}

?>
