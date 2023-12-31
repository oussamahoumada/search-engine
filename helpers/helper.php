<?php
require_once(str_replace("\\helpers", "", __DIR__) . "/class/connexion.php");
require_once(str_replace("\\helpers", "", __DIR__) . "/class/word.php");


//Cette fonction permet de separer les mots par une list de separateur donnée
function _multiexplode($delimiters, $string)
{
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return $launch;
}

//Cette fonction return tous les mots et le nobre de repetition de chaque mot d'un fichier donnee
function _loadDataFromFile($path)
{
    if (strpos($path, ".html") != false) {
        $imp = strip_tags(file_get_contents($path));
    }
    if (strpos($path, ".pdf") != false) {
        $chemain = "C:\wamp64\www\Paris8\Master2\search-engine/scripts/pdf_to_text.py";
        $imp = (shell_exec(escapeshellcmd('python ' . $chemain . ' "' . $path . '"')));
    }
    if (strpos($path, ".txt") != false) {
        $imp = implode(" ", file($path));
    }
    $exp = prepareSearchInput($imp);
    $arrayCount = array_count_values($exp);
    foreach ($arrayCount as $key => $value) {
        if (preg_match('/\d/', $key) || strlen($key) <= 2) {
            unset($arrayCount[$key]);
        }
    }
    return $arrayCount;
}

//Cette fonction permet a supprimer les espaces et transformer majuscule en minuscule
function _deleteSpaces($arr)
{
    mb_internal_encoding('UTF-8');
    foreach ($arr as $key => $value) {
        $arr[$key] = mb_strtolower($arr[$key]);
        $arr[$key] = trim($arr[$key]);
    }
    return $arr;
}

//Cette fonction permet a supprimer les mot d'arret
function _deleteStopWords($arr)
{
    $stopWords = file(__DIR__ . "/stopwords.txt");
    $arr = _deleteSpaces($arr);
    $stopWords = _deleteSpaces($stopWords);
    $tab = array_diff($arr, $stopWords);
    return $tab;
}

