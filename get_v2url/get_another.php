<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class CKCloudAPI {
    public $base_url;
    public $token = null;
    public $email = null;
    public $password = null;

    public function __construct($email = null, $password = null) {
        $this->base_url = "https://www.ckcloud.shop/api/v1";
        $this->email = $email;
        $this->password = $password;
    }

    public function register() {
        $url = $this->base_url . "/passport/auth/register";
        $data = array("email" => $this->email, "password" => $this->password);
        try {
            $client = new Client([
                'verify' => false // 禁用SSL证书验证
            ]);
            $response = $client->post($url, ['form_params' => $data]);
            $response->getBody()->getContents();
        } catch (GuzzleHttp\Exception\RequestException $e) {
            $error_msg = $e->getMessage();
            echo "Registration failed: " . $error_msg . "\n";
            return false;
        }
        return true;
    }

    public function login() {
        $url = $this->base_url . "/passport/auth/login";
        $data = array("email" => $this->email, "password" => $this->password);
        try {
            $client = new Client([
                'verify' => false // 禁用SSL证书验证
            ]);
            $response = $client->post($url, ['form_params' => $data]);
            $body = $response->getBody()->getContents();
            $json = json_decode($body, true);
            $this->token = $json["data"]["token"];
        } catch (GuzzleHttp\Exception\RequestException $e) {
            $error_msg = $e->getMessage();
            echo "Login failed: " . $error_msg . "\n";
            $this->token = null;
            return false;
        }
        return true;
    }

    public function get_token() {
        return $this->token;
    }
}

// 生成随机邮箱
$email = generateRandomEmail();

// 生成随机密码
$password = generateRandomPassword();

// 生成随机邮箱的函数
function generateRandomEmail() {
    $domains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com'];
    $username = generateRandomString(8);
    $domain = $domains[array_rand($domains)];
    return $username . '@' . $domain;
}

// 生成随机密码的函数
function generateRandomPassword() {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    for ($i = 0; $i < 8; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

// 生成随机字符串的函数
function generateRandomString($length) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
$api = new CKCloudAPI($email, $password);
if ($api->register() && $api->login()) {
    $token = $api->get_token();
}
$another_url = "https://www.jcbb.info/api/v1/client/subscribe?token=" . $token;
$html = file_get_contents($another_url);
$crawler = new Crawler($html);

// 使用Crawler对象获取网页内容
$content = $crawler->text();

// 输出获取到的内容
echo $content;
?>