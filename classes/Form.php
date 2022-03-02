<?php

class Form
{
    private string $form;
    private string $keyToNotUse = "envoi";
    private $errorHand;
    private array $formValues = array();

    public function __construct(string $method, string $action, string $enctype = null)
    {
        $this->form = "<form method='$method' action='$action'" . (($enctype === null) ? ">" : "enctype='$enctype'>") . "<ul>";
    }

    public function getFormValues()
    {
        foreach ($_POST as $key => $value) 
        {
            $this->formValues[$key][1] = htmlentities(trim($value));
        }

        foreach ($_FILES as $key => $value) 
        {
            $this->formValues[$key][1] = $value;
        }
    }

    public function createFormFromCSV($csvPath, $linesOffset = 1)
    {
        $csvInputs = csvReader($csvPath, $linesOffset);

        $formInputs = array();

        for ($cnt=0; $cnt < count($csvInputs); $cnt++) 
        { 
            $formInputs[$csvInputs[$cnt][0]] = 
            [ $this->createInputTypeFromString($csvInputs[$cnt][1]),
            ($csvInputs[$cnt][2] === "true") ? true : false, 
            $csvInputs[$cnt][3], 
            $csvInputs[$cnt][4]];
        }

        return $this->createForm($formInputs);
}   

    public function createInputTypeFromString(string $InputString)
    {
        switch ($InputString) 
        {
            case "Text":
                return InputType::Text;

            case "File":
                return InputType::File;

            case "FileMultiple":
                return InputType::FileMultiple;

            case "LongText":
                return InputType::LongText;

            case "Mail":
                return InputType::Mail;

            case "Password":
                return InputType::Password;

            case "PasswordInit":
                return InputType::PasswordInit;

            case "PasswordVerif":
                return InputType::PasswordVerif;

            case "Time":
                return InputType::Time;
            
            case "DateTime":
                return InputType::DateTime;

            case "Select";
                return InputType::Select;
            
        }
        
    }

    private function createForm(array $inputs)
    {
        $this->inputs = $inputs;
        foreach ($inputs as $key => $value) 
        {
            $this->createInput($key, $value);
        }

        $this->form .= "<li><input type='reset' value='Effacer' />
            <input type='submit' value='Envoyer' name='envoi' /></li>
            </ul></form>";


        return $this->form;
    }

    private function createInput($name, $inputDetails)
    {
        $this->form .= "<li><label for='$name'> $inputDetails[2] : </label>";

        switch ($inputDetails[0]) 
        {
            case InputType::Text:
            case InputType::Mail:
                $this->form .= "<input type='texte' id='$name' name='$name' value=" 
                . (($inputDetails[1]) ? ($this->formValues[$name][1] ?? $inputDetails[3]) : $inputDetails[3]) . "></li>"; 
                break;

            case InputType::File:
                $this->form .= "<input type='file' id='$name' name='$name' accept='image/jpeg, image/jpg, image/png, image/gif'></li>"; 
                break;

            case InputType::FileMultiple:
                $this->form .= "<input type='file' id='$name' name='$name" . "[]' accept='image/jpeg, image/jpg, image/png, image/gif' multiple></li>"; 
                break;
                
            case InputType::Password:
                $this->form .= "<input type='password' id='$name' name='$name' value=''></li>"; 
                break;

            case InputType::PasswordInit:
                {
                    $this->form .= "<input type='password' id='$name' name='$name' value=''></li>"
                    . "<li><label for='$name'Verif> Vérification $inputDetails[2] : </label>"
                    . "<input type='password' id='$name" . "Verif' name='$name" . "Verif' value=''></li>"; 
                    $this->formValues[$name . "Verif"][0] = InputType::PasswordVerif;
                    break;
                }

            case InputType::LongText:
                $this->form .= "<textarea id='$name' name='$name' value='" 
                . (($inputDetails[1]) ? "<?php echo \$$name;?>" : $inputDetails[3]) . "'></textarea></li>";          
                break;

            case InputType::Time:
                $this->form .= "<input type='time' id='$name' name='$name' step='1' value=" 
                . (($inputDetails[1]) ? ($this->formValues[$name][1] ?? $inputDetails[3]) : $inputDetails[3]) . "></li>"; 
                break;

            case InputType::DateTime:
                $this->form .= "<input type='datetime-local' id='$name' name='$name' value=" 
                . (($inputDetails[1]) ? ($this->formValues[$name][1] ?? $inputDetails[3]) : $inputDetails[3]) . "></li>"; 
                break;

            case InputType::Select:
                $this->form .= "<select range='$name' id='$name' name='$name'>
                </select>"; 
                break;

            default:
        }

        $this->formValues[$name][0] = $inputDetails[0];
        $this->formValues[$name][2] = $inputDetails[3];
    }

