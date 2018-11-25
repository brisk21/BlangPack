<?php
/**
 * Created by PhpStorm.
 * User: wbs
 * Date: 2018/11/25
 * Time: 14:28
 */
$nomal = '我也是正常输出的变量';
?>
<!doctype html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>目录2</title>
</head>
<body>
<div>测试</div>
<div>
    <h1>我知道我只喜欢独自流浪</h1>
    <ul>
        <li><?php echo '程序啊你要开心'?></li>
        <li><?php echo "单身自由呗"?></li>
        <li><?php echo $nomal?></li>
    </ul>
</div>
</body>
</html>
