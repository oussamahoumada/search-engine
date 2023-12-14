<?php
require_once(str_replace("\\views", "", __DIR__) . "/helpers/helper.php");
require_once(str_replace("\\views", "", __DIR__) . "/helpers/LireRecursDir.php");
require_once(str_replace("\\views", "", __DIR__) . "/class/word.php");

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

        #spinner {
            display: none;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="http://localhost/Paris8/master2/search-engine/views/indexation.php">
            Indexation
        </a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="http://localhost/Paris8/master2/search-engine/views/index.php">
                        Search Engine
                    </a>
                </li>
                <li class="nav-item active">
                    <form method="post">
                        <button class="nav-link" type="submit" name="submitBtn" onclick="showSpinner()">
                            Load
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    <div id="spinner"></div>
    <div style="text-align: center;">
        <?php

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submitBtn"])) {
            $list_lemm = getAllLemme();
            explorerDir(str_replace("\\views", "", __DIR__) . "/txtFiles", $list_lemm);
            unset($_POST["submitBtn"]);
        }

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
    <script>
        function showSpinner() {
            document.getElementById("spinner").style.display = "block";
            //document.getElementById("spinner").style.display = "none";
        }
    </script>
</body>

</html>