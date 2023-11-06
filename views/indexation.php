<?php
require_once(str_replace("\\views", "", __DIR__) . "/helpers/helper.php");
require_once(str_replace("\\views", "", __DIR__) . "/helpers/LireRecursDir.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <script src='https://cdn.jsdelivr.net/npm/chart.js'></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src='../scripts/chartGenerator.js'></script>

    <style>
        .btn_redirect {
            padding: 1%;
            color: white;
            border: none;
            border-radius: 10px;
            background-color: blue;
            margin: 2% 5% 0 0;
        }

        .div_redirect {
            text-align: end;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand"
            href="http://localhost/Paris8/master2/tp-web-search-engine/search-engine/views/indexation.php">
            Indexation
        </a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link"
                        href="http://localhost/Paris8/master2/tp-web-search-engine/search-engine/views/index.php">
                        Search Engine
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div style="text-align: center;">
        <?php
        explorerDir(str_replace("\\views", "", __DIR__) . "/txtFiles");
        foreach (getAllFiles() as $value) {
            $name = $value['name'];
            $realLen = $value['word_count'];
            $lenAfterCleaning = $value['word_delete_count'];
            $diff = ((int) $realLen) - ((int) $lenAfterCleaning);
            echo "<div style='width:300px; display:inline-block'>
			    <canvas id='$name'></canvas>
		    </div>";
            echo "<script>
			    printChart($realLen, $lenAfterCleaning, $diff, '$name');
		    </script>";
        }
        ?>
    </div>
</body>

</html>