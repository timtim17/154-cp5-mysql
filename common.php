<?php
  /**
   * CSE 154
   * common.php starter code for CP5. You may add more "common" functions to this if you would 
   * like, but this will help get started with getting your PDO connection (more information 
   * in Friday's lecture and its reading).
   *
   * TODO: Implement the 2 TODOS to use this common.php file for CP5:
   * 1. Fill in server variables found on MAMP home page to connect PDO to database
   * 2. Handle a DB connection error with a HTTP/1.1 503 Service Unavailable error
   *
   * Remember to use include("common.php") at the top of any PHP file that wants to 
   * use these function(s)!
   */

  /**
   * Returns a PDO object connected to the database. If a PDOException is thrown when
   * attempting to connect to the database, responds with a 503 Service Unavailable 
   * error.
   * @return {PDO} connected to the database upon a successful connection.
   */
  function get_PDO() {
    # Variables for connections to the database.
    # TODO: Replace with your server (e.g. MAMP) variables as shown in lecture on Friday.
    $host = "";     # fill in with server name (e.g. localhost)
    $port = "";      # fill in with a port if necessary (will be different mac/pc)
    $user = "";     # fill in with user name
    $password = ""; # fill in with password (will be different mac/pc)
    $dbname = "";   # fill in with db name containing your SQL tables

    # Make a data source string that will be used in creating the PDO object
    $ds = "mysql:host={$host}:{$port};dbname={$dbname};charset=utf8";

    try {
      $db = new PDO($ds, $user, $password);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $db;
    } catch (PDOException $ex) {
      # TODO: You must handle a DB error (503 Service Unavailable) if an error occurs.
    }
  }
?>
