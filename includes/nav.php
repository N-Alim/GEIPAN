<header>
    <nav role="navigation">
        <ul>
            <li><a href="index.php?page=home">Home</a></li>
            <li><a href="index.php?page=testimony">Make a testimony</a></li>
            <?php
                if(isset($_SESSION['login']) && $_SESSION['login'] === true) {
                    echo "<li><a href=\"index.php?page=logout\">Logout</a></li>";
                    echo "<li><a href=\"index.php?page=myaccount\">Mon compte</a></li>";
                }
                else {
                    echo "<li><a href=\"index.php?page=login\">Login</a></li>";
                    echo "<li><a href=\"index.php?page=inscription\">Inscription</a></li>";
                }

                if (isset($_SESSION["role"]) && $_SESSION["role"] === 1)
                {
                    echo "<li><a href=\"index.php?page=gestionUsers\">Gestion des utilisateurs</a></li>";
                }
            ?>

            <li><a href="index.php?page=404">UFO Findings</a></li>

        </ul>
    </nav>
</header>