<?php
//载入配置文件
include_once '../config.php';
session_start();

function login()
{
    global $msg;
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$conn) exit("数据库损坏");
    if (empty($_POST['email'])) {
        $msg = '请输入邮箱';
        return;
    }
    if (empty($_POST['password'])) {
        $msg = '请输入密码';
        return;
    }
    $query = mysqli_query($conn, "select * from users where email='{$_POST['email']}' limit 1;");
    if (!$query) exit('数据库损坏');
    $user = mysqli_fetch_assoc($query);
    if ($_POST['password'] != $user['password']) {
        $msg = '密码错误';
        return;
    }
    header("Location:/admin/index.php");
    $_SESSION['logged_in_user'] = $user;
}

if ($_SERVER['REQUEST_METHOD'] == "POST")
    login();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>Sign in &laquo; Admin</title>
    <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/static/assets/css/admin.css">
    <link rel="stylesheet" href="/static/assets/vendors/animate/animate.css">
    <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
    <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
    <script src="/static/assets/vendors/jquery/jquery.min.js"></script>
</head>
<body>
<div class="login">
    <form class="login-wrap<?php if (isset($msg)) echo ' rubberBand animated'; ?>"
          action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <img id="avatar" class="avatar" src="/static/assets/img/default.png">
        <!-- 有错误信息时展示 -->
        <?php if (isset($msg)): ?>
            <div class="alert alert-danger">
                <strong>错误！</strong> <?php echo $msg; ?>
            </div>
        <?php endif ?>
        <div class="form-group">
            <label for="email" class="sr-only">邮箱</label>
            <input id="email" name="email" type="email" class="form-control"
                   value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" placeholder="邮箱"
                   autocomplete="off" autofocus>
        </div>
        <div class="form-group">
            <label for="password" class="sr-only">密码</label>
            <input id="password" name="password" type="password" class="form-control" placeholder="密码"
                   autocomplete="off">
        </div>
        <button class="btn btn-primary btn-block">登 录</button>
    </form>
</div>
<script>
    $(function (){
        /*
        * 入口函数作用：
        * 1.单独作用域
        * 2.确保页面加载过后执行
        * */
        $(document).ajaxStart(function (){
            NProgress.start();
        });
        $(document).ajaxStop(function (){
            NProgress.done();
        });
        $("#email").blur(function (){
            let $avatar=$(this).val();
            $.get("api/getavatar.php",{"avatar":$avatar},function (res){
                if(res)
                    $("#avatar").fadeOut(function (){
                        $(this).on("load",function (){
                            $(this).fadeIn();
                        }).attr('src',res);
                    });
                else
                    $("#avatar").fadeOut(function (){
                        $(this).on("load",function (){
                            $(this).fadeIn();
                        }).attr('src','/static/assets/img/default.png');
                    });
            });
        });
    })
</script>
</body>
</html>
