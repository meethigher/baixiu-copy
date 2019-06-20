<?php require_once '../functions.php';
get_current();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>Comments &laquo; Admin</title>
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
            <h1>所有评论</h1>
        </div>
        <!-- 有错误信息时展示 -->
        <!-- <div class="alert alert-danger">
          <strong>错误！</strong>发生XXX错误
        </div> -->
        <div class="page-action">
            <!-- show when multiple checked -->
            <div class="btn-batch" style="display: none">
                <button href="javascript:void(0);" class="btn btn-info btn-sm">批量批准</button>
                <button href="javascript:void(0);" class="btn btn-warning btn-sm">批量拒绝</button>
                <button href="javascript:void(0);" id="btnDelMul" class="btn btn-danger btn-sm">批量删除</button>
            </div>
            <ul id="pages" class="pagination pagination-sm pull-right">
            </ul>
        </div>
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>作者</th>
                <th>评论</th>
                <th>评论在</th>
                <th>提交于</th>
                <th>状态</th>
                <th class="text-center" width="150">操作</th>
            </tr>
            </thead>
            <tbody>
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
<?php $current = "comments"; ?>
<?php include 'inc/slidebar.php'; ?>

<script src="/static/assets/vendors/jquery/jquery.min.js"></script>
<script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
<script src="/static/assets/vendors/jsrender/jsrender.js"></script>
<script src="/static/assets/vendors/twbs-pagination/jquery.twbsPagination.min.js"></script>

<script>NProgress.done()</script>
<script id="comments_tmpl" type="text/x-jsrender">
      {{for comments}}
      <tr{{if status=="rejected"}} class="danger" {{else status=="held"}} class="warning" {{/if}} data-id="{{:id}}">
      <td class="text-center"><input type="checkbox"></td>
      <td>{{:author}}</td>
      <td>{{:content}}</td>
      <td>《{{:post_title}}》</td>
      <td>{{:created}}</td>
      <td>{{if status=="rejected"}}已驳回{{else status=="held"}}待处理{{else status=="approved"}}已批准{{/if}}</td>
      <td class="text-center">
        {{if status=="held"}}
        <button class="btn btn-info btn-xs">批准</button>
        <button class="btn btn-warning btn-xs">驳回</button>
        {{/if}}
        <button class="btn btn-danger btn-xs btn-delete">删除</button>
      </td>
    </tr>
    {{/for}}



</script>
<script>
    //===============================
    $(document).ajaxStart(function () {
        NProgress.start();
        $("#loading").fadeIn();
    });
    $(document).ajaxStop(function () {
        NProgress.done();
        $("#loading").fadeOut();
    });
    // ===============================
    var currentPage = 1;

    function loadPageData(page) {
        $('tbody').fadeOut();
        $.get("/admin/api/comments.php", {p: page}, function (res) {
            if (page > res['totalPages']) {
                loadPageData(res['totalPages']);
                return false;
            }
            //第一次回调时没有初始化分页组件
            //第二次回调用这个组件不会重新渲染分页组件
            $('#pages').twbsPagination('destroy');
            $('#pages').twbsPagination({
                first: '首页',
                last: '尾页',
                prev: '<',
                next: '>',
                startPage: page,
                totalPages: res['totalPages'],
                visiablePages: 5,
                initiateStartPageClick: false,
                onPageClick: function (e, page) {
                    //点击分页页码执行这里
                    loadPageData(page);
                    currentPage = page;
                }
            });
            let html = $('#comments_tmpl').render({comments: res['comments']});
            $('tbody').fadeIn().html(html);
        });
    }
    loadPageData(currentPage);


    // 批准按钮
    $("tbody").on("click",".btn-info",function (){
      let id = $(this).parent().parent().attr("data-id");
      $.get("/admin/comment-delete.php",{id:id,b:'1'},function (res){
        if(!res) return;
        loadPageData(currentPage);
      });
    });
    //驳回按钮
    $("tbody").on("click",".btn-warning",function (){
      let id = $(this).parent().parent().attr("data-id");
      $.get("/admin/comment-delete.php",{id:id,b:'2'},function (res){
        if(!res) return;
        loadPageData(currentPage);
      });
    });
    // 删除按钮
    $("tbody").on("click", ".btn-delete", function () {
        let id = $(this).parent().parent().attr("data-id");
        $.get("/admin/comment-delete.php", {id: id}, function (res) {
            if (!res) return;
            loadPageData(currentPage);
        });
    });
    // 批量删除按钮
    $("#btnDelMul").on("click", function () {
      let array=[];
      $("tbody input").each(function (i,ele){
        if($(ele).prop("checked"))
          array.push($(ele).parent().parent().attr("data-id"));
      });
      array=array.toString();
      $.get("/admin/comment-delete.php",{id:array},function (res){
        if(!res) return;
        loadPageData(currentPage);
      })
    });
    // 全选功能
    // ========================
    window.onclick = function () {
        let theadInput = $("thead input");
        let tbodyInput = $("tbody input");
        theadInput.on("change", function () {
            $checked = theadInput.prop("checked");
            if ($checked)
                $(".btn-batch").fadeIn();
            else
                $(".btn-batch").fadeOut();
            tbodyInput.prop("checked", $checked);
            // $("tbody input").prop("checked",$checked);
        });
        tbodyInput.on("change", function () {
            let flag = true;
            let flag1 = false;
            tbodyInput.each(function (i, ele) {
                if (!$(ele).prop("checked"))
                    flag = false;
                else
                    flag1 = true;
            });
            theadInput.prop("checked", flag);
            if (flag1)
                $(".btn-batch").fadeIn();
            else
                $(".btn-batch").fadeOut();
        });
    };
    // ==========================
    
    




</script>
</body>
</html>