    public function setValuesChecker(ErrorHandler $errorHand)
    {
        $this->errorHand = $errorHand;
    }

    public function checkValues()
    {
        foreach ($this->formValues as $key => $value) 
        {
            if ($key != $this->keyToNotUse)
            {
                $this->checkValue($key, $value);
            }
        }
    }

    private function checkValue($inputName, $inputDetails)
    {
        switch ($inputDetails[0]) 
        {
            case InputType::Text:
                {
                    if (preg_match('/(*UTF8)^[[:alpha:]]+$/', html_entity_decode($inputDetails[1])) !== 1)
                        $this->errorHand->addError("Champ \"$inputName\" manquant");

                    else
                        $this->formValues[$inputName][1] = html_entity_decode($this->formValues[$inputName][1]);
                    break;
                }


            case InputType::Mail:
                if (!filter_var($this->formValues[$inputName][1], FILTER_VALIDATE_EMAIL))
                    $this->errorHand->addError("Veuillez saisir un e-mail valide");
                break;

            case InputType::File:
                $this->checkFile($inputName); 
                break;

            case InputType::Password:
            case InputType::Time:
            case InputType::DateTime:
                if (strlen($this->formValues[$inputName][1]) === 0)
                    $this->errorHand->addError("Champ \"$inputName\" manquant");
                break;

            case InputType::PasswordInit:
                {
                    if (strlen($this->formValues[$inputName][1]) === 0)
                        $this->errorHand->addError("Veuillez saisir un mot de passe");
                
                    else if (strlen($this->formValues[$inputName . "Verif"][1]) === 0)
                        $this->errorHand->addError("Veuillez saisir la vérification de votre mot de passe");
                
                    else if ($this->formValues[$inputName][1] !== $this->formValues[$inputName ."Verif"][1])
                        $this->errorHand->addError("Vos mots de passe ne correspondent pas");
                    break;
                }

            default:
        }
    }


    private function checkFile(string $name)
    {
        if ($this->formValues[$name][1]['error'] == 4) 
        {
            $this->formValues[$name][1] = $this->formValues[$name][2];
        }
    
        else if ($this->formValues[$name][1]['error'] == 0) 
        {
            $fileName = $this->formValues[$name][1]['name'];
            $fileType = $this->formValues[$name][1]['type'];
            $fileTmpName = $this->formValues[$name][1]['tmp_name'];
            
            $tableauTypes = array("image/jpeg", "image/jpg", "image/png", "image/gif");
    
            if (in_array($fileType, $tableauTypes))
            {
                $path = getcwd() . "/assets/avatar/";
                $date = date('Ymdhis');
                $fileName = $date . $fileName;
                $fileFullName = $path . $fileName;
                $fileFullName = str_replace("\\", "/", $fileFullName);
    
                move_uploaded_file($fileTmpName, $fileFullName);

                $this->formValues[$name][1] = $fileFullName;
            }
            else 
            {
                $this->errorHand->addError("Erreur type MIME");
            }
        }
    
        else 
        {
            $this->getFileError($this->formValues[$name][1]['error']);
        }
    }


    private function getFileError(int $errorValue)
    {
        switch($errorValue) {
            case 1 :
                $fileUploadErrorMessage = "La taille du fichier téléchargé excède la valeur de upload_max_filesize.";
            break;

            case 2 :
                $fileUploadErrorMessage = "La taille du fichier téléchargé excède la valeur de MAX_FILE_SIZE, qui a été spécifiée dans le formulaire HTML.";
            break;

            case 3 :
                $fileUploadErrorMessage = "Le fichier n'a été que partiellement téléchargé.";
            break;

            case 4 :
                $fileUploadErrorMessage = "Aucun fichier n'a été téléchargé.";
            break;

            case 6 :
                $fileUploadErrorMessage = "Un dossier temporaire est manquant.";
            break;

            case 7 :
                $fileUploadErrorMessage = "Échec de l'écriture du fichier sur le disque.";
            break;

            case 8 :
                $fileUploadErrorMessage = "Une extension PHP a arrêté l'envoi de fichier.";
            break;
        }

        $this->errorHand->addError("Erreur upload : " . $fileUploadErrorMessage);
    }

    public function getValue($key)
    {
        return $this->formValues[$key][1];
    }

    public function getErrorsCount()
    {
        return $this->errorHand->getErrorsCount();
    }

    public function checkErrors()
    {
        return $this->errorHand->getErrorMessage();
    }
}
