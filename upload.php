<?php
/*
*@param $key string
*@param $path string
*@param $maxSize int 
*@param $allowMime array
*@param $key string
*@param $key string
*@param $key string
*return array['状态'，路径 ，类型] 
*
*/
ini_set('date.timezone','Asia/Shanghai');
function upload($key , $path , $maxSize , $allowMime , $allowSubFix , $isRand = false)
{
	$error = $_FILES[$key]['error'];
	
	//判断错误号
	if ($error) {
		switch ($error) {
			case 1:
				$msg = '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值';
				break;
			case 2:
				$msg = '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值';
				break;
			case 3:
				$msg = '文件只有部分被上传';
				break;
			case 4:
				$msg = '没有文件被上传';
				break;
			case 6:
				$msg = '找不到临时文件夹';
				break;
			case 7:
				$msg = '文件写入失败';
				break;
		}
		return [0,$msg];
	}
	//判断大小
	if ($_FILES[$key]['size'] > $maxSize) {
		return [0 , '超过自定义大小'];
	}
	
	//判断mime 后缀
	
	if (!in_array($_FILES[$key]['type'] , $allowMime)) {
		return [0 , '不准许的Mime类型'];
	}	
	//判断后缀名
	$info = pathinfo($_FILES[$key]['name']);
	//得到后缀名
	$subFix = $info['extension'];
	if (!in_array($subFix , $allowSubFix)) {
		return [0 , '不准许的Mime类型'];
	}	
	//是否启用随机文件名
	if ($isRand) {
		$newName = uniqid().'.'.$subFix;
	} else {
		$newName = $_FILES[$key]['name'];
	}	
	//拼接路径
	$path = rtrim($path , '/').'/';
	$path = $path . date('Y/m/d').'/';
	//创建目录
	if (!file_exists($path)) {
		mkdir($path , 0777 , true);
	}	
	//判断是否是上传文件
	if (!is_uploaded_file($_FILES[$key]['tmp_name'])) {
		return [0 , '不是长传文件'];
	}
	//移动文件
	if (!move_uploaded_file($_FILES[$key]['tmp_name'],$path.$newName)) {
		return [0 , '文件上传失败'];
	} else {
		return [1 , $path.$newName , $_FILES[$key]['type']];
	}
}










