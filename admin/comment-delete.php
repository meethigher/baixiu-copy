<?php  
require_once '../functions.php';
header("Content-Type:application/json");
// 如果传过来id的话，要防止sql注入;
$id=$_GET['id'];
$btn=$_GET['b'];
if(empty($btn)){
	$sql="delete from comments where id in ({$id});";
	$rows=insert($sql);
	echo json_encode($rows>0);
}
elseif ($btn=='1') {
	$sql="update comments set status='approved' where id in ({$id});";
	$rows=insert($sql);
	echo json_encode($rows>0);
}
else {
	$sql="update comments set status='rejected' where id in ({$id})";
	$rows=insert($sql);
	echo json_encode($rows>0);
}

?>