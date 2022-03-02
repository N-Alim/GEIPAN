<?php

class Registered extends User
{
    use GestionSonCompte;

    public function __construct()
    {   
        $this->role = 2;
    }
}
