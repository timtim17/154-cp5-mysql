/**
 * 
 */
(function() {
    "use strict";

    const STATE_IDLE = "idle";
    const STATE_MOVE = "move";
    const STATE_ATK = "attack";

    window.addEventListener("load", init);

    /**
     * 
     */
    function init() {
        let canvas = qs("canvas");
        let ctx = canvas.getContext("2d");
        let canvasCompStyles = window.getComputedStyle(canvas);
        canvas.width = parseInt(canvasCompStyles.width);
        canvas.height = parseInt(canvasCompStyles.height);
        setInterval(render, 200, ctx);
        render(ctx);
    }

    let i = 0;

    /**
     * 
     * @param {*} ctx 
     */
    function render(ctx) {
        ctx.fillStyle = "#ffffff";
        ctx.imageSmoothingEnabled = false;
        if (i >= 3) {
            i = 0;
        } else {
            i++;
        }
        let img = new Image();
        img.addEventListener("load", () => {
            ctx.fillRect(0, 0, ctx.canvas.width, ctx.canvas.height);
            ctx.drawImage(img, 100, 100, img.width * 2.5, img.height * 2.5);
        });
        img.src = "img/link/idle_" + i + ".png";
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
