<?php
    require_once(str_replace("\\views","",__DIR__)."/helpers/helper.php");
    require_once(str_replace("\\views","",__DIR__) ."/helpers/LireRecursDir.php");
    explorerDir(str_replace("\\views","",__DIR__)."/txtFiles");
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .cloud{
            width: 100%;
            text-align: -webkit-center;
            margin-top: 5%;
        }
        .content{
            text-align: justify;
            margin:0 10%;
        }
    </style>
</head>
<body >
    <h1 style="text-align:center;">Content</h1>
    <div class="content">
        <?php
            if(isset($_GET['url']))
                echo _addStyle(file_get_contents( $_GET['url'] ),$_SESSION["search"]);
        ?>
    </div>
    <div class="cloud">
        <h2> Keyword Cloud </h2>
        <?php
            $keywords = _loadDataFromFile($_GET['url']);
            $wordCloudHTML = generateWordCloud($keywords);
            echo "<div style='width: 500px'>".$wordCloudHTML."</div>";
        ?>
    </div>
</body>
</html>
