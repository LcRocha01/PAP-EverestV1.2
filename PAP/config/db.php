<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "localhost";
$user = "root";
$pass = "mysql";
$dbname = "logistica_pap";

try {
    $conn = new mysqli($host, $user, $pass, $dbname);
    $conn->set_charset("utf8");
} catch (mysqli_sql_exception $e) {
    die("Erro na ligação à BD.");
}