//Afficher le resultat
function _afficher($result, $mot, $c)
{
    echo ("<ul>Nombre de réponses pour (<b>" . $mot . "</b>) :</u>" . $c . " <br>");
    echo ("<br>");
    echo ("<ul type='circle'>");
    foreach ($result as $k => $v) {
        $freq = $v['libelle'] . "<sup>" . $v['frequence'] . "</sup>";
        if (count($v) > 6) {
            for ($i = 1; $i <= (((count($v)) - 6) / 2); $i++) {
                $lib = "libelle" . $i;
                $fr = "frequence" . $i;
                $freq .= ", " . $v[$lib] . "<sup>" . $v[$fr] . "</sup>";
            }
        }

        $path = "http://localhost/Paris8/Master2/search-engine/views/affichage/?url=" . $v['path'];
        $dialog = "dialogBox_$k";
        $dialogChart = "dialogChart_$k";
        $dialogChartFunc = 'showDialog("dialogChart_' . $k . '")';
        $dialogChartClose = "closeDialog('dialogChart_$k')";
        echo ("<li>
                <a href='" . $path . "' target='_blank'>"
            . $v['name'] .
            "</a>(" . $freq . ")
                    <button onclick='showDialog(" . $dialog . ")' style='border: none;background: none;'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-cloud' viewBox='0 0 16 16'>
                            <path d='M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z'/>
                        </svg>
                    </button>
                    <button onclick='$dialogChartFunc' style='border: none;background: none;'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pie-chart' viewBox='0 0 16 16'>
                          <path d='M7.5 1.018a7 7 0 0 0-4.79 11.566L7.5 7.793V1.018zm1 0V7.5h6.482A7.001 7.001 0 0 0 8.5 1.018zM14.982 8.5H8.207l-4.79 4.79A7 7 0 0 0 14.982 8.5zM0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8z'/>
                        </svg>
                    </button>
            </li>");

        if (strpos($v['path'], ".pdf") != false) {
            echo ("<br><br>");
        } else {
            echo "<p style='margin-left:20px; font-size:12px; opacity: 50%;'>" . _addStyle(file_get_contents($v['path'], null, null, null, 250), $mot) . " ...</p>";
        }
        $keywords = _loadDataFromFile($v['path']);
        $wordCloudHTML = generateWordCloud($keywords);
        echo '
            <dialog id="' . $dialog . '" style="border-color:white; border-radius:5%">
                <header style="text-align:end">
                    <button onclick="closeDialog(' . $dialog . ')" style="border: none;background: none;">&#x2716</button>
                </header>
                <div style="width: 600px; text-align:center"> ' . $wordCloudHTML . ' </div>
            </dialog>
        ';
        echo '
            <dialog id="' . $dialogChart . '" style="border-color:white; border-radius:5%">
                <header style="text-align:end">
                    <button onclick="' . $dialogChartClose . '" style="border: none;background: none;">&#x2716</button>
                </header>
                <div style="width:300px; display:inline-block">
                    <canvas id="' . $dialogChart . '' . $k . '"></canvas>
                </div>
                <script> printChart(' . ((int) $v["word_count"]) . ',' . ((int) $v["word_delete_count"]) . ',' . ((int) ((int) $v["word_count"]) - ((int) $v["word_delete_count"])) . ',' . $dialogChart . '' . $k . ',"' . $v["name"] . '") </script>
            </dialog>
        ';
    }
    echo ("</ul>");
}

function _addStyle($text, $words)
{
    $wordsToMarke = prepareSearchInput($words);
    $arr = explode(" ", $text);
    foreach ($wordsToMarke as $v) {
        for ($i = 0; $i < count($arr); $i++) {
            if (strpos(strtolower(removeAccents($arr[$i])), strtolower(removeAccents($v))) !== false) {
                $arr[$i] = str_ireplace(strtolower($arr[$i]), "<b style='color:orange;'>" . $arr[$i] . "</b>", strtolower($arr[$i]));
            }
        }
    }

    return implode(" ", $arr);
}
function removeAccents($string)
{
    return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'))), ' '));
}
function prepareSearchInput($serchInput)
{
    $ponctuation_arr = [" ", "…", "!", "?", ".", ",", ";", ":", "(", ")", "{", "}", "[", "]", "—", "-", "+", "=", "/", "\\", "d'", "d’", "l'", "l’", "s'", "s’"];
    $exp = _multiexplode($ponctuation_arr, $serchInput);
    return _deleteStopWords($exp);
}
//Cette fonction permet de chercher un mot dans la base de donnée
function _getWord($words)
{
    /*
        $chemain = "../scripts/lemmatisation.py";
        var_dump(shell_exec(escapeshellcmd("python {$chemain} animaux")));
    */
    //$noStopWordList = prepareSearchInput($word);

    $w = "w.libelle = '-1'";
    foreach ($words as $value) {
        $w .= " or w.libelle = '$value'";
    }

    try {
        $cnx = new connexion();
        $req = "SELECT w.libelle,f.name,i.frequence,f.path,f.word_count,f.word_delete_count FROM word w inner JOIN indexation i on i.wId=w.wId INNER JOIN file f on  f.fId=i.fId where {$w} order by frequence desc";
        $prep = $cnx->prepare($req);
        $prep->execute();
        $result = $prep->fetchAll(PDO::FETCH_ASSOC);
        $prep->closeCursor();
    } catch (PDOException $e) {
        print $e->getMessage();
    }
    //var_dump($result);
    $c = 0;
    $p = [];
    $tab = [];
    for ($i = 0; $i < count($result); $i++) {
        if (!in_array($result[$i]["path"], $p)) {
            $tab[$c] = $result[$i];
            $p[$i] = $result[$i]["path"];
            $k = 1;
            for ($j = $i + 1; $j < count($result); $j++) {
                if ($p[$i] == $result[$j]["path"]) {
                    $tab[$c]["libelle{$k}"] = $result[$j]['libelle'];
                    $tab[$c]["frequence{$k}"] = $result[$j]['frequence'];
                    $k++;
                }
            }
        }
        $c++;
    }
    return $tab;
}

function pagination($nbr)
{
    if ((($nbr / 4) * 100000) > (((int) ($nbr / 4)) * 100000)) {
        return ((int) ($nbr / 4)) + 1;
    }
    return ($nbr / 4);
}

function generateWordCloud($keywords)
{
    $wordCloud = '';
    foreach ($keywords as $keyword => $value) {
        $fontSize = (($value + 60) * $value);
        $red = rand(0, 255); // Random red value for color
        $green = rand(0, 255); // Random green value for color
        $blue = rand(0, 255); // Random blue value for color
        if (!is_numeric($keyword) && $value > 1)
            $wordCloud .= " <span >
                            <a style='text-decoration: none;font-size:{$fontSize}%; color: rgb({$red},{$green},{$blue});' href='http://localhost/Paris8/master2/search-engine/views/?search={$keyword}'>{$keyword}</a>
                            <sup>{$value}</sup> 
                        </span>";
    }
    return $wordCloud;
}

function spellCorrection($p)
{
    $chemain = "../scripts/spellCorrector.py";
    $exec = (shell_exec(escapeshellcmd('python ' . $chemain . ' "' . $p . '"')));
    $w = str_replace(" ", "+", $exec);
    $result = "http://localhost/Paris8/master2/search-engine/views/?search={$w}";

    if (trim($p) != trim($exec)) {
        echo "<i style='color:red'>Try with this spelling</i> : ";
        echo "<a href={$result}>{$exec}</a>";
    }
}

function lemmatisation($list, $list_lemm)
{
    //$list_lemm = getAllLemme();
    $new_array = [];
    foreach ($list as $key => $value) {
        $res = array_search($key, array_column($list_lemm, 'ortho'));
        if ($res) {
            $lem = $list_lemm[$res]["lemme"];
            if (array_key_exists($lem, $new_array)) {
                $new_array[$lem] = $new_array[$lem] + $value;
            } else {
                $new_array[$lem] = $value;
            }
        } else {
            $new_array[$key] = $value;
        }
    }
    return $new_array;
}

function lemmatisation_sersh($list, $list_lemm)
{
    $new_array = [];
    foreach ($list as $key => $value) {
        $res = array_search($value, array_column($list_lemm, 'ortho'));
        if ($res) {
            $lem = $list_lemm[$res]["lemme"];
            if (array_search($lem, $new_array)) {

            } else {
                array_push($new_array, $lem);
            }
        } else {
            array_push($new_array, $value);
        }
    }
    return $new_array;
}