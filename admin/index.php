<?php require_once '../functions.php'; 
get_current();
$num_count=fetch_count_one('select count(1) as num from posts;');
$num_drafted=fetch_count_one('select count(1) as num from posts where status="drafted";');
$num_sort=fetch_count_one('select count(1) as num from categories;');
$num_comments=fetch_count_one('select count(1) as num from comments;');
$num_held=fetch_count_one('select count(1) as num from comments where status="held";');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
  <script src="/static/assets/vendors/chart/chart.min.js"></script>
</head>
<body>
  <script>NProgress.start()</script>
  <div class="main">
    <?php include 'inc/navbar.php' ?>
    <div class="container-fluid">
      <div class="jumbotron text-center">
        <h1>One Belt, One Road</h1>
        <p>Thoughts, stories and ideas.</p>
        <p><a class="btn btn-primary btn-lg" href="post-add.php" role="button">写文章</a></p>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">站点内容统计：</h3>
            </div>
            <ul class="list-group">
              <li class="list-group-item"><strong><?php echo $num_count["num"]; ?></strong>篇文章（<strong><?php echo $num_drafted["num"]; ?></strong>篇草稿）</li>
              <li class="list-group-item"><strong><?php echo $num_sort["num"]; ?></strong>个分类</li>
              <li class="list-group-item"><strong><?php echo $num_comments["num"]; ?></strong>条评论（<strong><?php echo $num_held["num"]; ?></strong>条待审核）</li>
            </ul>
          </div>
        </div>
        <div class="col-md-4">
          <canvas id="mychart"></canvas>
        </div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </div>
  <?php $current='index'; ?>
  <?php include 'inc/slidebar.php'; ?>
  <script src="/static/assets/vendors/jquery/jquery.min.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
  <script>
    var ctx = document.getElementById("mychart").getContext('2d');
    var myPieChart = new Chart(ctx,{
      type: 'pie',
      data: {
        datasets: [{
          data: [
          <?php echo $num_count["num"]; ?>,
          <?php echo $num_sort["num"]; ?>,
          <?php echo $num_comments["num"]; ?>
          ],
          backgroundColor: [
          "#2F4050",
          "#244334",
          "#333333"
          ],
          label: 'Dataset 1'
        }],
        labels: [
        '文章',
        '分类',
        '评论',
        ]
      },
      options: {
        responsive: true
      }
    });
  </script>
</body>
</html>
