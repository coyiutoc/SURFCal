<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = 'Home';
$pageMode = ($loggedin ? '' : 'macro');

include('styles/header.php');

if ($loggedin) {
    // Load default caldendar.
} else {
    echo <<<_END
                    <main>
                        <section class="landing">
                            <h2>Welcome to SURFCal!</h2>
                            <p class="subtitle"></p>
                            <script>document.getElementsByClassName("subtitle")[0].innerHTML += twemoji.parse("🐿 🦄 🐰 🐠");</script>
                            <nav>
                                <a href="#" class="login">Login</a>
                                <a href="#">Sign Up</a>
                            </nav>
                        </section>
                    </main>
_END;
}

include('styles/footer.php');

?>
