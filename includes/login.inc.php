<h1>Login</h1>
<?php



$formCreator = new Form("post", "index.php?page=login");
$formCreator->getFormValues();
$form = $formCreator->createFormFromCSV("./assets/frmFiles/login.csv");

if (isset($_POST['envoi']))
{

    $errorHand = new ErrorHandler;

    $formCreator->setValuesChecker($errorHand);

    $formCreator->checkValues();

    if ($formCreator->getErrorsCount() === 0)
    {
        $connHand = new Query;

        $resultat = $connHand->select("SELECT * FROM users WHERE usermail='" . $formCreator->getValue("mail") . "'");

        if (count($resultat) === 0)
        {
            echo "Pas de résultat avec votre login/mot de passe";
        }

        else
        {
            $mdpRequete = $resultat[0]->userPassword;
            if (password_verify($formCreator->getValue("mdp"), $mdpRequete))
            {
                if (!isset($_SESSION['login']))
                {
                    $_SESSION['login'] = true;
                    $_SESSION['nom'] = $resultat[0]->userName;
                    $_SESSION['prenom'] = $resultat[0]->userFirstNAme;
                    $_SESSION['role'] = $resultat[0]->id_role;
                    echo "<script>
                    document.location.replace('http://localhost/GEIPAN/');
                    </script>";
                }

                else
                {
                    echo "Vous êtes déjà connecté, donc vous n'avez rien à faire ici";
                }
            }

            else
            {
                echo "Bien tenté, mais non";
            }
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
