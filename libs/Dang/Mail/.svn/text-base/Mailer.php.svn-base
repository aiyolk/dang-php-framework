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

/*
$fromName = "吴庆成";
$from = "wuqingcheng@dangdang.com"; //senders e-mail adress 
$reply = "381766010@qq.com";
$to = "wqc200@gmail.com"; //recipient 
$subject = "关于短信发送的相关数据"; //subject 
$bcc = "wuqingcheng@dangdang.com";
$cc = "wuqingcheng@dangdang.com";
$message = "
<html>
<head>
<title>HTML email</title>
</head>
<body>
<p>This email contains HTML Tags!</p>
<table>
<tr>
<th>Firstname</th>
<th>Lastname</th>
</tr>
<tr>
<td>John</td>
<td>中文名字</td>
</tr>
</table>
</body>
</html>
";

$mailer = new mailer();
$mailer->to($to);
$mailer->to($reply, $fromName);
$mailer->from($from, $fromName);
$mailer->reply($reply, $fromName);
$mailer->subject($subject);
$mailer->body($message, true);
$mailer->bcc($bcc);
$mailer->cc($cc, $fromName);
$result = $mailer->send();
print_r($result);
 */

?>