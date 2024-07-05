<?php

# This is a manual testing script
# Paste the generated secure attribute in $secure_attr and run the script

foreach (glob(__DIR__ . '/encryption*') as $filename) {
    print $filename . "\n";
}


// $key = "v4gaj6ELt8p5+aLuBeNbRW45BAkHQzAfC2sExq1Elr4=";
// $secure_attr = "JwoRGh+PALbip8/3pBOUGxIxitq3020+w7dy7rRUOKKMtG6c4GS/nxVQMtzogCfBWzcrm6W7HPrTQkqwuxYbsA==:m9r0SUXPFkeMzinvTNaP1g==";

// [$encrypted, $iv] = explode(':', $secure_attr);

// $decrypted = openssl_decrypt(base64_decode($encrypted), 'aes-256-cbc', base64_decode($key), OPENSSL_RAW_DATA, base64_decode($iv));

// echo $decrypted . "\n";