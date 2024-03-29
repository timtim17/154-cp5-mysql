<?php
    /**
     * Austin Jenchi
     * CSE 154 19sp AQ
     * 05/31/2019
     * Backend script to handle registration of new users and authenticating existing users.
     * Queries the database to check for existing users and inserts new rows for new ones.
     *
     * API Doc:
     * ============================================================================================
     * POST /auth.php   [mode=login]
     *      Body: form-data
     *          - mode: the mode of the request (should be login)
     *          - uname: the user's username
     *      Response Type: Plain Text
     *          The user's uid if they exist.
     *      Responds with HTTP 400 if no uname is given or the uname does not exist.
     * POST /auth.php   [mode=signup]
     *      Body: form-data
     *          - mode: the mode of the request (should be signup)
     *          - uname: the user's username
     *          - email: the user's email
     *      Response Type: Plain Text
     *          The user's uid
     *      Responds with HTTP 400 if one of the parameters is missing or if a player with the
     *          given username already exists.
     */

    include "common.php";

    if (isset($_POST["mode"])) {
        $mode = $_POST["mode"];
        if (isset($_POST["uname"])) {
            $uname = $_POST["uname"];
            $pdo = get_PDO();
            if ($mode === "login") {
                $uid = get_uid($pdo, $uname);
                if ($uid) {
                    output_text($uid["uid"]);
                } else {
                    output_error(400, "No uid exists for the given username.");
                }
            } else if ($mode === "signup") {
                if (isset($_POST["email"])) {
                    $email = $_POST["email"];
                    output_text(register($pdo, $uname, $email));
                } else {
                    output_error(400, "Parameter email not provided.");
                }
            } else {
                output_error(400, "Invalid mode provided.");
            }
        } else {
            output_error(400, "Parameter uname not provided.");
        }
    } else {
        output_error(400, "Required parameter mode not provided.");
    }

    /**
     * Queries the database to get the UID of the given user by name.
     *
     * @param {PDO} $pdo - The PDO connection to the database
     * @param {string} $uname - The username of the player to query for
     * @return {array|boolean} - Either an associative array containing the uid at the key "uid",
     *                           or FALSE if the user does not exist.
     */
    function get_uid($pdo, $uname) {
        # select the player's row, limit 1 (it should be unique)
        $stmt = $pdo->prepare("SELECT uid FROM players WHERE uname = :uname LIMIT 1");
        $stmt->execute(array("uname" => $uname));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Registers the given user via their username and email. The username must be unique, not
     * already seen in the database. If the username is duplicate, a HTTP 400 error is thrown and
     * the execution is killed.
     *
     * @param {PDO} $pdo - The PDO connection to the database
     * @param {string} $uname - The username of the player to add
     * @param {email} $email - The email of the player to add
     */
    function register($pdo, $uname, $email) {
        $stmt = $pdo->prepare("INSERT INTO players(uname, email, uid)
            VALUES (:name, :email, :uid)");
        try {
            $uid = generate_unique_uid($pdo);
            $stmt->execute(array(
                "name" => $uname,
                "email" => $email,
                "uid" => $uid
            ));
            return $uid;
        } catch (PDOException $ignored) {
            output_error(400, "Duplicate uname provided.");
        }
    }

    /**
     * Generates a random UID using MD5 hashes of random numbers. Ensures that the UID is not
     * already in the database.
     *
     * @param {PDO} $pdo - The PDO connection to the database
     * @return {string} - The generated uid
     */
    function generate_unique_uid($pdo) {
        $res = null;
        while ($res === null || contains_uid($pdo, $res)) {
            $hash = md5(rand());
            $res = substr($hash, rand(0, strlen($hash) - 7), 6);
        }
        return $res;
    }

    /**
     * Checks if the given uid is in the database. It is assumed that the given UID is safe,
     * generated by the script and not possible to use for SQL injection.
     *
     * @param {PDO} $pdo - The PDO connection to the database
     * @param {string} $uid - The uid to look for
     * @return {boolean} - Whether or not the uid is in the database
     */
    function contains_uid($pdo, $uid) {
        return $pdo
            ->query("SELECT uid FROM players WHERE uid = '{$uid}' LIMIT 1")
            ->fetch() !== false;
    }
?>
