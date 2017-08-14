<?php
header('Access-Control-Allow-Origin:*');
ini_set('date.timezone','Asia/Shanghai');
include_once("upload.php");
// include_once("demo.class.php");
class MessageController{
	public function publish(){
		$data=$_POST;
		$title=$data['title'];
		$content=$data['content'];
		$time1=date("Y-m-d h:i:s");
		$uid=$data['uid'];
		$data2 = upload('file' , 'question_img' , pow(1024,2)*2 , [
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
		$path = $data2[1];
		// if($path){
			$model=new ModelController();
			if(strpos($path,'question_img') !== false){
				$result=$model->exec_insert("INSERT INTO question (title,content,time,uid,question_img,hot) VALUES('{$title}','{$content}','{$time1}','{$uid}','{$path}',0)");
				if ($result) {
					echo ajax_return(200,'发布成功！',$path);
				} else {
					echo ajax_return(303,'发布失败！','');
				}
			}else{
				$result=$model->exec_insert("INSERT INTO question (title,content,time,uid,hot) VALUES('{$title}','{$content}','{$time1}','{$uid}',0)");
				if ($result) {
					echo ajax_return(200,'发布成功！','');
				} else {
					echo ajax_return(303,'发布失败！','');
				}
			}			
	}


	public function show(){//获取数据表所有数据并展示在页面上
		$data=$_POST;
		$start=$data['start'];
		$model=new ModelController();
		$result=$model->query_sql("SELECT * FROM question order by question_id desc   LIMIT $start,50");
		if(result){

			$result2=$model->query_sql("select * from qxz_user where uid in  (select t.uid from (select uid from question order by question_id desc   LIMIT $start,50) as t)");
																			  // select t.id from (select * from table limit 10)as t
			$arr=array($result,$result2);
			echo json_encode($arr);
		}
	}


	public function keyword(){
		$data=$_POST;
		$keyword=$data['keyword'];
		$start=$data['start'];
		$model=new ModelController();
		$result=$model->query_sql("SELECT * FROM question  WHERE title LIKE '%$keyword%' order by question_id desc LIMIT $start,50");// order by question_id desc   LIMIT $start,50");
		if(result){

			$result2=$model->query_sql("select * from qxz_user where uid in  (select t.uid from (select uid from question  WHERE title LIKE '%$keyword%' order by question_id desc   LIMIT $start,50) as t)");
																			  // select t.id from (select * from table limit 10)as t
			$arr=array($result,$result2);
			echo json_encode($arr);
		}
	}
	// public function support(){//删除数据
	// 	$data=$_POST;
	// 	$questionId=$data['questionId'];
	// 	$model=new ModelController();
	// 	$model->exec_delete("DELETE FROM message WHERE id='{$id}'");
	// }
	public function support(){//获取要修改的数据行
		$data=$_POST;
		$question_id=$data['question_id'];
		$model=new ModelController();
		$result=$model->query_sql("SELECT hot FROM question where question_id='{$question_id}'");
		$hot = ++$result[0]['hot'];//$result[0]['hot'];
		if($hot){
			$result=$model->exec_update("UPDATE question SET hot='{$hot}' where question_id='{$question_id}'");	
			if($result){
				echo ajax_return(200,'success！','');
			}else{
				echo ajax_return(200,'error！','');
			}
		}
	}
	public function support_user(){//获取要修改的数据行
		$data=$_POST;
		$uid=$data['uid'];
		$model=new ModelController();
		$result=$model->query_sql("SELECT grade FROM qxz_user where uid='{$uid}'");
		$grade = ++$result[0]['grade'];//$result[0]['hot'];
		
		if($grade){
			$result=$model->exec_update("UPDATE qxz_user SET grade='{$grade}' where uid='{$uid}'");	
			if($result){
				echo ajax_return(200,'success！','');
			}else{
				echo ajax_return(304,'error！','');
			}
		}
	}

	public function answer(){
		$data=$_POST;
		$question_id=$data['question_id'];
		$content=$data['content'];
		$time=date("Y-m-d h:i:s");
		$point_uid=$data['point_id'];
		$uid=$data['uid'];
		$name=$data['name'];
		$answer_nickname=$data['answer_nickname'];
		$model=new ModelController();
		// $result=$model->exec_insert("INSERT INTO answer (question_id,content,time,uid,answer_hot) VALUES('{$question_id}','{$content}','{$time}','{$uid}',0)");
		$result=$model->exec_insert("INSERT INTO answer (question_id,point_uid,name,content,time,uid,answer_nickname,answer_hot) VALUES('{$question_id}','{$point_uid}','{$name}','{$content}','{$time}','{$uid}','{$answer_nickname}',0)");

		if($result){
				echo ajax_return(200,'success！',$name);
		}
	}



	public function showAnswer(){
		$data=$_POST;
		$question_id=$data['question_id'];
		$model=new ModelController();
		$result=$model->query_sql("SELECT * FROM answer where question_id='{$question_id}'");
		// echo $result;
		if($result){
				echo ajax_return(200,'success',$result);
		}
	}
	public function publish_article(){
 		$data=$_POST;
 		$content=$data['content'];
		$time=date("Y-m-d h:i:s");
		$title=$data['title'];
		$uid=$data['uid'];
		$hot=$data['hot'];
		// $collect=$data['collect'];
		$model=new ModelController();
		// $result=$model->exec_insert("INSERT INTO answer (question_id,content,time,uid,answer_hot) VALUES('{$question_id}','{$content}','{$time}','{$uid}',0)");
		$result=$model->exec_insert("INSERT INTO article (title,content,uid,time,hot) VALUES('{$title}','{$content}','{$uid}','{$time}',0)");
								
		if($result){
				echo ajax_return(200,'success！',$result);
		}
	}

		public function get_article(){
 		$data=$_POST;
		$uid=$data['uid'];

		// $collect=$data['collect'];
		$model=new ModelController();
		$result=$model->query_sql("SELECT * FROM article where uid='{$uid}'");
		if($result){
				// echo $result;
				echo ajax_return(200,'success！',$result);
		}
	}
	public function get_question_hot(){
   		$data=$_POST;
		$start=$data['start'];
		$model=new ModelController();
		$result=$model->query_sql("SELECT * FROM question order by hot  desc LIMIT $start,50");
		if(result){

			$result2=$model->query_sql("select * from qxz_user where uid in  (select t.uid from (select uid from question order by question_id    LIMIT $start,50) as t)");
																			  // select t.id from (select * from table limit 10)as t
			$arr=array($result,$result2);
			// echo json_encode($arr);
			echo ajax_return(200,'success！',$arr);
		}
	}

	public function get_user_hot(){
		$data=$_POST;
		$start=$data['start'];
		$model=new ModelController();
		$result=$model->query_sql("SELECT * FROM qxz_user order by grade  desc LIMIT $start,50");
		if(result){
			echo ajax_return(200,'success！',$result);
		}
	}

	public function article_pic(){
		$data=$_POST;
		// $uid=$data['uid'];
		$data2 = upload('file' , 'article_img' , pow(1024,2)*2 , [
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
		$path = $data2[1];
		// if($path){
			$model=new ModelController();
			if(strpos($path,'article_img') !== false){
				// $result=$model->exec_insert("INSERT INTO question (title,content,time,uid,question_img,hot) VALUES('{$title}','{$content}','{$time1}','{$uid}','{$path}',0)");
					echo ajax_return(200,'发布成功！',$path);
			}else {
					echo ajax_return(303,'发布失败！','');
			}			
	}
}


