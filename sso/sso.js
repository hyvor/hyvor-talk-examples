// HMAC SHA256 hash
const CryptoJS = require("crypto-js");

function getSsoHash() {
    /**
     * Construct the user object
     * See: https://talk.hyvor.com/docs/sso-stateless#user-object for available properties
     */
    let userData = {
        timestamp: Math.floor(Date.now() / 1000),
        id: 1,
        name: "John Doe",
        email: "john@doe.com",
    };

    // 1. JSON encoding
    userData = JSON.stringify(userData);

    // 2. Base64 encoding
    userData = Buffer.from(userData).toString("base64");

    /**
     * Replace this with your SSO private key from Console -> Settings -> SSO
     * Use an environment variable or a secure location to store this
     */
    const SSO_PRIVATE_KEY = "sso_private_key";

    const hash = CryptoJS.HmacSHA256(userData, SSO_PRIVATE_KEY);

    return {
        user: userData, // sso-user attribute
        hash: hash.toString(), // sso-hash attribute
    };
}

console.log(JSON.stringify(getSsoHash()));
