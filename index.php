<?php
//单入口文件
header('Access-Control-Allow-Origin:*');
include_once("function.php");
$C=isset($_GET['C'])?$_GET['C']:'Index';
$C.='Controller';
$M=isset($_GET['M'])?$_GET['M']:'index';
$obj=new $C();// $obj=new $loginController();
$obj->$M();





















