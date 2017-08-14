<?php

include 'upload.php';

$data = upload('file' , 'upload' , pow(1024,2)*2 , [
			'image/jpeg',
			'image/png',
			'image/gif',
			'image/wbmp',
			'image/bmp',
		],[
			'jpeg',
			'png',
			'gif',
			'jpg',
			'bmp'
		],true);
//var_dump($data);

$link = mysqli_connect('localhost' , 'root' ,'root');
if (!$link) {
	exit('失败');
}
$db = mysqli_select_db($link , 'jk_user');

if (!$db) {
	exit('db -- error!!!!!');
}

$path = $data[1];
$type = $data[2];
$ctime = time();

//准备sql语句


$sql = "insert into jk_user(u_img) values('$path')";

$res = mysqli_query($link , $sql);

if ($res && mysqli_affected_rows($link)) {
	echo '上传成功';
} else {
	echo '上传失败';
}

mysqli_close($link);































