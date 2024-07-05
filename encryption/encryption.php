<?php

/**
 * Encrypt the data using AES-256-CBC
 */
function encrypt()
{

    /**
     * This is the base64 encoded key from Console -> Settings -> API -> Encryption Key
     * Ideally, this should be stored in a secure location and not in the codebase. ex: env variable
     */
    $key = "v4gaj6ELt8p5+aLuBeNbRW45BAkHQzAfC2sExq1Elr4=";


    $data = [
        /**
         * Current UNIX timestamp in seconds
         * This is used to ensure that the request is not replayed
         */
        'timestamp' => time(),

        /**
         * Below, you would set the other data you want to set
         * See our documentation what keys are required in each component
         */
        'page-id' => 'my-page-id',
    ];

    /**
     * Convert the data to a JSON string
     */
    $data = json_encode($data);


    /**
     * Generate a random IV (Initialization Vector) for each encryption
     * This is used to ensure that the same data encrypted multiple times will have different results
     */
    $iv = openssl_random_pseudo_bytes(16);


    /**
     * Encrypt the data
     */
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', base64_decode($key), OPENSSL_RAW_DATA, $iv);

    /**
     * Finally, return the encrypted data (base64 encoded) and the IV (base64 encoded)
     * Connect the two with a :
     */

    return base64_encode($encrypted) . ':' . base64_encode($iv);

}


echo encrypt() . "\n";