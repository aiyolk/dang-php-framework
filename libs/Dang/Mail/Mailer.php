<?php

/*
 * 利用php mail发送邮件
 * 暂不支持附件
 * 
 * @author wuqingcheng
 * @date 2013.05.08
 * 
 * 使用方法：

$mailer = new mailer();
$mailer->to($to);
$mailer->to($reply, $fromName);
$mailer->from($from, $fromName);
$mailer->reply($reply, $fromName);
$mailer->subject($subject);
$mailer->body($message);
$mailer->bcc($bcc);
$mailer->cc($cc, $fromName);
$result = $mailer->send();

 */

class Dang_Mail_Mailer
{
    private $_headers = "";
    
    private $_from;
    private $_bcc = "";
    private $_cc = "";
    private $_to = array();
    private $_reply = "";
    private $_body = "";
    private $_isHtml = false;
    
    function __construct() 
    {
        
    }
    
    public function from($email, $name=null)
    {
        $this->_from = "From: ";
        if($name != null){
            $name = "=?UTF-8?B?".base64_encode($name)."?=";
            $this->_from .= $name;
            $this->_from .= " <" . $email . ">\r\n";
        }else{
            $this->_from .= "" . $email . "\r\n";
        }
    }
    
    public function bcc($email, $name=null)
    {
        if($email == ""){
            return false;
        }
        
        $this->_bcc .= "Bcc: ";
        if($name != null){
            $name = "=?UTF-8?B?".base64_encode($name)."?=";
            $this->_bcc .= $name;
            $this->_bcc .= " <" . $email . ">\r\n";
        }else{
            $this->_bcc .= "" . $email . "\r\n";
        }
    }
    
    public function cc($email, $name=null)
    {
        if($email == ""){
            return false;
        }
        
        $this->_cc .= "Cc: ";
        if($name != null){
            $name = "=?UTF-8?B?".base64_encode($name)."?=";
            $this->_cc .= $name;
            $this->_cc .= " <" . $email . ">\r\n";
        }else{
            $this->_cc .= "" . $email . "\r\n";
        }
    }
    
    public function to($email, $name=null)
    {
        $to = "";
        if($name != null){
            $name = "=?UTF-8?B?".base64_encode($name)."?=";
            $to .= $name;
            $to .= " <" . $email . ">\r\n";
        }else{
            $to .= "" . $email . "\r\n";
        }
        
        $this->_to[] = $to;
    }
    
    public function reply($email, $name=null)
    {
        if($email == ""){
            return false;
        }
        
        $this->_reply = "Reply-To: ";
        if($name != null){
            $name = "=?UTF-8?B?".base64_encode($name)."?=";
            $this->_reply .= $name;
            $this->_reply .= " <" . $email . ">\r\n";
        }else{
            $this->_reply .= "" . $email . "\r\n";
        }
    }
    
    public function subject($subject)
    {
        $subject = "=?UTF-8?B?".base64_encode($subject)."?=";
        $this->_subject = $subject;
    }
    
    public function body($body, $isHtml=false)
    {
        $this->_body = $body;
        $this->_isHtml = $isHtml;
    }
    
    public function send()
    {
        $headers = "";
        if($this->_isHtml == true){
            $headers .= "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=utf-8" . "\r\n";
        }
        
        $headers .= $this->_from;
        $headers .= $this->_reply;
        $headers .= $this->_cc;
        $headers .= $this->_bcc;
        
        $result = mail(join(",", $this->_to), $this->_subject, $this->_body, $headers);
        
        return $result;
    }
}

?>