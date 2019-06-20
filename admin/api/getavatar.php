<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/22 0022
 * Time: 14:28
 */
require_once '../../config.php';
$conn=mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
if(!$conn) exit("连接数据库失败");
if(empty($_GET['avatar'])) exit("缺少必要的参数");
$avatar=$_GET['avatar'];
$sql="select avatar from users where email='{$avatar}' limit 1";
if(!$query=mysqli_query($conn,$sql)) exit("查询失败");
echo mysqli_fetch_assoc($query)['avatar'];