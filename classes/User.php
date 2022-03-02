<?php

abstract class User
{
    protected string $nom;
    protected string $prenom = "";
    protected string $mail = "";
    protected string $avatar = "";
    protected int $role = 2;
    protected Query $connexion;
    use TokenHandler;

    public function setNom(string $nom) : void
    {
        $this->nom = $nom;
    }

    public function getNom() : string|bool
    {
        return isset($this->nom) ? $this->nom : false;
    }

    public function setPrenom(string $prenom) : void
    {
        $this->prenom = $prenom;
    }

    public function getPrenom() : string|bool
    {
        return isset($this->prenom) ? $this->prenom : false;
    }

    public function setMail(string $mail) : bool
    {
        if (filter_var($mail, FILTER_VALIDATE_EMAIL))
        {
            $this->mail = $mail;
            return true;
        }

        else
            return false;
    }

    public function getMail() : string|bool
    {
        return isset($this->mail) ? $this->mail : false;
    }

    public function setAvatar(string $avatar) : void
    {
        $this->avatar = $avatar;
    }

    public function getAvatar() : string|bool
    {
        return isset($this->avatar) ? $this->avatar : false;
    }

    public function setRole(string $role) : void
    {
        $this->role = $role;
    }

    public function getConnexion() : string|bool
    {
        return isset($this->connexion) ? $this->connexion : false;
    }

    public function setConnexion(Query $connexion) : void
    {
        $this->connexion = $connexion;
    }

    public function getRole() : string|bool
    {
        return isset($this->role) ? $this->role : false;
    }

    public function inscription(string $mdp)
    {
        $resultat = $this->connexion->select("SELECT * FROM users WHERE usermail='". $this->mail . "'");

        if (count($resultat) !== 0) 
        {
            if ($resultat[0]->userName === null)
            {
                $this->createToken();

                $this->connexion->insertion("UPDATE users SET userName='$this->nom'
                , userFirstname='$this->prenom'
                , userPassword='" . password_hash($mdp, PASSWORD_DEFAULT) .
                "', userAvatar='$this->avatar'
                , id_role=2
                , userToken='$this->token' WHERE usermail='$this->mail'");

                echo "<p>Votre compte a été mis à jour</p>";

                $this->sendMail();

                echo "<p>Veuillez valider votre compte pour pouvoir vous connecter</p>";

            }


            else
            {
                echo "<p>Votre compte est déjà enregistrée dans la base de données</p>";
            }
        }

        else 
        {
            $this->createToken();

            $this->connexion->insertion("
            INSERT INTO users(userName, userFirstname, userMail, userPassword, userAvatar, id_role, usertoken)
            VALUES ('$this->nom', '$this->prenom', '$this->mail', '"
            . password_hash($mdp, PASSWORD_DEFAULT) . "', '$this->avatar',  2, '$this->token')
            ");

            $this->sendMail();

            echo "<p>Veuillez valider votre compte pour pouvoir vous connecter</p>";

        }
    }

}
