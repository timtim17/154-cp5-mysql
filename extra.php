<?php
    /**
     * Austin Jenchi
     * CSE 154 19sp AQ
     * 05/31/2019
     * Extra PHP script to add extra functionality for late day challenge. Provides an endpoint
     * to update the email of a user in the database.
     *
     * API Doc:
     * ============================================================================================
     * POST /extra.php
     *      Body: form-data
     *          - uid: The uid of the player
     *          - email: The player's new email
     *      Response Type: JSON
     *          The player's updated record.
     *      Responds with HTTP 400 if no uid is given or the uid does not exist.
     */

    include "common.php";

    if (isset($_POST["uid"])) {
        $uid = $_POST["uid"];
        $pdo = get_PDO();
        if (contains_uid($pdo, $uid)) {
            if (isset($_POST["email"])) {
                $email = $_POST["email"];
                update_email($pdo, $uid, $email);
                output_json(get_player_record($pdo, $uid));
            } else {
                output_error(400, "Parameter email not provided.");
            }
        } else {
            output_error(400, "Given UID not found.");
        }
    } else {
        output_error(400, "Parameter uid not provided.");
    }

    /**
     * Checks if the given uid is in the database. Does not make the assumption that the given
     * UID is safe.
     *
     * @param {PDO} $pdo - The PDO connection to the database
     * @param {string} $uid - The uid to look for
     * @return {boolean} - Whether or not the uid is in the database
     */
    function contains_uid($pdo, $uid) {
        $stmt = $pdo->prepare("SELECT uid FROM players WHERE uid = :uid LIMIT 1");
        $stmt->execute(array("uid" => $uid));
        return $stmt->fetch() !== false;
    }

    /**
     * Updates the email of the player with the given UID. Assumes that the given UID is valid.
     *
     * @param {PDO} $pdo - The PDO connection to the database
     * @param {string} $uid - The uid of the player to update
     * @param {string} $email - The user's new email
     */
    function update_email($pdo, $uid, $email) {
        $stmt = $pdo->prepare("UPDATE players SET email = :email WHERE uid = :uid");
        $stmt->execute(array(
            "uid" => $uid,
            "email" => $email
        ));
    }

    /**
     * Gets the given player record as an associative array. Assumes that the given UID exists.
     *
     * @param {PDO} $pdo - The PDO connection to the database
     * @param {string} $uid - A valid uid
     */
    function get_player_record($pdo, $uid) {
        $stmt = $pdo->prepare("SELECT * FROM players WHERE uid = :uid");
        $stmt->execute(array("uid" => $uid));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
?>
