<?php
require_once '../functions.php';
get_current();
function add_category()
{
    global $msg,$success;
    if (empty($_POST['name'])) {
        $msg = "请输入分类名称";
        return;
    }
    if (empty($_POST['slug'])) {
        $msg = "请输入slug";
        return;
    }
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    if (!insert("insert into categories values(null,'{$slug}','{$name}');")) {
        $msg = '添加失败';
        return;
    }
    $success='添加成功';
}
function edit_category(){
    global $msg,$success;
    if(empty($_POST['name'])){
        $msg='请输入分类名称';
        return;
    }
    if(empty($_POST['slug'])){
        $msg='请输入与slug';
        return;
    }
    $name=$_POST['name'];
    $slug=$_POST['slug'];
    $sql="update categories set name='{$name}',slug='{$slug}' where id={$_GET['id']};";
    $affected_rows=insert($sql);
    if($affected_rows==0){
        $msg='数据未发生改变';
        return;
    }
    $success='更新成功';
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if(!empty($_GET['id']))
        edit_category();
    else
        add_category();
}
if(!empty($_GET['id'])){
    $sql="select * from categories where id={$_GET['id']} limit 1;";
    $edit_current_category=fetch_count_one($sql);
}
$categories = fetch_count('select * from categories;');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>Categories &laquo; Admin</title>
    <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
    <link rel="stylesheet" href="/static/assets/css/admin.css">
    <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
<script>NProgress.start()</script>

<div class="main">
    <?php include 'inc/navbar.php'; ?>
    <div class="container-fluid">
        <div class="page-title">
            <h1>分类目录</h1>
        </div>
        <!-- 有错误信息时展示 -->
        <?php if (!empty($msg)): ?>
            <div class="alert alert-danger">
                <strong>错误！</strong><?php echo $msg; ?>
            </div>
        <?php elseif(!empty($success)): ?>
            <div class="alert alert-success">
                <strong>成功！</strong><?php echo $success;?>
            </div>
        <?php endif ?>
        <div class="row">
            <?php if (!isset($edit_current_category)): ?>
                <div class="col-md-4">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <h2>添加新分类目录</h2>
                    <div class="form-group">
                        <label for="name">名称</label>
                        <input id="name" class="form-control" name="name" type="text" placeholder="分类名称"
                               autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="slug">别名</label>
                        <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
                        <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">添加</button>
                    </div>
                </form>
            </div>
            <?php else: ?>
                <div class="col-md-4">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF'].'?id='.$edit_current_category['id']; ?>">
                    <h2>编辑分类《<?php echo $edit_current_category['name']; ?>》</h2>
                    <div class="form-group">
                        <label for="name">名称</label>
                        <input id="name" class="form-control" name="name" type="text" value="<?php echo $edit_current_category['name']; ?>" placeholder="分类名称"
                               autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="slug">别名</label>
                        <input id="slug" class="form-control" name="slug" type="text" value="<?php echo $edit_current_category['slug']; ?>" placeholder="slug">
                        <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">更新</button>
                    </div>
                </form>
            </div>
            <?php endif ?>
            
            <div class="col-md-8">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th class="text-center" width="40"><input type="checkbox"></th>
                        <th>名称</th>
                        <th>Slug</th>
                        <th class="text-center" width="100">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($categories as $item): ?>
                        <tr>
                            <td class="text-center"><input type="checkbox" data-id="<?php echo $item['id']; ?>"></td>
                            <td><?php echo $item['name']; ?></td>
                            <td><?php echo $item['slug']; ?></td>
                            <td class="text-center">
                                <a href="<?php echo $_SERVER['PHP_SELF'].'?id='.$item['id']; ?>" class="btn btn-info btn-xs">编辑</a>
                                <a href="<?php echo 'category-delete.php?id=' . $item['id']; ?>"
                                   class="btn btn-danger btn-xs">删除</a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
                <div class="page-action">
                    <!-- show when multiple checked -->
                    <a id="btn_delete" class="btn btn-danger btn-sm" href="/admin/category-delete.php" style="display: none">批量删除</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $current = "categories"; ?>
<?php include 'inc/slidebar.php'; ?>
<script src="/static/assets/vendors/jquery/jquery.min.js"></script>
<script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
<script>NProgress.done()</script>
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
