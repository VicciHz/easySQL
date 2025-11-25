<?php

namespace App\Config;

class EasySQL
{
    private $conn;

    public function __construct($db_server, $db_user, $db_pass, $db_name, $db_port = 3306)
    {
        try {
            $this->conn = \mysqli_connect($db_server, $db_user, $db_pass, $db_name, $db_port);
            if (!$this->conn) {
                throw new \Exception("Failed to connect to MySQL: " . \mysqli_connect_error());
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            die("Database connection error. Please try again later.");
        }
    }

    /**
     * Inserts data into a database table.
     */
    public function db_In($dbTable, $data): void
    {
        if (!is_array($data) || empty($data)) {
            return;
        }

        $columns = implode(", ", array_map(fn ($col) => "`$col`", array_keys($data)));
        $placeholders = implode(", ", array_fill(0, count($data), '?'));
        $values = array_values($data);
        $query = "INSERT INTO `$dbTable` ($columns) VALUES ($placeholders)";

        try {
            $stmt = mysqli_prepare($this->conn, $query);
            if (!$stmt) {
                throw new \Exception("Statement preparation failed: " . mysqli_error($this->conn));
            }
            $types = str_repeat("s", count($values));
            mysqli_stmt_bind_param($stmt, $types, ...$values);
            if (!mysqli_stmt_execute($stmt)) {
                throw new \Exception("Execution failed: " . mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);
        } catch (\Exception $e) {
            error_log("An error occurred during DB insert: " . $e->getMessage());
        }
    }

    /**
     * Fetches data from a database table.
     */
    public function db_Out($dbTable, $what, $where = null, $params = [], $orderBy = ''): array
    {

        $sql = "SELECT $what FROM $dbTable";

        if ($where) {
            $sql .= " WHERE $where";
        }
        if ($orderBy) {
            $sql .= " ORDER BY " . $orderBy;
        }

        try {
            $stmt = mysqli_prepare($this->conn, $sql);
            if (!$stmt) {
                throw new Exception("Statement preparation failed: " . mysqli_error($this->conn));
            }
            if ($params) {
                $types = str_repeat("s", count($params));
                mysqli_stmt_bind_param($stmt, $types, ...$params);
            }
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Query execution failed: " . mysqli_stmt_error($stmt));
            }
            $result = mysqli_stmt_get_result($stmt);
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            mysqli_stmt_close($stmt);
            return $data;
        } catch (Exception $e) {
            error_log("An error occurred during DB out: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Updates records in a database table.
     */
    public function db_Set($dbTable, $data, $where, $params = []): void
    {
        if (!is_array($data) || empty($data)) {
            return;
        }

        $setPlaceholders = implode(", ", array_map(fn ($key) => "`$key` = ?", array_keys($data)));
        $values = array_values($data);
        $allParams = array_merge($values, $params);
        $query = "UPDATE `$dbTable` SET $setPlaceholders WHERE $where";

        try {
            $stmt = mysqli_prepare($this->conn, $query);
            if (!$stmt) {
                throw new Exception("Statement preparation failed: " . mysqli_error($this->conn));
            }
            $types = str_repeat("s", count($allParams));
            mysqli_stmt_bind_param($stmt, $types, ...$allParams);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Execution failed: " . mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);
        } catch (Exception $e) {
            error_log("An error occurred during DB set: " . $e->getMessage());
        }
    }

    /**
     * Deletes records from a database table.
     */
    public function db_Del($dbTable, $where, $params = []): void
    {
        $query = "DELETE FROM `$dbTable` WHERE $where";

        try {
            $stmt = mysqli_prepare($this->conn, $query);
            if (!$stmt) {
                throw new Exception("Statement preparation failed: " . mysqli_error($this->conn));
            }
            if ($params) {
                $types = str_repeat("s", count($params));
                mysqli_stmt_bind_param($stmt, $types, ...$params);
            }
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Execution failed: " . mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);
        } catch (Exception $e) {
            error_log("An error occurred during DB delete: " . $e->getMessage());
        }
    }

    public function closeConnection(): void
    {
        if ($this->conn) {
            mysqli_close($this->conn);
        }
    }
}

