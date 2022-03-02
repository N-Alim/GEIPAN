<?php

$formCreator = new Form("post", "index.php?page=testimony", "multipart/form-data");
$formCreator->getFormValues();
$form = $formCreator->createFormFromCSV("./assets/frmFiles/testimony.csv");

echo $form;