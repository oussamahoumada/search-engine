<?php
require_once(__DIR__ . "/connexion.php");

class fichiers
{
    public $name;
    public $path;
    public $word_count;
    public $word_delete_count;

    public function __construct($name = NULL, $path = NULL, $word_count = NULL, $word_delete_count = NULL)
    {
        $this->name = $name;
        $this->path = $path;
        $this->word_count = $word_count;
        $this->word_delete_count = $word_delete_count;
    }

    //Ajouter un fichier après la verification de son existence
    function Add()
    {
        if ($this->check() > 0) {
            return false;
        }
        try {
            $cnx = new connexion();
            $req = "INSERT INTO file (name, path, word_count, word_delete_count) VALUES (:name, :path, :word_count, :word_delete_count)";
            $prep = $cnx->prepare($req);
            $prep->execute(
                array(
                    ':name' => $this->name,
                    ':path' => $this->path,
                    ':word_count' => $this->word_count,
                    ':word_delete_count' => $this->word_delete_count
                )
            );
            $prep->closeCursor();
        } catch (PDOException $e) {
            print $e->getMessage();
        }
        return true;
    }

    //Verifier l'existence d'un fichier
    function check()
    {
        try {
            $cnx = new connexion();
            $req = "SELECT count(*) as 'len' FROM file where (name = :name AND path = :path)";
            $prep = $cnx->prepare($req);
            $prep->execute(
                array(
                    ':name' => $this->name,
                    ':path' => $this->path
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

//chercher un fichier appartire la base de donnée
function findFile($name, $path)
{
    try {
        $cnx = new connexion();
        $req = "SELECT fId  FROM file where (name = :name AND path = :path)";
        $prep = $cnx->prepare($req);
        $prep->execute(
            array(
                ':name' => $name,
                ':path' => $path
            )
        );
        $result = $prep->fetch(PDO::FETCH_ASSOC)['fId'];
        $prep->closeCursor();
    } catch (PDOException $e) {
        print $e->getMessage();
    }
    return $result;
}

function getAllFiles($path=null)
{
    try {
        $cnx = new connexion();
        $req = "SELECT * FROM file";
        if ($path != null)
            $req .= " where path=$path";
        $prep = $cnx->prepare($req);
        $prep->execute();
        $result = $prep->fetchAll(PDO::FETCH_ASSOC);
        $prep->closeCursor();
    } catch (PDOException $e) {
        print $e->getMessage();
    }
    return $result;
}