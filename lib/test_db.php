<?php 
require_once __DIR__ . "/lib/db.php";

try{
    $stmt = $pdo->query("SELECT DATABASE() AS db, NOW() AS now");
    $row = $stmt->fetch();

    echo "✅ Conectado a DB: " . $row['db'] . "<br>";
    echo "Hora servidor: " . $row['now'] . "<br>";

    $tables = $pdo->query("SHOW TABLES")->fetchAll();
    echo "<pre>";
    print_r($tables);
    echo "</pre>";

} catch (Throwable $e) {
    echo "❌ Error: " . htmlspecialchars($e->getMessage());
}


