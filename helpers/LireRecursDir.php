<?php
require_once(str_replace("\\helpers","",__DIR__) ."/class/fichiers.php");
require_once(__DIR__ ."/helper.php");
require_once(str_replace("\\helpers","",__DIR__) ."/class/word.php");
require_once(str_replace("\\helpers","",__DIR__) ."/class/indexation.php");

function explorerDir($path)
{
	$folder = opendir($path);
	
	while($entree = readdir($folder))
	{		
		//On ignore les entrees
		if($entree != "." && $entree != "..")
		{
			// On verifie si il s'agit d'un repertoire
			if(is_dir($path."/".$entree))
			{
				$sav_path = $path;
				
				// Construction du path jusqu'au nouveau repertoire
				$path .= "/".$entree;
								
				// On parcours le nouveau repertoire
				// En appellant la fonction avec le nouveau repertoire
				explorerDir($path);
				$path = $sav_path;
			}
			else //C'est un fichier
			{	
				$path_source = $path."/".$entree;
				// ajouter fichier dans la base de donnée
				$file = new fichiers($entree, $path_source);
				if($file->Add()){
					$words = _loadDataFromFile($path_source);

					//ajouter les mot du fichier courant dans la base de donnée
					$fId = findFile($entree,$path_source);
					foreach ($words as $key => $value) {
						$wId = null;
						if($key!="" && !is_numeric($key)){
							$wId = findWord($key);
							if($wId==false){
								$word = new word($key);
								$word->Add();
								$wId = findWord($key);
							}
							$indx = new indexation($wId['wId'], $fId, $value);
							$indx->Add();
						}
					}
					echo '<script>
							console.log("Le fichier '.$entree.' avec le chemain '.$path_source.' qui contien '.count($words).' mots est ajouter.");
						</script>';
				}
			}
		}
	}
	closedir($folder);
}
?>