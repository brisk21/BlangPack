<?php
/**
 * Created by PhpStorm.
 * User: wbs
 * Date: 2018/11/25
 * Time: 14:28
 */
$nomal = '大家好啊';
?>
<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>目录1.1</title>
</head>
<body>
<div>测试</div>
<div>
    <h1>测试php输出</h1>
    <ul>
        <li><?php echo '我是单引号字符串在目录'?></li>
        <li><?php echo "我是双引号字符串在三级目录里面"?></li>
        <li><?php echo $nomal?></li>
    </ul>
</div>
</body>
</html>
