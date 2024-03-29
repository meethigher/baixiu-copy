<?php
require_once '../functions.php'; 
$user=get_current();
$current=isset($current)?$current:"";
$current_user=$user['nickname'];
$current_avatar=$user['avatar'];
?>
<div class="aside">
    <div class="profile">
        <img class="avatar" src="<?php echo $current_avatar; ?>">
        <h3 class="name"><?php echo $current_user; ?></h3>
    </div>
    <ul class="nav">
        <li<?php echo $current == 'index' ? ' class="active"' : ''; ?>>
            <a href="index.php"><i class="fa fa-dashboard"></i>仪表盘</a>
        </li>
        <li<?php echo ($current == 'posts' || $current == 'post-add' || $current == 'categories') ? ' class="active"' : ''; ?>>
            <a href="#menu-posts"<?php echo ($current == 'posts' || $current == 'post-add' || $current == 'categories') ? '' : ' class="collapsed"'; ?>
               data-toggle="collapse">
                <i class="fa fa-thumb-tack"></i>文章<i class="fa fa-angle-right"></i>
            </a>

            <ul id="menu-posts"<?php echo ($current == 'posts' || $current == 'post-add' || $current == 'categories') ? ' class="collapse in"' : ' class="collapse"'; ?>>
                <li<?php echo $current == 'posts' ? ' class="active"' : ''; ?>><a href="posts.php">所有文章</a></li>
                <li<?php echo $current == 'post-add' ? ' class="active"' : ''; ?>><a href="post-add.php">写文章</a></li>
                <li<?php echo $current == 'categories' ? ' class="active"' : ''; ?>><a href="categories.php">分类目录</a>
                </li>
            </ul>
        </li>
        <li<?php echo $current == 'comments' ? ' class="active"' : ''; ?>>
            <a href="comments.php"><i class="fa fa-comments"></i>评论</a>
        </li>
        <li<?php echo $current == 'users' ? ' class="active"' : ''; ?>>
            <a href="users.php"><i class="fa fa-users"></i>用户</a>
        </li>
        <li<?php echo ($current == 'nav-menus' || $current == 'slides' || $current == 'settings') ? ' class="active"' : ''; ?>>
            <a href="#menu-settings"<?php echo ($current == 'nav-menus' || $current == 'slides' || $current == 'settings') ? '' : ' class="collapsed"'; ?>
               data-toggle="collapse">
                <i class="fa fa-cogs"></i>设置<i class="fa fa-angle-right"></i>
            </a>
            <ul id="menu-settings"<?php echo ($current == 'nav-menus' || $current == 'slides' || $current == 'settings') ? ' class="collapse in"' : ' class="collapse"'; ?>>
                <li<?php echo $current == 'nav-menus' ? ' class="active"' : ''; ?>><a href="nav-menus.php">导航菜单</a></li>
                <li<?php echo $current == 'slides' ? ' class="active"' : ''; ?>><a href="slides.php">图片轮播</a></li>
                <li<?php echo $current == 'settings' ? ' class="active"' : ''; ?>><a href="settings.php">网站设置</a></li>
            </ul>
        </li>
    </ul>
</div>