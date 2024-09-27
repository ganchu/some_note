<?php
error_reporting(E_ALL);  
ini_set('display_errors', 1);
function getEmailFromTxt($lineNumber)
{
    $file = 'email.txt';
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if ($lineNumber >= 1 && $lineNumber <= count($lines)) {
        $line = $lines[$lineNumber - 1];
        $credentials = explode(' ', $line);

        if (count($credentials) === 2) {
            return $credentials[0];
        }
    }

    return null;
}


// 通过IMAP登录邮箱并获取邮箱正文中的验证码
function getVerificationCode($lineNumber)
{

    $file = 'email.txt';
    //格式为： 邮箱 密码
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if ($lineNumber >= 1 && $lineNumber <= count($lines)) {
        $line = $lines[$lineNumber - 1];
        $credentials = explode(' ', $line);

        // 邮箱登录配置
        $hostname = '{outlook.office365.com:993/imap/ssl}INBOX'; // 根据您的邮箱提供商进行配置
        $username = $credentials[0];
        $password = $credentials[1]; // 获取邮箱密码，这里需要您实现一个方法来读取密码

        // 连接到邮箱服务器
        $inbox = imap_open($hostname, $username, $password) or die('Cannot connect to mailbox: ' . imap_last_error());
       
        // 获取邮箱中的邮件数量
        $count = imap_num_msg($inbox);
        $emails = imap_search($inbox,'ALL');  
        if ($emails) {  
            $latestEmail = end($emails);
            $overview = imap_fetch_overview($inbox, $latestEmail, 0);  
            $message = imap_fetchbody($inbox, $latestEmail, 1);
        }
        $decodedMessage = base64_decode($message);
        $dom = new DOMDocument();
        $dom->loadHTML($decodedMessage);

        $xpath = new DOMXPath($dom);
        $codeElement = $xpath->query('//b')->item(0);
        $verificationCode = $codeElement->nodeValue;

        echo $verificationCode;

        // var_dump($message) ;
        // 打印提取到的中文内容  
        // echo '中文内容: ' . implode(', ', $matches[0]) . '<br><br>';  

        // 如果没有找到验证码邮件，返回空
        // return '';
    }
}
function setpwd($lineNumber,$pwd,$name){

    $file = 'email.txt';
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if ($lineNumber >= 1 && $lineNumber <= count($lines)) {
        $line = $lines[$lineNumber - 1];
        $credentials = explode(' ', $line);

        if (count($credentials) === 2) {
            $filename = 'pwd.txt'; // txt文件名
            $newPassword =$credentials[0].'----'.$credentials[1].'----'.$pwd/* .'----'.$name */; // 新密码

            // 读取原始文件内容
            $fileContent = file_get_contents($filename);

            // 将新密码追加到文件内容末尾
            $fileContent .= "\n" . $newPassword;

            // 将更新后的文件内容写入文件
            file_put_contents($filename, $fileContent);

            echo '新密码已成功追加到txt文件中。';
        }
    }
}

// 调用方法示例
$line = @$_GET['line']; // 通过API请求获取参数
$lineoremail = @$_GET['lineoremail'];
$pwd = @$_GET['pwd'];
$name = @$_GET['name'];
if(!empty($pwd)&&!empty($line)) {
    $new = setpwd($line,$pwd,$name);
    return $new;
}elseif (!empty($line)) {
    $email = getEmailFromTxt($line);
    echo $email;
}elseif(!empty($lineoremail)) {
    $verificationCode = getVerificationCode($lineoremail);
    return $verificationCode;
}

