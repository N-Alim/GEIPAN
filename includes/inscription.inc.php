<h1>Inscription</h1>
<?php

if (isset($_SESSION['login']))
{
    echo "<script>
    document.location.replace('http://localhost/GEIPAN/index.php?page=404');
    </script>";
}

else
{
    $formCreator = new Form("post", "index.php?page=inscription", "multipart/form-data");
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

            $user = new Registered();
            $user->setNom($formCreator->getValue("nom"));
            $user->setPrenom($formCreator->getValue("prenom"));
            $user->setMail($formCreator->getValue("mail"));
            $user->setAvatar($formCreator->getValue("avatar"));
            $user->setConnexion($connHand);

            $user->inscription($formCreator->getValue("mdp"));
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
}
