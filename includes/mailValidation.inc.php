<?php

dump($_GET);
dump(urldecode($_GET["mail"]));
dump(urldecode( password_verify("hi@gmail.com", $_GET["mail"])));


echo "<h1>Votre compte a bien été validé</h1>";
echo "<h2>Redirection en cours</h2>";

echo "<script>setTimeout(document.location.replace, 1000, 'index.php?page=login');</script>";