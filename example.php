<?php
session_start();
require_once 'easySQL.php';

use App\Config\EasySQL;

// Connect to database ex:
$db = new EasySQL('localhost', 'root', '', 'kemisida');

// insert data ex:
$data = [
    'username' => 'vicci',
    'password' => 'test1234'
];
$db->db_In('users', $data);

// Fetch data ex:
$results = $db->db_Out('users', '*');
print_r($results);

// Example of fetching specific data with conditions
$results = $db->db_Out('users', 'username, password', 'username = ?', ['vicci']);
foreach($results as $row) {
    echo $row['username'] . ': ' . $row['password'] . "\n";
}
?>
