<?php
date_default_timezone_set("PRC");
header("Content-Type: text/html;charset=utf-8");
include "../admin_judge.php";


if(isset($_POST["search"]))
{
    $search = $_POST["search"];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Excalibur OJ</title>
    <link rel="icon" href="/images/title.ico" type="image/x-icon"/>
    <link href="/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/Admin.css" rel="stylesheet">
    <script src="/bootstrap-3.3.7-dist/js/jquery.min.js"></script>
    <script src="/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <link href="/bootstrap-3.3.7-dist/css/bootstrap-switch.css" rel="stylesheet">
    <script src="/bootstrap-3.3.7-dist/js/bootstrap-switch.js"></script>
    <script type="text/javascript">
        $(function () {
            $('#mySwitch input').bootstrapSwitch();
        })
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
</head>



<?php
include "../../functions/conn.php";
$conn = new mysqli($servername, $dbusername, $dbpasswd, $dbname);
// 检测连接
if (!$conn) {
    die("连接失败: " . $conn->connect_error);
}
$conn->query("set names 'utf8'");
$str = "select user_avatar_path from user where user_mail='$user_mail'";
$result = $conn->query($str);
while (list($user_avatar_path) = $result->fetch_row())
{
    $avatar_path = $user_avatar_path;
}
?>

<body>
<div class="container" style="width: 100% ; height:100%; ">
    <div class="row clearfix" style="height: 100% ;">
        <div class="col-md-2 column" style="height: 100% ; background-color:white;" id="align_nav">
            <?php
            echo "<img src='"."$avatar_path"."' alt=\"140x140\" class=\"img-circle\" style=\"width: 100px; height: 100px; margin-left: 50px;
            margin-bottom: 20px;\">";
            echo "<h4 style='text-align: center;'>$user_name</h4>";
            ?>

            <nav>
                <ul class="nav nav-pills nav-stacked">
                    <li class="active"><a href="../problem_admin">问题列表</a></li>
                    <li><a href="../problem_create">创建问题</a></li>
                    <li><a href="../contest_admin">比赛列表</a></li>
                    <li><a href="../contest_create">创建比赛</a></li>
                    <li><a href="../user_admin">用户列表</a></li>
                    <li><a href="../user_plugin">导入&生成用户</a></li>
                    <li><a href="../board_admin">公告列表</a></li>
                    <li><a href="../board_create">创建公告</a></li>
                    <li><a href="/">返回主站</a></li>
                    <li><a href="/functions/login_out">退出登录</a></li>
                </ul>
            </nav>
        </div>
        <div class="col-md-10 column col-md-offset-2">
            <article id="wrapper">
                <nav class="navbar navbar-default" role="navigation"
                     style="border:0px;background-color: white;box-shadow:0px 0px 0px 0px #666;">

                    <div>
                        <ul class="nav navbar-nav">
                            <li>
                                <h3>&nbsp;&nbsp;Problem List</h3>
                            </li>
                        </ul>
                        <form class="navbar-form navbar-right" action="problem_search.php" method="post" role="search">
                            <div class="form-group">
                                <input type="text" name= "search" class="form-control" placeholder="输入标题或ID进行检索...">
                            </div>
                            <button type="submit" class="btn btn-default">查找</button>
                        </form>

                    </div>

                </nav>

                <div class="row clearfix">
                    <div class="col-md-12 column">
                        <table class="table table-striped table-hover table-responsive">
                            <thead>
                            <tr class="">
                                <th>
                                    ID
                                </th>
                                <th>
                                    Title
                                </th>
                                <th>
                                    Author
                                </th>
                                <th>
                                    Last Update Time
                                </th>
                                <th>
                                    Visible
                                </th>
                                <th>
                                    Test_case
                                </th>
                                <th>
                                    Operation
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php

                            $str = "select problem_id,problem_title,user_name,problem_create_time,problem_visible,problem_testcase_path from user,problem where user_id=problem_author and (problem_title like '%$search%' or problem_id like '%$search%')order by problem_id desc ";
                            $result = $conn->query($str);
                            while (list($problem_id, $problem_title, $problem_author,$problem_create_time,$problem_visible,$problem_testcase_path) = $result->fetch_row()) {
                                echo "<tr>
                                <td>
                                    $problem_id
                                </td>
                                <td>
                                    <a href='/problem/problem_page/?id=$problem_id' target='_blank'>
                                        $problem_title
                                    </a>
                                </td>
                                <td>
                                    $problem_author
                                </td>
                                <td>
                                    $problem_create_time
                                </td>
                                ";
                                if($problem_visible == '1')
                                    echo "<td style='color: deepskyblue;'>
                                        <a href='visible_change.php?id=$problem_id&page=$page&visible=1'>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;√&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </a>
                                        </td>";
                                else
                                    echo "<td style='color: mediumvioletred;'>
                                        <a style='color: mediumvioletred'; href='visible_change.php?id=$problem_id&page=$page&visible=0'>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;✕&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </a>
                                        </td>";
                                if($problem_testcase_path == '')
                                    echo "<td><a href='test_case_upload_page.php?problem_id=$problem_id&page=$page'    style='color: mediumvioletred;' target='_blank'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;✕&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>";
                                else
                                    echo "
                                    <td  style='color: deepskyblue;'><a href='test_case_view.php?problem_id=$problem_id&page=$page'  target='_blank'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;√&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>";

                                echo "
                                <td>
                                    <a href='../problem_edit?id=$problem_id&page=$page'>
                                    <button type=\"button\" class=\"btn btn-default\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Edit\"><span class=\"glyphicon glyphicon-edit\" aria-hidden=\"true\"> </span></button>
                                    </a>
                                    <a href='delete.php?id=$problem_id&page=$page' >
                                    <button onclick=\"return confirm('确定删除当前题目？')\"  type=\"button\" class=\"btn btn-default\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span></button>
                                    </a>
                                    ";
                                echo "
                                    <a href='test_case_upload_page.php?problem_id=$problem_id&page=$page' target='_blank'>
                                 <button type=\"button\" class=\"btn btn-default\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Upload\"><span class=\"glyphicon glyphicon-open\" aria-hidden=\"true\"></span></button>
                                  </a>    ";


                                if($problem_testcase_path != '')
                                {
                                    echo "
                                    <a href='test_case_download.php?problem_id=$problem_id&page=$page' target='_blank'>
                                    <button type=\"button\" class=\"btn btn-default\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Download\"><span class=\"glyphicon glyphicon-download-alt\" aria-hidden=\"true\"></span></button>
                                    </a>
                                      ";


                                }
                                else
                                {
                                    echo "
                                    <button type=\"button\" class=\"btn btn-default\" data-toggle=\"tooltip\" data-placement=\"top\" title=\"Download\"><span class=\"glyphicon glyphicon-download-alt\" aria-hidden=\"true\"></span></button>
                                      ";
                                }
                                echo "
                                   </td>
                            </tr>";
                            }
                            //下载按钮
                            //<button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Download"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"><a href="#"></a></span></button>
                            //状态
                            /*
                             *
                                <th>
                                    Invisible
                                </th>

                                <td>
                                    <div class="switch" id="mySwitch">
                                        <input type="checkbox" checked data-on-text="YES" data-off-text="NO"/>
                                    </div>

                                </td>
                             */

                            $result->close();
                            $conn->close();
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>


            </article>

            <!--            页脚-->
            <?php
            include "../../pages/footer/index.php";
            ?>
            <!--            页脚-->
        </div>
    </div>
</div>

</body>
</html>