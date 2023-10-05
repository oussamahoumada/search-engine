<?php
require_once(__DIR__ ."/connexion.php");
class indexation
{
    public $fId;
    public $wId;
    public $frequence;

    public function __construct($wId = NULL, $fId = NULL, $frequence = NULL)
    {
        $this->fId = $fId;
        $this->wId = $wId;
        $this->frequence = $frequence;
            
    }

    //Ajouter une indexation après la verification de son existence
    function Add()
    {
        if($this->check() > 0){
            return false;
        }
        try {
            $cnx = new connexion();
            $req = "INSERT INTO indexation (fId, wId, frequence) VALUES (:fId, :wId, :frequence)";
            $prep = $cnx->prepare($req);
            $prep->execute(
                array(
                    ':fId' => $this->fId,
                    ':wId' => $this->wId,
                    ':frequence'=>$this->frequence
                )
            );
            $prep->closeCursor();
        } catch (PDOException $e) {
            print $e->getMessage();
        }
        return true;
    }

    //Verifier l'existence d'une indexation
    function check()
    {
        try {
            $cnx = new connexion();
            $req = "SELECT count(*) as 'len' FROM indexation where (fId = :fId AND wId = :wId)";
            $prep = $cnx->prepare($req);
            $prep->execute(
                array(
                    ':fId' => $this->fId,
                    ':wId' => $this->wId
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
