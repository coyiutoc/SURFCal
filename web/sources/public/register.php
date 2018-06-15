<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle = 'Register';
$pageMode = 'macro';

include('styles/header.php');

if ($loggedin) {
    header("Location: ?$profile=home");
}

echo <<<_END
                    <main>
                        <section class="register">
                            <h4>Create an Account</h4>
                            <form action="#">
                                <div class="field"><label for="username">Username</label><input type="text" name="username" placeholder="Username" required="required" maxlength="32" /></div>
                                <div class="field"><label for="password">Password</label><input type="password" name="password" placeholder="Password" required="required" maxlength="64" /></div>
                                <div class="field"><label for="passwordC">Confirm Password</label><input type="password" name="passwordC" placeholder="Password" required="required" maxlength="64" /></div>
                                <div class="field"><label for="email">Email</label><input type="text" name="email" placeholder="Email" required="required" maxlength="64" /></div>
                                <div class="field"><label for="email">Confirm Email</label><input type="text" name="email" placeholder="Email" required="required" maxlength="64" /></div>
                                <div class="field birthdate">
                                    <label for="bdate">Birthday</label>
                                    <input type="number" name="bdateM" placeholder="MM" required="required" min="1" max="12" />
                                    <input type="number" name="bdateD" placeholder="DD" required="required" min="1" max="31" />
                                    <input type="number" name="bdateY" placeholder="YYYY" required="required" min="0" />
                                </div>
                                <div class="field"><input type="submit" value="Create" class="button" /></div>
                            </form>
                            <p>Have an account? <a href="?$profile=login">Sign in.</a></p>
                        </section>
                    </main>
_END;

include('styles/footer.php');

?>
