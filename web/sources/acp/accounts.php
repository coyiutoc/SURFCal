<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle .= ' - Accounts';
$pageMode = '';

// Array of account information for all accounts
$accQ = getAllAccounts();
// Array of ids (of experienced accounts)
$expQ = getExperiencedAccounts();

include('styles/header.php');

echo "
                    <main>
                        <header>
                            <h2>Site Admin View</h2>
                        </header>
                        <section class=\"accounts\">
                            <h3>Accounts</h3>
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Name</th>
                                        <th>Exp?</th>
                                    </tr>
                                </thead>
                                <tbody>";
foreach($accQ as &$acc) {
    echo "\n                                    <tr>"
    . "\n                                        <td>" . $acc['id'] . "</td>"
    . "\n                                        <td>" . $acc['username'] . "</td>"
    . "\n                                        <td>" . $acc['email'] . "</td>"
    . "\n                                        <td>" . $acc['name'] . "</td>"
    . "\n                                        <td><i class=\"fas fa-" . (in_array($acc['id'], $expQ) ? "check" : "times") . "\"></i></td>"
    . "\n                                    </tr>";
}
echo "
                                </tbody>
                            </table>
                        </section>
                    </main>
";

include('styles/footer.php');

?>
