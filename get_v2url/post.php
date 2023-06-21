<?php
// 获取POST请求中的JSON数据
$json = file_get_contents('php://input');

// 解析JSON数据
$data = json_decode($json, true);

// 获取URL参数
$url = $data['url'];

// 使用file_get_contents()函数获取URL网页的内容
$content = file_get_contents($url);

// 输出内容
echo $content;
?>