<?php
  /**
   * Returns a PDO object connected to the database. If a PDOException is thrown when
   * attempting to connect to the database, responds with a 503 Service Unavailable 
   * error.
   *
   * @return {PDO} connected to the database upon a successful connection.
   */
  function get_PDO() {
    $host = "localhost";     # fill in with server name (e.g. localhost)
    $port = "3306";      # fill in with a port if necessary (will be different mac/pc)
    $user = "root";     # fill in with user name
    $password = "root"; # fill in with password (will be different mac/pc)
    $dbname = "cp5";   # fill in with db name containing your SQL tables

    # Make a data source string that will be used in creating the PDO object
    $ds = "mysql:host={$host}:{$port};dbname={$dbname};charset=utf8";

    try {
      $db = new PDO($ds, $user, $password);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $db;
    } catch (PDOException $ex) {
      output_error(503, "Error accessing database.");
    }
  }

  /**
   * Outputs the given message in plain text. Kills the script's execution.
   *
   * @param {string} $out - The message to output
   */
  function output_text($out) {
    header("Content-Type: text/plain");
    die($out);
  }

  /**
   * Outputs the given array in JSON format. Kills the script's execution.
   *
   * @param {string} $arr - The array to output
   */
  function output_json($arr) {
    header("Content-Type: application/json");
    die(json_encode($arr));
  }

  /**
   * Outputs an error with the given HTTP status code and message. Kills the script's execution.
   *
   * @param {int} $code - A valid HTTP status code
   * @param {string} $msg - The error message to output
   */
  function output_error($code, $msg) {
    http_response_code($code);
    output_text($msg);
  }
?>
