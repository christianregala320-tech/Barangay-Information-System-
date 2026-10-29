<?php

require_once("includes/auth.php");
require_once("connection.php");

$tables = [
    'users',
    'households',
    'residents',
    'certificates',
    'requests',
    'announcements',
    'activity_logs'
];

$backup = "-- Barangay Information System Backup\n";
$backup .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
$backup .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

foreach ($tables as $table) {

    $create = $conn->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);

    $backup .= "DROP TABLE IF EXISTS `$table`;\n";
    $backup .= $create['Create Table'] . ";\n\n";

    $rows = $conn->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {

        $columns = array_map(
            function($column) {
                return "`" . $column . "`";
            },
            array_keys($row)
        );

        $values = [];

        foreach ($row as $value) {
            if ($value === null) {
                $values[] = "NULL";
            } else {
                $values[] = $conn->quote($value);
            }
        }

        $backup .= "INSERT INTO `$table` (" . implode(", ", $columns) . ")
                    VALUES (" . implode(", ", $values) . ");\n";
    }

    $backup .= "\n";
}

$backup .= "SET FOREIGN_KEY_CHECKS = 1;\n";

header("Content-Type: application/sql");
header("Content-Disposition: attachment; filename=barangay_backup_" . date('Y-m-d_H-i-s') . ".sql");

echo $backup;
exit();

?>
