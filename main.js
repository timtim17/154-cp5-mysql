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

        let chars = [];

        let linkImg = new Image();
        linkImg.src = "img/sprites/link.png";
        linkImg.addEventListener("load", () => {
            chars.push(new Character(linkImg, 41, 48, 41, 83, 73, 48, 4, 4, 3, 66, 194, 322, 62, 45, 49, 82));
        });

        let pikaImg = new Image();
        pikaImg.src = "img/sprites/pikachu.png";
        pikaImg.addEventListener("load", () => {
            chars.push(new Character(pikaImg, 39, 34, 39, 85, 69, 27, 4, 4, 3, 83, 211, 339, 45, 43, 58, 88));
        });

        let corrinImg = new Image();
        corrinImg.src = "img/sprites/corrin.png";
        corrinImg.addEventListener("load", () => {
            chars.push(new Character(corrinImg, 40, 25, 40, 86, 62, 15, 4, 4, 3, 59, 187, 315, 69, 42, 66, 99));
        });

        setInterval(render, 100, ctx, chars);
        render(ctx, chars);
    }


    let t = 0;
    /**
     * 
     * @param {*} ctx 
     * @param {Character[]} chars 
     */
    function render(ctx, chars) {
        ctx.fillStyle = "#ffffff";
        ctx.fillRect(0, 0, ctx.canvas.width, ctx.canvas.height);
        ctx.imageSmoothingEnabled = false;
        let i = 0;
        let y = 200 * Math.sin(t) + 400;
        t += 0.025;
        chars.forEach(char => char.drawSprite(ctx, 200 + 200 * i++, y));
        ctx.fillStyle = "#000000";
        ctx.fillRect(0, y, ctx.canvas.width, 50);
    }

    /**
     * 
     */
    class Character {
        /**
         * 
         * @param {*} spritesheet 
         * @param {*} idleOffset 
         * @param {*} moveOffset 
         * @param {*} atkOffset 
         * @param {*} idleShift 
         * @param {*} moveShift 
         * @param {*} atkShift 
         * @param {*} numIdle 
         * @param {*} numMove 
         * @param {*} numAtk 
         * @param {*} yIdle 
         * @param {*} yMove 
         * @param {*} yAtk 
         * @param {*} height 
         * @param {*} idleWidth 
         * @param {*} moveWidth 
         * @param {*} atkWidth 
         */
        constructor(spritesheet, idleOffset, moveOffset, atkOffset, idleShift, moveShift, atkShift,
                    numIdle, numMove, numAtk, yIdle, yMove, yAtk, height, idleWidth, moveWidth,
                    atkWidth) {
            this.spritesheet = spritesheet;
            this.sprites = {};
            this.sprites[STATE_IDLE] =
                splitSpritesheet(idleOffset, idleShift, numIdle, yIdle, idleWidth, height);
            this.sprites[STATE_MOVE] =
                splitSpritesheet(moveOffset, moveShift, numMove, yMove, moveWidth, height);
            this.sprites[STATE_ATK] =
                splitSpritesheet(atkOffset, atkShift, numAtk, yAtk, atkWidth, height);
            this.state = STATE_MOVE;
            this.curSprite = 0;
        }

        /**
         * 
         * @param {*} newState 
         */
        setState(newState) {
            this.state = newState;
        }

        /**
         * 
         * @param {*} ctx 
         */
        drawSprite(ctx, x, y) {
            let curSprite = this.sprites[this.state][this.curSprite];
            ctx.drawImage(this.spritesheet, curSprite.x, curSprite.y, curSprite.width,
                curSprite.height, x, y - curSprite.height * 2.5, curSprite.width * 2.5, curSprite.height * 2.5);
            if (this.curSprite >= this.sprites[this.state].length - 1) {
                this.curSprite = 0;
            } else {
                this.curSprite++;
            }
        }
    }

    /**
     * 
     * @param {*} offset 
     * @param {*} shift 
     * @param {*} num 
     * @param {*} y
     * @param {*} width 
     * @param {*} height 
     * @return {array} - an array of object representing the x, y, width, and height of a subsection
     *                      of the spritesheet that the sprite is located at
     */
    function splitSpritesheet(offset, shift, num, y, width, height) {
        let res = [];
        for (let i = 0; i < num; i++) {
            res.push({
                x: offset + i * (width + shift),
                y: y,
                width: width,
                height: height
            });
        }
        return res;
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
