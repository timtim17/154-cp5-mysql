<?php
    /**
     * Austin Jenchi
     * CSE 154 19sp AQ
     * 05/31/2019
     * Manages scoreboard and adding scores to the database.
     *
     * API Doc:
     * ============================================================================================
     * POST /scores.php
     *      Body: form-data
     *          - uid: The uid of the player
     *          - score: The player's score
     *      Response Type: Plain Text
     *          The string "Ok" to show the request was successful.
     *      Responds with HTTP 400 if missing parameters or the uid does not exist.
     * GET /scores.php
     *      Response Type: JSON
     *          The top 10 scores in a JSON array sorted in descending order. If fewer than 10
     *          entries exist all are returned.
     */

    include "common.php";

    define("NUM_RECORDS", 10);

    $pdo = get_PDO();
    if (isset($_POST["uid"])) {
        $uid = $_POST["uid"];
        if (contains_uid($pdo, $uid)) {
            if (isset($_POST["score"])) {
                $score = $_POST["score"];
                add_score($pdo, $uid, $score);
                output_text("Ok");
            } else {
                output_error(400, "Parameter score not provided.");
            }
        } else {
            output_error(400, "Given UID not found.");
        }
    } else {
        output_json(get_top_scores($pdo));
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
     * Adds a score to the database. Assumes that the given UID is valid. Does not do any checking
     * on the validity of the score.
     *
     * @param {PDO} $pdo - The PDO connection to the database
     * @param {string} $uid - The uid of the player
     * @param {string} $score - The player's score
     */
    function add_score($pdo, $uid, $score) {
        $stmt = $pdo->prepare("INSERT INTO scores(uid, score, time) VALUES (:uid, :score, NOW())");
        $stmt->execute(array(
            "uid" => $uid,
            "score" => $score
        ));
    }

    /**
     * Fetches the top scores on the leaderboard and returns them as an array of sorted descending
     * associative arrays.
     *
     * @param {PDO} $pdo - The PDO connection to the database
     * @return {Array} - The top NUM_RECORDS scores sorted in descending order
     */
    function get_top_scores($pdo) {
        return $pdo->query("SELECT players.uname, scores.score, scores.time
                            FROM scores LEFT JOIN players
                            ON players.uid = scores.uid
                            ORDER BY scores.score DESC
                            LIMIT " . NUM_RECORDS)
                    ->fetchAll(PDO::FETCH_ASSOC);
    }
?>
