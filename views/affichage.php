<?php
require_once(str_replace("\\views", "", __DIR__) . "/helpers/helper.php");
require_once(str_replace("\\views", "", __DIR__) . "/helpers/LireRecursDir.php");
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
        .fileContent {
            text-align: justify;
            margin: 0 10%;
        }
    </style>
</head>

<body>
    <h1 style="text-align:center;">Content</h1>
    <div class="fileContent">
        <?php
        if (isset($_GET['url'])) {
            if (strpos($_GET['url'], ".pdf") != false) {
                $url = str_replace("C:\wamp64\www\\", "http://localhost/", $_GET['url']);
                echo "<embed  src='{$url}' width='100%' type='application/pdf' height='600px'></embed >";
            } else
                echo _addStyle(file_get_contents($_GET['url']), $_SESSION["search"]);
        }

        ?>
    </div>
</body>

</html>