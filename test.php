<?php

function runFile(string $filename)
{
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    $output = [];

    if ($extension === 'php') {
        exec("php $filename", $output);
    } else if ($extension === 'js') {
        exec("node $filename", $output);
    } else if ($extension === 'py') {
        exec("python3 $filename", $output);
    } else if ($extension === 'java') {
        exec("java -cp .:lib/org.json.jar $filename", $output);
    } else {
        throw new Exception('Unsupported file type');
    }

    return $output[0];
}

function testEncryption()
{

    $files = glob(__DIR__ . '/encryption/encryption*');
    $key = "v4gaj6ELt8p5+aLuBeNbRW45BAkHQzAfC2sExq1Elr4=";

    echo "Testing Encryption\n";

    foreach ($files as $filename) {
        $output = runFile($filename);

        [$encrypted, $iv] = explode(':', $output);
        $decrypted = openssl_decrypt(base64_decode($encrypted), 'aes-256-cbc', base64_decode($key), OPENSSL_RAW_DATA, base64_decode($iv));
        $json = json_decode($decrypted, true);

        if (!isset($json['timestamp'])) {
            throw new Exception('Timestamp not set');
        }

        if (!is_int($json['timestamp'])) {
            throw new Exception('Timestamp is not an integer');
        }

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        echo "$ext: Success\n";
    }

}

function testSso()
{
    // TODO
}


testEncryption();
testSso();