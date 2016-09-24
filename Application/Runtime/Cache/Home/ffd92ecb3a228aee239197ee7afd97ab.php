<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<head xmlns:COLOR="http://www.w3.org/1999/xhtml">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>查询结果</title>
    <style type="text/css">
        /* CSS Document */
        body {
            font: normal 11px auto "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
            color: #4f6b72;
            background: #E6EAE9;
        }

        a {
            color: #c75f3e;
        }

        #mytable {
            width: 95%;
            padding: 0;
            margin: 0;
        }

        caption {
            padding: 0 0 5px 0;
            width: 700px;
            font: italic 11px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
            text-align: right;
        }

        th {
            font: bold 11px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
            color: #4f6b72;
            border-right: 1px solid #C1DAD7;
            border-bottom: 1px solid #C1DAD7;
            border-top: 1px solid #C1DAD7;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-align: left;
            padding: 6px 6px 6px 12px;
            background: #CAE8EA;
        }

        th.nobg {
            border-top: 0;
            border-left: 0;
            border-right: 1px solid #C1DAD7;
            background: #CAE8EA;
        }

        td {
            border-right: 1px solid #C1DAD7;
            border-bottom: 1px solid #C1DAD7;
            background: #fff;
            font-size: 11px;
            padding: 6px 6px 6px 12px;
            color: #4f6b72;
        }

        .alt {
            background: #F5FAFA;
            color: #797268;
        }

        th.spec {
            border-left: 1px solid #C1DAD7;
            border-top: 0;
            background: #fff;
            font: bold 10px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
        }

        th.specalt {
            border-left: 1px solid #C1DAD7;
            border-top: 0;
            background: #f5fafa;
            font: bold 10px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
            color: #797268;
        }

        /*---------for IE 5.x bug*/
        html > body td {
            font-size: 11px;
        }
    </style>
</head>
<body>
    <p class=".alt" style="font-size:66px;margin-top: 5px;margin-bottom: 5px"><?php echo ($average); ?></p>
    <table id="mytable" cellspacing="0" summary="The technical specifications of the Apple PowerMac G5 series">
        <tr>
            <th scope="col" abbr="Configurations" class="nobg">学号</th>
            <th scope="col" abbr="Dual 1.8">姓名</th>
            <th scope="col" abbr="Dual 2">班级</th>
            <th scope="col" abbr="Dual 2.5">学年</th>
            <th scope="col" abbr="Dual 1.8">学期</th>
            <th scope="col" abbr="Dual 2">课程</th>
            <th scope="col" abbr="Dual 2.5">学分</th>
            <th scope="col" abbr="Dual 1.8">成绩</th>
            <th scope="col" abbr="Dual 2">绩点</th>
        </tr>

        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr <?php if(($mod) == "0"): ?>class="alt"<?php endif; ?>>
                <th scope='row' abbr='Model' class='spec'<?php if(($mod) == "0"): ?>style="background: #F5FAFA;color: #797268;"<?php endif; ?>><?php echo ($vo[stu_num]); ?></th>
                <td <?php if(($mod) == "0"): ?>class="alt"<?php endif; ?>><?php echo ($vo[stu_name]); ?></td>
                <td <?php if(($mod) == "0"): ?>class="alt"<?php endif; ?>><?php echo ($vo[stu_class]); ?></td>
                <td <?php if(($mod) == "0"): ?>class="alt"<?php endif; ?>><?php echo ($vo[stu_year]); ?></td>
                <td <?php if(($mod) == "0"): ?>class="alt"<?php endif; ?>><?php echo ($vo[stu_grade_year]); ?></td>
                <td <?php if(($mod) == "0"): ?>class="alt"<?php endif; ?>><?php echo ($vo[stu_course_name]); ?></td>
                <td <?php if(($mod) == "0"): ?>class="alt"<?php endif; ?>><?php echo ($vo[stu_fen]); ?></td>
                <td <?php if(($mod) == "0"): ?>class="alt"<?php endif; ?>><?php echo ($vo[stu_grade]); ?></td>
                <td <?php if(($mod) == "0"): ?>class="alt"<?php endif; ?>><?php echo ($vo[stu_dian]); ?></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>

    </table>
</body>
</html>