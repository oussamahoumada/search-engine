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
    <div class="div_redirect">
        <button class="btn_redirect" onclick="redirect()">
            My Search Engine
        </button>
    </div>

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