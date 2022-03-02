<?php

if (isset($_SESSION["role"]) && $_SESSION["role"] === 1)
{



}
else
{
    echo "<script>
    document.location.replace('http://localhost/GEIPAN/index.php?page=404');
    </script>";
}