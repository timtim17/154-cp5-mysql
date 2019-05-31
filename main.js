/**
 * Austin Jenchi
 * CSE 154 19sp AQ
 * 
 */
(function() {
    "use strict";

    const URL_AUTH = "auth.php";
    const URL_GAME = "game.php";
    const URL_SCORES = "scores.php";
    const CLASS_HIDDEN = "hidden";

    let uid = undefined;

    window.addEventListener("load", init);

    /**
     * Runs on page load. Registers event listeners with the authentication form, as well
     * as sets up the listeners for the elements for the game. These however should not be
     * functional on page load since they are hidden by default.
     */
    function init() {
        id("form-login").addEventListener("submit", handleFormLogin);
        id("form-signup").addEventListener("submit", handleFormSignup);
        qsa("#view-game > button").forEach(btn => btn.addEventListener("click", makeGuess));
        id("btn-again").addEventListener("click", startGame);
    }

    /**
     * @param {HTMLFormEvent} event - The event from submitting the form
     */
    function handleFormLogin(event) {
        // this is the form element
        event.preventDefault();
        let body = new FormData(this);
        body.append("mode", "login");
        fetch(URL_AUTH, { method: "POST", body: body })
            .then(checkStatus)
            .then(resp => uid = resp)
            .then(switchViewGame)
            .catch(() => showError(qs("#view-auth .error-text"),
                "There was an issue logging in... are you sure you are registered?"));
    }

    /**
     * @param {HTMLFormEvent} event - The event from submitting the form
     */
    function handleFormSignup(event) {
        // this is the form element
        event.preventDefault();
        let body = new FormData(this);
        body.append("mode", "signup");
        fetch(URL_AUTH, { method: "POST", body: body })
            .then(checkStatus)
            .then(resp => uid = resp)
            .then(switchViewGame)
            .catch(() => showError(qs("#view-auth .error-text"),
                "There was an issue logging in... are you sure you are registered?"));
    }

    /**
     * Switches the view from the auth form to the game. This should only be run once. This
     * assumes we have an authed user and that uid is no longer undefined.
     */
    function switchViewGame() {
        id("view-auth").classList.add(CLASS_HIDDEN);
        id("view-game").classList.remove(CLASS_HIDDEN);
        startGame();
    }

    /**
     * Starts a new round. Resets the number of correct guesses to 0, and enables the game
     * buttons. Also hides the game over section.
     */
    function startGame() {
        qsa("#view-game > button").forEach(btn => btn.disabled = false);
        id("guessed").innerText = 0;
        id("game-over").classList.add(CLASS_HIDDEN);
    }

    /**
     * Makes a guess. This should be called as an event listener of a guess button. The button
     * should have an id matching "btn-{color name}". That color name is sent to the backend, which
     * makes a decision on whether it matches its (random) choice and returns "true"/"false" back
     * in response. Then the UI is updated with either another correct guess counted or the game
     * being over.
     */
    function makeGuess() {
        // this is a guess button element
        let color = this.id.substring(this.id.indexOf("-") + 1);
        fetch(URL_GAME + "?guess=" + color)
            .then(checkStatus)
            .then(resp => resp === "true")
            .then(handleGuess)
            .then(() => clearError(qs("#view-game .error-text")))
            .catch(() => showError(qs("#view-game .error-text"),
                "There was an issue making that guess... try again?"));
    }

    /**
     * Handles a response from the game API. Given a boolean whether or not the game API guessed
     * the same as the player, update the UI. Either another correct guess is added to the score
     * or the game over routine begins.
     *
     * @param {boolean} guessCorrect - Whether or not the API found the guess to be correct
     */
    function handleGuess(guessCorrect) {
        if (guessCorrect) {
            let ele = id("guessed");
            ele.innerText = parseInt(ele.innerText) + 1;
        } else {
            qsa("#view-game > button").forEach(btn => btn.disabled = true);
            id("game-over").classList.remove(CLASS_HIDDEN);
        }
    }

    /**
     * Show the given error in the given element.
     *
     * @param {HTMLElement} ele - The element to show the error in
     * @param {strung} msg - The error message to show
     */
    function showError(ele, msg) {
        ele.innerText = msg;
    }

    /**
     * Clears the error of the given element, effectively hiding it.
     *
     * @param {HTMLElement} ele - The element to clear the error of
     */
    function clearError(ele) {
        ele.innerText = "";
    }

    /* CSE 154 HELPER FUNCTIONS */

    /**
     * Returns the DOM element with the given id.
     *
     * @param {string} id - The id to search for
     * @returns {HTMLElement} The element with that id
     */
    const id = id => document.getElementById(id);

    /**
     * Returns the first element in the DOM tree that matches the given selector.
     *
     * @param {string} selector - The selector to search with
     * @returns {HTMLElement} The first element in the DOM that matches that selector
     */
    const qs = selector => document.querySelector(selector);

    /**
     * Returns all the elements in the DOM that match the given selector.
     *
     * @param {string} selector - The selector to search with
     * @returns {HTMLElement[]} All elements in the DOM that match that selector
     */
    const qsa = selector => document.querySelectorAll(selector);

    /**
     * Helper function to return the response's result text if successful, otherwise
     * returns the rejected Promise result with an error status and corresponding text
     *
     * @param {object} response - response to check for success/error
     * @returns {object} - valid result text if response was successful, otherwise rejected
     *                     Promise result
     */
    function checkStatus(response) {
        if (response.status >= 200 && response.status < 300) {
            return response.text();
        } else {
            return Promise.reject(new Error(response.status + ": " + response.statusText));
        }
    }
})();
