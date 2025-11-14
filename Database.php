<?php

require_once "config.php";

require_once "send_tg_msg.php";

class Database
{

  public $conn;

  // Connect to DB
  public function __construct()
  {
    $this->conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$this->conn) {
      die("Connection failed: " . mysqli_connect_error());
    }
  }


  public function check_connection()
  {
    print_r($this->conn);
  }

  // CREATE record
  public function create($table, $columns, $values)
  {
    $cols = implode(",", $columns);
    $vals = implode("','",  $values);
    $sql = "INSERT INTO $table ($cols) VALUES ('$vals')";
    return mysqli_query($this->conn, $sql);
  }

  // READ with filters (LIKE, OR, LIMIT, OFFSET)
  public function read($table, $filters = [])
  {
    $sql = "SELECT * FROM $table";

    // WHERE conditions
    $whereParts = [];

    if (isset($filters['where']) && is_array($filters['where'])) {
      foreach ($filters['where'] as $col => $condition) {
        foreach ($condition as $op => $val) {
          $whereParts[] = "$col $op '$val'";
        }
      }
    }

    // OR WHERE conditions
    if (isset($filters['or_where']) && is_array($filters['or_where'])) {
      $orParts = [];
      foreach ($filters['or_where'] as $col => $condition) {
        foreach ($condition as $op => $val) {
          $orParts[] = "$col $op '$val'";
        }
      }
      if (!empty($orParts)) {
        $whereParts[] = "(" . implode(" OR ", $orParts) . ")";
      }
    }

    // Append WHERE clause
    if (!empty($whereParts)) {
      $sql .= " WHERE " . implode(" AND ", $whereParts);
    }

    // ORDER BY
    if (isset($filters['order_by'])) {
      $sql .= " ORDER BY " . $filters['order_by'];
    }

    // LIMIT
    if (isset($filters['limit'])) {
      $sql .= " LIMIT " . intval($filters['limit']);
    }

    // OFFSET
    if (isset($filters['offset'])) {
      $sql .= " OFFSET " . intval($filters['offset']);
    }

    $result = mysqli_query($this->conn, $sql);
    return $result;
  }

  // UPDATE
  public function update($table, $columns, $values, $whereColumn, $whereValue)
  {
    $set = [];
    foreach ($columns as $index => $col) {
      $set[] = "$col = '$values[$index]'";
    }
    $setString = implode(", ", $set);
    $sql = "UPDATE $table SET $setString WHERE $whereColumn = '$whereValue'";
    return mysqli_query($this->conn, $sql);
  }

  // DELETE
  public function delete($table, $whereColumn, $whereValue)
  {
    $sql = "DELETE FROM $table WHERE $whereColumn = '$whereValue'";
    return mysqli_query($this->conn, $sql);
  }

  // Close DB connection
  public function __destruct()
  {
    mysqli_close($this->conn);
  }

  // custom query
  public function custom_query($sql)
  {
    return mysqli_query($this->conn, $sql);
  }
}
session_start();

$DB  =  new Database();
