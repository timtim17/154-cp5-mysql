<?php
    /**
     * Austin Jenchi
     * CSE 154 19sp AQ
     * 05/31/2019
     * Makes a random decision for a color, and checks if the given player guess matches
     * the guess made by the computer.
     *
     * API Doc:
     * ============================================================================================
     * GET /game.php?guess={color}
     *      Parameters:
     *          - color: a valid color guess, either "red" or "blue" (case-sensitive)
     *      Response: Plain Text
     *          Responds the string "true" if the player's guess matches the computer's choice, or
     *          "false" if it does not.
     *
     * Responds with HTTP 400 for any other request type, if the guessed color is not valid, or
     * if the guess parameter is missing from a GET request.
     */

    include "common.php";

    define("VALID_COLORS", array("red", "blue"));
    
    if (isset($_GET["guess"])) {
        $color = $_GET["guess"];
        if (in_array($color, VALID_COLORS)) {
            if ($color === VALID_COLORS[array_rand(VALID_COLORS)]) {
                output_text("true");
            } else {
                output_text("false");
            }
        } else {
            output_error(400, "Invalid guess - must be either 'red' or 'blue'.");
        }
    } else {
        output_error(400, "Invalid request type or guess parameter missing.");
    }
?>
