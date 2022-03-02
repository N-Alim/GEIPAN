<?php

abstract class User
{
    protected string $nom;
    protected string $prenom = "";
    protected string $mail = "";
    protected string $dateNaissance = "";
    protected int $role = 2;

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

    public function setDateNaissance(string $dateNaissance) : void
    {
        $this->dateNaissance = $dateNaissance;
    }

    public function getDateNaissance() : string|bool
    {
        return isset($this->dateNaissance) ? $this->dateNaissance : false;
    }

    public function setRole(string $role) : void
    {
        $this->role = $role;
    }

    public function getRole() : string|bool
    {
        return isset($this->role) ? $this->role : false;
    }

    public function inscription(Sql $connexion)
    {
        $requete = "INSERT INTO users
        (nom, prenom, mail, datenaissance, id_role) 
        VALUES ('$this->nom', '$this->prenom', '$this->mail', '$this->dateNaissance', $this->role)";

        $connexion->insertion($requete);
    }
}
