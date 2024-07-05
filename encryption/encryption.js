/**
 * This depends on the crypto-js library
 * See https://www.npmjs.com/package/crypto-js
 */

const CryptoJS = require("crypto-js");

function encrypt() {
    /**
     * This is the base64 encoded key from Console -> Settings -> API -> Encryption Key
     * Ideally, this should be stored in a secure location and not in the codebase. ex: env variable
     */
    const ENCRYPTION_KEY = "v4gaj6ELt8p5+aLuBeNbRW45BAkHQzAfC2sExq1Elr4=";
    const key = CryptoJS.enc.Base64.parse(ENCRYPTION_KEY);

    const data = {
        /**
         * Current UNIX timestamp in seconds
         * This is used to ensure that the request is not replayed
         */
        timestamp: Math.floor(Date.now() / 1000),

        /**
         * Below, you would set the other data you want to set
         * See our documentation what keys are required in each component
         */
        "page-id": "my-page-id-3",
    };

    /**
     * Convert the data to a JSON string
     */
    const json = JSON.stringify(data);

    /**
     * Generate a random IV (Initialization Vector) for each encryption
     * This is used to ensure that the same data encrypted multiple times will have different results
     */
    const iv = CryptoJS.lib.WordArray.random(16);

    /**
     * Encrypt the data
     */
    const encrypted = CryptoJS.AES.encrypt(json, key, {
        iv: iv,
    });

    /**
     * Finally, return the encrypted data (base64 encoded) and the IV (base64 encoded)
     * Connect the two with a :
     */
    return encrypted.toString() + ":" + iv.toString(CryptoJS.enc.Base64);
}

console.log(encrypt());
