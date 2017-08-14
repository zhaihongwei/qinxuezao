<?php
include_once("upload.php");
class LoginController{

	public function login(){//控制登录
		$data=$_POST;
		$username=$data['username'];
		$password=$data['password'];
		$model=new ModelController();
		$result=$model->query_sql("SELECT * FROM qxz_user WHERE username='{$username}'");
		//SELECT jk_user_collection.collection FROM jk_user JOIN jk_user_collection ON jk_user.uid=jk_user_collection.uid;
		if (empty($result)) {
			echo ajax_return(303,'用户名不存在！','');
		} else {
			$arr=$result[0];
			$password=$this->md($password);
			if ($arr['password']==$password) {
//				echo $arr['uid'];
			echo ajax_return(200,'登录成功！',$arr);

			} else {
				echo ajax_return(303,'密码不正确！','');
			}
		}
	}
	public function sign(){//控制注册
		$data=$_POST;
		$username=$data['username'];
		$password=$data['password'];
		// $motto=$data['motto'];
		$model=new ModelController();
		$result=$model->query_sql("SELECT * FROM qxz_user WHERE username='{$username}'");
		if (empty($result)) {		
				$password=$this->md($password);
				$res=$model->exec_insert("INSERT INTO qxz_user(username,password,grade) VALUES('{$username}','{$password}',0)");
				if ($res) {
					echo ajax_return(200,'注册成功！','');
				}else{
					echo ajax_return(400,'注册失败！','');
					}
		} else {
			echo ajax_return(303,'该用户名已经被注册！','');
		}
	}	
		
	public function sendPic(){
		$d=$_POST;
		$uid=$d['uid'];
		// echo $uid;
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
		$path = $data[1];

		if($path){
			$model=new ModelController();
			$res2=$model->exec_insert("UPDATE qxz_user SET uimg='{$path}' where uid='{$uid}'");	
			// echo $res2;
			if ($res2||$res2==0) {
						echo ajax_return(200,'上传成功！',$path);
			} else {
						unlink($path);
						echo ajax_return(304,'上传失败！','');
			}	

			// echo ajax_return(200,'上传成功！',$path);

		}else{
			 echo ajax_return(200,'上传失败！','');
		}
	}	
	
		public function upUser(){
		$data=$_POST;
		$uid=$data['uid'];
		$sex=$data['sex'];
		$motto=$data['motto'];
		$nickname=$data['nickname'];
		$model=new ModelController();
		$result=$model->exec_update("UPDATE qxz_user SET sex='{$sex}',motto='{$motto}',nickname='{$nickname}' WHERE uid='{$uid}'");
		if ($result) {
			echo ajax_return(200,'修改成功！','');
		} else {
			echo ajax_return(303,'修改失败！','');
		}
	}


	
	public function upMotto(){
		$data=$_POST;
		$uid=$data['uid'];
		$motto=$data['motto'];
		$model=new ModelController();
		$result=$model->exec_update("UPDATE qxz_user SET motto='{$motto}' WHERE uid='{$uid}'");
		if ($result) {
			echo ajax_return(200,'修改成功！','');
		} else {
			echo ajax_return(303,'修改失败！','');
		}
	}


	
	public function md($con){//密码加密
		$result=md5(md5($con).'beicai');
		return $result;
	}
	public function find(){//查找用户名是否存在
		$data=$_POST;
		$user=$data['username'];
		$model=new ModelController();
		$result=$model->query_sql("SELECT * FROM login WHERE username='{$user}'");
		if (empty($result)) {		
			echo ajax_return(200,'用户名可用！','');
		} else {
			echo ajax_return(303,'用户名已存在！','');
		}
	}

	// public function rollback(){
	// 	$data=$_POST;
	// 	$path=$data['path'];
	// 	unlink($path);
	// }


		// 	public function upSex(){
	// 	$data=$_POST;
	// 	$uid=$data['uid'];
	// 	$sex=$data['sex'];
	// 	$model=new ModelController();
	// 	$result=$model->exec_update("UPDATE qxz_user SET sex='{$sex}' WHERE uid='{$uid}'");
	// 	if ($result) {
	// 		echo ajax_return(200,'修改成功！','');
	// 	} else {
	// 		echo ajax_return(303,'修改失败！','');
	// 	}
	// }
	// public function upNickname(){
	// 	$data=$_POST;
	// 	$uid=$data['uid'];
	// 	$nickname=$data['nickname'];
		
	// 	$model=new ModelController();
	// 	$result=$model->exec_update("UPDATE qxz_user SET nickname='{$nickname}' WHERE uid='{$uid}'");
	// 	if ($result) {
	// 		echo ajax_return(200,'修改成功！','');
	// 	} else {
	// 		echo ajax_return(303,'修改失败！','');
	// 	}
	// }
}