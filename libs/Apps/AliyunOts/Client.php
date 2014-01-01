<?php

namespace Apps\AliyunOts;

class Client
{
    function __construct()
    {
        //date_default_timezone_set('GMT');
        $date = date("D M j H:i:s Y", time()-3600*8);
        //$date = date("D, j M Y h:i:s e");

        $this->_accessParam = array(
            'APIVersion' => 1,
            'Date' => $date,
            'OTSAccessKeyId' => "OyJ5MvwJdUjTullg",
            'SignatureMethod' => "HmacSHA1",
            'SignatureVersion' => "1",
        );
    }

    private function http_build_query($param)
    {
        $str = $separator = "";
        foreach($param as $key=>$val){
            $str .= $separator.$key."=".rawurlencode($val);
            $separator = "&";
        }

        return $str;
    }

    private function signature($action, $param)
    {
        $signatureParam = array_merge($this->_accessParam, $param);
        ksort($signatureParam);
        print_r($signatureParam);

        $signatureString = "/{$action}"."\n".$this->http_build_query($signatureParam);
        $signature = base64_encode(hash_hmac("sha1", $signatureString, "RYekrFHpgWrKTbpveBEcCsoLfm3viv", true));

        $param = array_merge($param, $this->_accessParam);
        $param['Signature'] = $signature;
        print_r($param);

        return $param;
    }

    public function send($action, $param)
    {
        $param = $this->signature($action, $param);

        $url = "http://ots.aliyuncs.com/{$action}?".$this->http_build_query($param);
        //$result = file_get_contents($url);
        $curl = new \Common\Download\Curl();
        $result = $curl->get($url);
        print_r($curl);
        print_r($result);

        return $result;
    }

    public function putData($action, $param)
    {
        $param = $this->signature($action, $param);
        print_r($param);
        $url = "http://ots.aliyuncs.com/{$action}?".$this->http_build_query($param);
        echo $url;
        $result = file_get_contents($url);

        return $result;
    }
}

?>
