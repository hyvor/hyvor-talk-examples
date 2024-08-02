<?php

function getUserSso()
{

    /**
     * Construct the user object
     * See: https://talk.hyvor.com/docs/sso-stateless#user-object for available properties
     */
    $user = [
        'timestamp' => time(),

        'id' => 1,
        'name' => 'John Doe',
        'email' => 'john@doe.com',

        'picture_url' => null,
        'website_url' => null,
        'bio' => null,
        'location' => null
    ];

    /**
     * Replace this with your SSO private key from Console -> Settings -> SSO
     * Use an environment variable or a secure location to store this
     */
    $key = 'sso_private_key';

    /**
     * Encode the user object to JSON
     */
    $user = json_encode($user);

    /**
     * Base64 encode the user object
     * Add this as the `sso-user` attribute
     */
    $user = base64_encode($user);

    /**
     * Generate a hash using HMAC-SHA256
     * Add this as the `sso-hash` attribute
     */
    $hash = hash_hmac('sha256', $user, $key);

    return [
        'user' => $user,
        'hash' => $hash
    ];

}

echo json_encode(getUserSso()) . "\n";