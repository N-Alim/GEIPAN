<?php

trait GestionCompteRegistered
{
    public function deleteUser(Query $connection, User $user)
    {
        if ($user->getRole() > $this->getRole())
        {
            $requete = "DELETE FROM users WHERE mail='" . $user->getMail() . "'";

            $connection->insertion($requete);
        }

        else 
        {
            echo "You can't delete an user with the same or more rights";
        }

    }
}
