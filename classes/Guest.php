<?php

class Guest extends User
{
    public function __construct()
    {   
        $this->role = 3;
    }
}
