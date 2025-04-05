<?php

$globalKey = 'z,iydFv9bd';
function encryptData($data) {
    global $globalKey;
    // Generate an initialization vector
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    
    // Encrypt the data using AES-256-CBC cipher
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $globalKey, 0, $iv);
    
    // Concatenate the IV with the encrypted data
    $encryptedData = base64_encode($iv . $encrypted);
    
    return $encryptedData;
}

function decryptData($encryptedData) {
    global $globalKey;;
    // Decode the base64 encoded string
    $encryptedData = @base64_decode($encryptedData);
    
    // Extract the IV and encrypted data
    $iv = substr($encryptedData, 0, openssl_cipher_iv_length('aes-256-cbc'));
    $data = substr($encryptedData, openssl_cipher_iv_length('aes-256-cbc'));
    
    // Decrypt the data using AES-256-CBC cipher
    $decrypted = @openssl_decrypt($data, 'aes-256-cbc', $globalKey, 0, $iv);
    
        // Check if decryption was successful
        if ($decrypted === false) {
            return ''; // fail to decrypt
        }else{
            return $decrypted;
        }
    
}



?>
