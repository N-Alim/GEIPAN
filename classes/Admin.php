<?php

class Admin extends User
{
    use GestionSonCompte;
    use GestionCompteRegistered;

    public function __construct()
    {   
        $this->role = 1;
    }
}
