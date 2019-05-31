# OuttaTime&trade; API Documentation
A basic API backend for the _OuttaTime&trade; Time Waster_ game. Handles registration and
"authentication" of users, game logic, and storage of player high scores.

## /auth.php    [mode=login]
**Request Format:** /auth.php

**Request Type:** POST

**Returned Data Format**: Plain Text

**Description:** Logs in the user with the given username by responding with their UID.


**Example Request:** /auth.php

Form Data

| Key | Value |
|-----|-------|
|mode |login  |
|uname|mowgli |

**Example Response:**

```
petit1
```

**Error Handling:** Responds with HTTP 400 if no `uname` is given or the `uname` does not exist.

## /auth.php    [mode=signup]
**Request Format:** /auth.php

**Request Type:** POST

**Returned Data Format**: Plain Text

**Description:** Creates a new record for the user with the given username and email. The given
username must not already exist in the database. Responds with the new users's UID.


**Example Request:** /auth.php

Form Data

| Key | Value |
|-----|-------|
|mode |signup |
|uname|geoff  |
|email|me@test.com|

**Example Response:**

```
xyz987
```

**Error Handling:** Responds with HTTP 400 if one of the parameters is missing or if a player with
the given username already exists.

## /extra.php
**Request Format:** /extra.php

**Request Type:** POST

**Returned Data Format**: JSON

**Description:** Updates the email of the player with the given UID. The UID must already exist in
the database. Responds with the updated user database entry in JSON format.


**Example Request:** /extra.php

Form Data

| Key | Value |
|-----|-------|
|uid  |xyz987 |
|email|new@uw.edu|

**Example Response:**

```json
{
    "id": "9",
    "uname": "geoff",
    "email": "new@uw.edu",
    "uid": "xyz987"
}
```

**Error Handling:** Responds with HTTP 400 if no `uid` is given or the `uid` does not exist.

## /game.php
**Request Format:** /game.php?guess={color}

**Request Type:** GET

**Returned Data Format**: Plain Text

**Description:** Triggers the CPU to make a color choice, and checks if the player's choice matches
it. Responds with the string "true" if the CPU's choice matches the player, or "false" if it does not.


**Example Request:** /game.php?color=red

**Example Response:**

```
true
```

**Error Handling:** Responds with HTTP 400 for any other request type, if the guessed color is not
valid, or if the guess parameter is missing from a GET request.

## /scores.php
**Request Format:** /scores.php

**Request Type:** GET

**Returned Data Format**: JSON

**Description:** Returns the top 10 scores on the leaderboard as a JSON array sorted in descending
order by score. If there are fewer than 10 scores in the database all of the scores are returned.


**Example Request:** /scores.php

**Example Response:**

```json
[
    {
        "uname": "mowgli",
        "score": "17",
        "time": "2019-06-05 22:22:23"
    },
    {
        "uname": "austin",
        "score": "15",
        "time": "2019-05-29 14:44:27"
    },
    {
        "uname": "oposdeo",
        "score": "14",
        "time": "2019-06-02 13:22:15"
    }
]
```

## /scores.php  [add]
**Request Format:** /scores.php

**Request Type:** POST

**Returned Data Format**: Plain Text

**Description:** Adds the given score to the database associated with the given player UID. Does not
do any checking of the score. Responds with the string "Ok" to show that the requests was
successful.


**Example Request:** /scores.php

Form Data

| Key | Value |
|-----|-------|
|uid  |xyz987 |
|score|5      |

**Example Response:**

```
Ok
```

**Error Handling:** Responds with HTTP 400 if missing parameters or the uid does not exist.
