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
    } else if ($extension === 'cs') {
        exec("dotnet script $filename", $output);
    } else if ($extension === 'rb') {
        exec("ruby $filename", $output);
    } else if ($extension === 'go') {
        exec("go run $filename", $output);
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

    $files = glob(__DIR__ . '/sso/sso*');
    $key = "sso_private_key";

    echo "Testing SSO\n";

    foreach ($files as $filename) {
        $output = runFile($filename);

        $json = json_decode($output, true);

        $user = $json['user'];
        $hash = $json['hash'];

        $checkHash = hash_hmac('sha256', $user, $key);

        if ($hash !== $checkHash) {
            throw new Exception('Hash does not match');
        }

        $user = base64_decode($user);
        $json = json_decode($user, true);

        if (!isset($json['timestamp'])) {
            throw new Exception('Timestamp not set');
        }

        if ($json['id'] !== 1) {
            throw new Exception('ID does not match');
        }

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        echo "$ext: Success\n";
    }

}


testEncryption();
testSso();