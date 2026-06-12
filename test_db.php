<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config/database.php';

try {
    $pdo = Database::getConnection();
    echo "Base active : " . $pdo->query("SELECT DATABASE()")->fetchColumn() . "<br>";

    $rows = $pdo->query("SELECT * FROM stock_batches")->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    var_dump($rows);
    echo "</pre>";
} catch (PDOException $e) {
    echo "Erreur PDO : " . $e->getMessage();
}
