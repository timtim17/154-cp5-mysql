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
?>
