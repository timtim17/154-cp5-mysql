-- Austin Jenchi
-- CSE 154 19sp AQ
-- 05/29/2019
-- Sets up basic tables for tracking players of the game and their high scores.
-- Also inserts some basic user and score data.

USE cp5;
DROP TABLE IF EXISTS players;
DROP TABLE IF EXISTS scores;

CREATE TABLE players(
    id INT NOT NULL AUTO_INCREMENT,
    uname VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    uid VARCHAR(6) NOT NULL,
    `character` VARCHAR(7) NOT NULL,
    PRIMARY KEY(id)
);

INSERT INTO players(uname, email, uid, `character`)
    VALUES ("austin", "ajenchi@uw.edu", "123abc", "link"),
           ("EndenDragon", "enden@titan.com", "4ghi56", "corrin"),
           ("oposdeo", "sven@cse154.edu", "fez100", "link"),
           ("mowgli", "mowgli@cse154.edu", "petit1", "pikachu");

CREATE TABLE scores(
    id INT NOT NULL AUTO_INCREMENT,
    uid VARCHAR(6) NOT NULL,
    score INT NOT NULL,
    time DATETIME NOT NULL,
    PRIMARY KEY(id)
);

INSERT INTO scores(uid, score, time)
    VALUES ("123abc", 15, "2019-05-29 14:44:27"),
           ("fez100", 14, "2019-06-02 13:22:15"),
           ("petit1", 17, "2019-06-05 22:22:23");
