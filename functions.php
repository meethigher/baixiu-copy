<?php  
/**
 * 封装一些公用的函数
 */
require_once("config.php");
session_start();

/**
 * 与数据库建立连接
 * @return [type] [description]
 */
function conn_query($sql){
	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$conn) exit("数据库损坏");
	$query=mysqli_query($conn,$sql);
    if(!$query){
    	return false;
    }
    $obj = array('conn' =>$conn ,'query'=>$query );
    return $obj;
}
/**
 * 获取当前登录用户的信息，如果没有获取到，则自动跳转到登录页面
 * @return [type] [description]
 */
function get_current(){
	if(empty($_SESSION['logged_in_user'])){
		header("Location:/admin/login.php");
		exit();//没有必要再执行下面的程序
	}
	return $_SESSION['logged_in_user'];
}

/**
 * 通过数据库查询获取所有数据
 * @return [type] [description]
 */
function fetch_count($sql){
	$fetch=conn_query($sql);
	if(!$fetch) return false;
    $result=null;
    while($row=mysqli_fetch_assoc($fetch['query'])){
    	$result[]=$row;
    }
    mysqli_free_result($fetch['query']);
    mysqli_close($fetch['conn']);
    return $result;
}
/**
 * 通过数据库查询获取一条数据
 * @return [type] [description]
 */
function fetch_count_one($sql){
	return isset(fetch_count($sql)[0])?fetch_count($sql)[0]:null;
}

/**
 * 向数据库中插入内容
 * @param  [type] $sql [description]
 * @return [type]      [description]
 */
function insert($sql){
	$fetch=conn_query($sql);
	if(!$fetch) return false;
    $affected_rows=mysqli_affected_rows($fetch['conn']);
    mysqli_close($fetch['conn']);
    return $affected_rows;
}
