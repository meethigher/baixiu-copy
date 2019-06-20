<?php  
require_once '../functions.php';
if(empty($_GET)) header("Location:/admin");
// 如果传过来id的话，要防止sql注入;
$id=$_GET['id'];
$sql="delete from categories where id in ({$id});";
if(!insert($sql)) exit("失败");
header("Location:/admin/categories.php");
?>