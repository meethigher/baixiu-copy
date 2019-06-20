<?php
require_once '../../functions.php';
$begin=empty($_GET['p'])?1:intval($_GET['p']);
$step=3;
$begin=($begin-1)*$step;

$sql=sprintf("select comments.*,posts.title as post_title from comments
inner join posts on posts.id=comments.post_id  order by created desc
limit %d,%d;",$begin,$step);
$comments=fetch_count($sql);
$total_pages=fetch_count_one("select count(1) as count from comments;")['count'];
$total_pages=ceil($total_pages/$step);
header("Content-Type:application/json");
echo json_encode(array('totalPages' => $total_pages, 'comments'=>$comments));