<?php

if (!$_SESSION['login'])
{
    echo "<script>
    document.location.replace('http://localhost/GEIPAN/index.php?page=404');
    </script>";
}

else
{
    $_SESSION['login'] = false;
    session_unset();
    session_destroy();
    echo "<script>document.location.replace('http://localhost/GEIPAN/');</script>";   

}
