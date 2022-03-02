<h1>Inscription</h1>
<?php
$nom = $prenom = $mail = '';

$formCreator = new Form("post", "index.php?page=inscription");
$formCreator->getFormValues();
$form = $formCreator->createFormFromCSV("./assets/frmFiles/inscription.csv");

if (isset($_POST['envoi'])) 
{

    $errorHand = new ErrorHandler;

    $formCreator->setValuesChecker($errorHand);

    $formCreator->checkValues();

    if ($formCreator->getErrorsCount() === 0) 
    {
        $connHand = new Query;

        $resultat = $connHand->select("SELECT * FROM users WHERE usermail='". $formCreator->getValue("mail") . "'");
        
        if (count($resultat) !== 0) 
        {
            echo "<p>Votre adresse est déjà enregistrée dans la base de données</p>";
        }

        else 
        {
            $token = sha1(getRandomString(128));

            $connHand->insertion("
            INSERT INTO users(userName, userFirstname, userMail, userPassword, id_role, usertoken)
            VALUES ('". $formCreator->getValue("nom") 
            . "', '". $formCreator->getValue("prenom") 
            . "', '". $formCreator->getValue("mail") 
            . "', '". password_hash($formCreator->getValue("mdp"), PASSWORD_DEFAULT) . "', 2, '$token')
            ");
            
            $from = "contact.news@gmail.com";
            $to = $formCreator->getValue("mail");
            $subject = "Vérification de votre compte";
            $header = "Content-type: text/html; charset=iso-8859-1\nFrom:" . $from;
            $message = "<a href='http://localhost/GEIBAN/index.php?page=mailValidation&token=" 
            . urlencode($token) . "&mail=" 
                . $formCreator->getValue("mail") . "' target='_blank'>Cliquez sur ce lien pour valider votre compte</a>";
            mail($to, $subject, $message, $header);
        }
    } 

    else 
    {
        echo $formCreator->checkErrors();
        echo $form;
    }

} 

else 
{
    echo "<h2>Merci de renseigner le formulaire&nbsp;:</h2>";
    echo $form;
}

