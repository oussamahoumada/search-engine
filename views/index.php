<?php
require_once(str_replace("\\views", "", __DIR__) . "/helpers/helper.php");
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles/index.css">
    <script src='https://cdn.jsdelivr.net/npm/chart.js'></script>
    <script src='../scripts/chartGenerator.js'></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand"
            href="http://localhost/Paris8/master2/search-engine/views/indexation.php">
            Indexation
        </a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link"
                        href="http://localhost/Paris8/master2/search-engine/views/index.php">
                        Search Engine
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <h1 class="title"> Search Engine </h1>
    <div class="content">
        <form class="row" style="padding-bottom:1%">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Search</span>
                <input type="text" class="form-control" name="search" placeholder="Write something"
                    aria-label="Username" aria-describedby="basic-addon1" value="<?php if (isset($_GET['search']))
                        echo $_GET['search']; ?>">
                <button type="submit" class="btn btn-primary">submit</button>
            </div>
        </form>
        <?php
        if (count($_GET) <= 0) {
            session_unset();
        }
        if (isset($_GET['search'])) {
            session_unset();
            $search = "";
            $_SESSION["search"] = $_GET['search'];
            $search = $_SESSION["search"];
        } else {
            if (isset($_SESSION["search"])) {
                $search = $_SESSION["search"];
            } else {
                $search = "";
            }
        }

        if ($search != "") {
            $tab = explode(" ", $search);

            $noStopWordList = prepareSearchInput($search);
            $search_lemmatisation = lemmatisation_sersh($noStopWordList, getAllLemme());
            $result = _getWord($search_lemmatisation);
            
            

            $c = count($result);
            spellCorrection($search);


            if (isset($_GET['p']) && $_GET['p'] > 0 && $_GET['p'] <= pagination($c)) {
                $filArray = array_slice($result, ($_GET['p'] - 1) * 4, (($_GET['p'] - 1) * 4) + 4);
            } else {
                $filArray = array_slice($result, 0, 4);
            }
            _afficher($filArray, $search, $c);
            //Pagination
            if (pagination($c) > 1) {
                echo ('<br>');
                echo '<div>
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center">
                ';
                for ($i = 1; $i <= pagination($c); $i++) {
                    echo '<li class="page-item"><a class="page-link" href="?p=' . $i . '">' . $i . '</a></li>';
                }
                echo "  </ul>
                    </nav>
                </div>";
            }

        }
        ?>
    </div>

</body>

</html>