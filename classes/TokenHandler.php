<?php

trait TokenHandler
{
    private string $token;
    
    private function getRandomString($size) 
    {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $randomString = '';
    
        for ($i = 0; $i < $size; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
    
        return $randomString;
    }

    public function createToken()
    {
        $this->token = sha1(getRandomString(128));
    }

    public function sendMail()
    {
        $from = "contact.news@gmail.com";
        $to = $this->mail;
        $subject = "Vérification de votre compte";
        $header = "Content-type: text/html; charset=iso-8859-1\nFrom:" . $from;
        $message = "<a href='http://localhost/GEIPAN/index.php?page=mailValidation&token=" 
        . urlencode($this->token) . "&mail=" 
            . urlencode($this->mail) . "' target='_blank'>Cliquez sur ce lien pour valider votre compte</a>";
        mail($to, $subject, $message, $header);
    }
    
}
