<?php
// konfigurasi koneksi ke database
$host = '';
$port = '';
$dbname = '';
$username = '';
$password = '';


// membuat koneksi PDO
try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    echo "Koneksi sukses!";
}
catch(PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}

?>

