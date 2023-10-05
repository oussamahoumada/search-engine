<?php
require_once(__DIR__ ."/connexion.php");
class word
{
    public $libelle;

    public function __construct($libelle = NULL)
    {
        $this->libelle = $libelle;    
    }

    //Ajouter un mot après la verification de son existence
    function Add()
    {
        if($this->check() > 0){
            return false;
        }
        try {
            $cnx = new connexion();
            $req = "INSERT INTO word (libelle) VALUES (:libelle)";
            $prep = $cnx->prepare($req);
            $prep->execute(
                array(
                    ':libelle' => $this->libelle,
                )
            );
            $prep->closeCursor();
        } catch (PDOException $e) {
            print $e->getMessage();
        }
        return true;
    }

    //Verifier l'existence d'un mot
    function check()
    {
        try {
            $cnx = new connexion();
            $req = "SELECT count(*) as 'len' FROM word where (libelle = :libelle)";
            $prep = $cnx->prepare($req);
            $prep->execute(
                array(
                    ':libelle' => $this->libelle
                )
            );
            $result = $prep->fetch(PDO::FETCH_ASSOC)['len'];
            $prep->closeCursor();
        } catch (PDOException $e) {
            print $e->getMessage();
        }
        return $result;
    }
}

function findWord($libelle){
    try {
            $cnx = new connexion();
            $req = "SELECT wId  FROM word where (libelle = :libelle)";
            $prep = $cnx->prepare($req);
            $prep->execute(
                array(
                    ':libelle' => $libelle
                )
            );
            
            $result = $prep->fetch(PDO::FETCH_ASSOC);
            
            $prep->closeCursor();
        } catch (PDOException $e) {
            print $e->getMessage();
        }
    return $result;
}
