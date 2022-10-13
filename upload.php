<?php

/*
 * PHP upload demo for Editor.md
 *
 * @FileName: upload.php
 * @Auther: Pandao
 * @E-mail: pandao@vip.qq.com
 * @CreateTime: 2015-02-13 23:20:04
 * @UpdateTime: 2015-02-14 14:52:50
 * Copyright@2015 Editor.md all right reserved.
 */

//header("Content-Type:application/json; charset=utf-8"); // Unsupport IE
header("Content-Type:application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");

require("editormd.uploader.class.php");

error_reporting(E_ALL & ~E_NOTICE);

$base_dir = dirname(__FILE__, 3) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
if (!is_dir($base_dir)){
    mkdir($base_dir);
}
$date_dir = date('Ymd');
$savePath = $base_dir . $date_dir . DIRECTORY_SEPARATOR;
if (!is_dir($savePath)) {
    mkdir($savePath);
}
$saveURL = '/usr/uploads/' . $date_dir . '/';


$formats = array(
    'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp')
);

$name = 'editormd-image-file';

if (isset($_FILES[$name])) {
    $imageUploader = new EditorMdUploader($savePath, $saveURL, $formats['image'], true,'his');  // Ymdhis表示按日期生成文件名，利用date()函数

    $imageUploader->config(array(
        'maxSize' => 10240,
        'cover' => false         // 是否覆盖同名文件，默认为true
    ));

    if ($imageUploader->upload($name)) {
        $imageUploader->message('上传成功！', 1);
    } else {
        $imageUploader->message('上传失败！', 0);
    }
}
?>