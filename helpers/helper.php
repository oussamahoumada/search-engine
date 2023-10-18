<?php
require_once(str_replace("\\helpers", "", __DIR__) . "/class/connexion.php");

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
    $imp = implode(" ", file($path));
    $ponctuation_arr = [" ", "…", "!", "?", ".", ",", ";", ":", "(", ")", "{", "}", "[", "]", "—", "-", "+", "=", "/", "\\", "d'", "d’", "l'", "l’", "s'", "s’"];
    $exp = _multiexplode($ponctuation_arr, $imp);
    return array_count_values(_deleteStopWords($exp));
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
        $path = "http://localhost/Paris8/Master2/tp-web-search-engine/search-engine/views/affichage/?url=" . $v['path'];
        $dialog = "dialogBox" . $k;
        echo ("<li>
                <a href='" . $path . "' target='_blank'>"
            . $v['name'] .
            "</a>(" . $v['frequence'] . ")
                    <button onclick='showDialog(" . $dialog . ")' style='border: none;background: none;'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-cloud' viewBox='0 0 16 16'>
                            <path d='M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z'/>
                        </svg>
                    </button>
            </li>");
        echo "<p style='margin-left:20px; font-size:12px; opacity: 50%;'>" . _addStyle(file_get_contents($v['path'], null, null, null, 250), $mot) . " ...</p>";
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
    }
    echo ("</ul>");
}

function _addStyle($text, $mot)
{
    $arr = explode(" ", $text);
    for ($i = 0; $i < count($arr); $i++) {
        if (strpos(strtolower($arr[$i]), strtolower($mot)) !== false) {
            $arr[$i] = str_ireplace(strtolower($mot),"<b style='color:orange;'>" . $mot . "</b>", strtolower($arr[$i]));
        }
    }
    return implode(" ", $arr);
}
//Cette fonction permet de chercher un mot dans la base de donnée
function _getWord($word)
{
    try {
        $cnx = new connexion();
        $req = "SELECT name,frequence,path FROM word w inner JOIN indexation i on i.wId=w.wId INNER JOIN file f on  f.fId=i.fId where (w.libelle = :libelle) order by frequence desc";
        $prep = $cnx->prepare($req);
        $prep->execute(
            array(
                ':libelle' => $word
            )
        );
        $result = $prep->fetchAll(PDO::FETCH_ASSOC);
        $prep->closeCursor();
    } catch (PDOException $e) {
        print $e->getMessage();
    }
    return $result;
}

function pagination($nbr)
{
    if ((($nbr / 4) * 100000) > (((int) ($nbr / 4)) * 100000)) {
        return ((int) ($nbr / 4)) + 1;
    }
    return ($nbr / 4);
}

//Recuperer les donnees a afficher pour chaque page
function getData($nbr = 1, $arr, $mot, $c)
{
    $filArray = array();
    for ($i = (($nbr - 1) * 4); $i < (($nbr - 1) * 4) + 4; $i++) {
        if ($i == count($arr))
            break;
        $filArray[] = $arr[$i];
    }
    _afficher($filArray, $mot, $c);
}

function generateWordCloud($keywords)
{
    $wordCloud = '';
    foreach ($keywords as $keyword => $value) {
        $fontSize = (($value + 60) * $value);
        $red = rand(0, 255); // Random red value for color
        $green = rand(0, 255); // Random green value for color
        $blue = rand(0, 255); // Random blue value for color
        if (!is_numeric($keyword))
            $wordCloud .= " <span >
                            <a style='text-decoration: none;font-size:{$fontSize}%; color: rgb({$red},{$green},{$blue});' href='http://localhost/Paris8/master2/tp-web-search-engine/search-engine/views/?search={$keyword}'>{$keyword}</a>
                            <sup>{$value}</sup> 
                        </span>";
    }
    return $wordCloud;
}