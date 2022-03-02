<?php

class Query
{
    private string $serverName = "localhost";
    private string $userName = "root";
    private string $database = "geipan";
    private string $userPassword = "";
    private object $connexion;

    public function __construct()
    {
        try{
            $this->connexion = new PDO("mysql:host=$this->serverName;dbname=$this->database", $this->userName, $this->userPassword);
            $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e){
            die("Erreur :  " . $e->getMessage());
        }
    }

    public function insertion($requete)
    {
        try
        {
            $this->connexion->beginTransaction();
            $this->connexion->exec($requete);
            $this->connexion->commit();

        }

        catch (PDOException $e)
        {
            $this->connexion->rollBack();     
            die("Erreur : " . $e->getMessage());
        }    
    }

    public function select($requete)
    {
        try
        {            
            $requete = $this->connexion->prepare($requete);
            $requete->execute();
            $resultat = $requete->fetchAll(PDO::FETCH_OBJ);

            return $resultat;
        }

        catch(PDOException $e)
        {
            die("Erreur :  " . $e->getMessage());
        }
    }

    public function __destruct()
    {
        unset($this->connexion);        
    }
}
