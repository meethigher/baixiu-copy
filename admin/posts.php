<?php require_once '../functions.php'; 
get_current();
//$posts=fetch_count("select * from posts;");
/**
 * 转换状态
 * @param  string $status 英文状态
 * @return string         中文状态
 */
function convert_status($status){
  $dict=array('published' =>'已发布' ,'drafted'=>'草稿','trashed'=>'回收站');
  return isset($dict[$status])?$dict[$status]:'未知状态';
}
/**
 * 转换时间
 * @param  [type] $created [description]
 * @return [type]          [description]
 */
function convert_date($created){
  $timestamp=strtotime($created);
  return date('Y年m月d日<b\r>H:i:s',$timestamp);
}

//筛选
//================================
$categories=fetch_count('select * from categories;');
$where='1=1';
$search='';
if(isset($_GET['c'])&&$_GET['c']!='all'){
  $where.=" and categories.id={$_GET['c']}";
  $search.="&c=".$_GET['c'];
}
if(isset($_GET['s'])&&$_GET['s']!='all'){
  $where.=" and posts.`status`='{$_GET['s']}'";
  $search.="&s=".$_GET['s'];
}
//================================

//处理分页代码
//================================
$page=empty($_GET['p'])?1:(int)$_GET['p'];
$size=10;

$total=fetch_count_one("select count(1) as total from posts
  inner join categories on categories.id=posts.category_id
  inner join users on users.id=posts.user_id
  where {$where};")['total'];
$visibility=4;
$total_pages=ceil($total/$size);

//传过来的页数过大或者过小
$page=$page>$total_pages?$total_pages:$page;
$page=$page<1?1:$page;
$page_begin=($page-1)*10;

$begin=$page-$visibility/2;
$end=$page+$visibility/2;
if($total_pages>$visibility){//页数多
  $begin=$begin<1?1:$begin;
  $end=$begin+$visibility;
  $end=$end>$total_pages?$total_pages:$end;
  $begin=$end-$visibility;
}
else{//页数过少
  $begin=$begin<1?1:$begin;
  $end=$end>$total_pages?$total_pages:$end;
}
//================================

$posts=fetch_count("select
  posts.id,
  posts.title,
  users.nickname,
  categories.`name`,
  posts.created,
  posts.`status`
  from posts
  inner join categories on categories.id=posts.category_id
  inner join users on users.id=posts.user_id
  where {$where}
  order by created desc
  limit {$page_begin},{$size};");
  ?>
  <!DOCTYPE html>
  <html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <title>Posts &laquo; Admin</title>
    <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
    <link rel="stylesheet" href="/static/assets/css/admin.css">
    <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
  </head>
  <body>
    <script>NProgress.start();</script>
    <div class="main">
      <?php include 'inc/navbar.php'; ?>
      <div class="container-fluid">
        <div class="page-title">
          <h1>所有文章</h1>
          <a href="post-add.php" class="btn btn-primary btn-xs">写文章</a>
        </div>
        <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <form class="form-inline" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <select name="c" class="form-control input-sm">
            <option value="all">所有分类</option>
            <?php foreach ($categories as $item): ?>
              <option value="<?php echo $item['id']; ?>"<?php echo isset($_GET['c'])&&$item['id']==$_GET['c']?' selected':'';?>><?php echo $item['name']; ?></option>
            <?php endforeach ?>
          </select>
          <select name="s" class="form-control input-sm">
            <option value="all">所有状态</option>
            <option value="drafted"<?php echo isset($_GET['s'])&&$_GET['s']=='drafted'?' selected':''; ?>>草稿</option>
            <option value="published"<?php echo isset($_GET['s'])&&$_GET['s']=='published'?' selected':''; ?>>已发布</option>
            <option value="trashed"<?php echo isset($_GET['s'])&&$_GET['s']=='trashed'?' selected':''; ?>>回收站</option>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <a id="btn_delete" class="btn btn-danger btn-sm" href="/admin/post-delete.php" style="display: none">批量删除</a>
        <ul class="pagination pagination-sm pull-right">
          <li><a href="/admin/posts.php?p=1<?php echo $search; ?>">首页</a></li>
          <?php for ($i=$begin;$i<=$end;$i++): ?>
            <li<?php echo $i==$page?' class="active"':''; ?>><a href="/admin/posts.php?p=<?php echo $i.$search; ?>"><?php echo $i; ?></a></li>
          <?php endfor ?>
          <li><a href="/admin/posts.php?p=<?php echo $total_pages.$search; ?>">尾页</a></li>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($posts as $item): ?>
            <tr>
              <td class="text-center"><input type="checkbox" data-id="<?php echo $item['id']; ?>"></td>
              <td><?php echo $item['title']; ?></td>
              <td><?php echo $item['nickname']; ?></td>
              <td><?php echo $item['name']; ?></td>
              <td class="text-center"><?php echo convert_date($item['created']); ?></td>
              <td class="text-center"><?php echo convert_status($item['status']); ?></td>
              <td class="text-center">
                <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
                <a href="/admin/post-delete.php?id=<?php echo $item['id']; ?>" class="btn btn-danger btn-xs">删除</a>
              </td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>
  <div id="loading">
    <div class="pacman">
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
    </div>
  </div>
  <?php $current="posts"; ?>
  <?php include 'inc/slidebar.php'; ?>
  <script src="/static/assets/vendors/jquery/jquery.min.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done();$("#loading").fadeOut();</script>
  <script>
    $(function () {
        let $tbodyInput = $("tbody input");
        let $btnDelete = $("#btn_delete");
        let $theadInput = $("thead input");
        let $tableInput = $("table input");
        let array=[];
        $theadInput.on("change", function () {
            $tbodyInput.prop('checked', $(this).prop('checked'));
            if($(this).prop('checked')){
                array=[];
                $tbodyInput.each(function (i,ele){
                    array.push($(ele).data('id'));
                })
            }else{
                array=[];
            }
            array.length?$btnDelete.fadeIn():$btnDelete.fadeOut();
            $btnDelete.prop('search','?id='+array);
        });
        $tbodyInput.on("change", function () {
            let flagAll = true;
            let id=$(this).data('id');
            $tbodyInput.each(function (i, item) {
                if (!$(item).prop('checked'))
                    flagAll = false;
            });
            if($(this).prop('checked')){
                array.push(id);
            }else{
                array.splice(array.indexOf(id),1);
            }
            array.length?$btnDelete.fadeIn():$btnDelete.fadeOut();
            flagAll ? $theadInput.prop('checked', true) : $theadInput.prop('checked', false);
            $btnDelete.prop('search','?id='+array);
        });

    });
</script>
</body>
</html>
