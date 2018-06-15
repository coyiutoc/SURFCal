<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = 'Login';
$pageMode = 'micro';

include('styles/header.php');

if ($loggedin) {
    header("Location: ?$profile=home");
}

echo <<<_END
                    <main>
                        <section class="login">
                            <h4>Login</h4>
                            <form action="#">
                                <div class="field"><label for="username">Username</label><input type="text" name="username" placeholder="Username" required="required" maxlength="32" /></div>
                                <div class="field"><label for="password">Password</label><input type="password" name="password" placeholder="Password" required="required" maxlength="64" /></div>
                                <div class="field"><input type="submit" value="Login" class="button" /></div>
                            </form>
                            <p>Forgot your password? <a href="#">Reset it.</a></p>
                            <p>No account? <a href="#">Create one.</a></p>
                        </section>
                    </main>
_END;

include('styles/footer.php');

?>
