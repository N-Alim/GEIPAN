<?php

trait GestionSonCompte
{
    public function connection(Sql $connexion)
    {
        $requete = "SELECT * FROM users WHERE mail='$this->mail'";
        $resultats = $connexion->select($requete);

        if (count($resultats) === 0 && $_SESSION["login"] = true)
        {
            return false;
        }

        else
        {
            $_SESSION["login"] = true;
            $_SESSION["nom"] = $this->nom;
            $_SESSION["prenom"] = $this->prenom;
            $_SESSION["mail"] = $this->mail;
            $_SESSION["dateNaissance"] = $this->dateNaissance;
            $_SESSION["idRole"] = $this->role;

            header("Location: http://localhost/VernonPHPPOOExercice/test.php");
        }
    }

    public function update(Sql $connexion, array $newValues)
    {
        if (count($newValues) !== 0)
        {
            $requete = "UPDATE users SET";

            foreach ($newValues as $key => $value) 
            {
                $requete .= " $key='$value',";
            }

            $requete = substr($requete, 0, -1) . " WHERE mail='$this->mail'";

            $connexion->insertion($requete);
        }

        else
        {
            echo "You must change at least 1 value";
        }
        
    }

    public function deconnection()
    {
        if ($_SESSION["login"] = true)
        {
            $_SESSION["login"] = false;

            session_unset();
        }

        else
        {
            return false;
        }
    }
}
