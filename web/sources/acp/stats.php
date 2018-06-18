<?php

if (basename($_SERVER['PHP_SELF']) === 'main.php') {
    require_once('../403.php');
}

$pageTitle .= ' - Stats';
$pageMode = '';

$totalItemsByType = getTotalItemsByType();

$stats = array(
    array('Items created', getTotalItems(), ''),
    array('&nbsp;&nbsp;&nbsp;&nbsp; - Events', $totalItemsByType["event"], ''),
    array('&nbsp;&nbsp;&nbsp;&nbsp; - Reminders', $totalItemsByType["reminder"], ''),
    array('&nbsp;&nbsp;&nbsp;&nbsp; - Tasks', $totalItemsByType["task"], ''),
    array('&nbsp;&nbsp;&nbsp;&nbsp; - Notes', $totalItemsByType["note"], ''),
    array('Average Items Per Account', getAverageItemsPerAccount(), ''),
    array('Average Contacts Per Account', getAverageContactsPerAccount(), ''),
    array('Minimum number of contacts created for an account', getMinMaxContactsPerAccount("min"), 'Minimum contacts created, calculated across all accounts that have contacts'),
    array('Maximum number of contacts created for an account', getMinMaxContactsPerAccount("max"), 'Maximum contacts created, calculated across all accounts that have contacts'),
    array('Minimum number of items created for an account', getMinMaxItemsPerAccount('min'), 'Minimum items created, calculated across all accounts that have contacts'),
    array('Maximum number of items created for an account', getMinMaxItemsPerAccount('max'), 'Maximum items created, calculated across all accounts that have contacts')
);

include('styles/header.php');

echo "
                    <main>
                        <header>
                            <h2>Site Admin View</h2>
                        </header>
                        <section class=\"accounts\">
                            <h3>Site Stats</h3>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Metric</th>
                                        <th>Stat</th>
                                        <th>Note</th>
                                    </tr>
                                </thead>
                                <tbody>";

for ($i = 0; $i < count($stats); $i++) {
    echo "\n                                    <tr>"
    . "\n                                        <td>" . $stats[$i][0] . "</td>"
    . "\n                                        <td>" . $stats[$i][1] . "</td>"
    . "\n                                        <td>" . $stats[$i][2] . "</td>"
    . "\n                                    </tr>";
}

echo "
                                </tbody>
                            </table>
                        </section>
                    </main>
";

// echo var_dump(getTotalItems());
// echo var_dump(getTotalItemsByType());

include('styles/footer.php');

?>
