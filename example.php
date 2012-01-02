<?php

// TODO : real data

// Generate key-pair and encrypt it
$message = 'Hello, World!';
$key_length = 1024;
$RSA = new RSA();
$RSA->generateKeys($key_length);
$encrypted_message = $RSA->encrypt($message);

// Encrypt with public key
$message = 'Hello, World!';
$RSA = new RSA();
$RSA->public_key = '3';
$RSA->modulus = '9173503';
$encrypted_message = $RSA->encrypt($message);

// Decrypt with private key
$encrypted_message = 'bla';
$RSA = new RSA();
$RSA->private_key = '212';
$RSA->modulus = '9173503';
$message = $RSA->decrypt($encrypted_message);

// String key representation
$key = 123;
$modulus = 123;
$type = 'private';
$key_string_representation = RSA::keyToString($key, $modulus, $type);

// or
$key = 123;
$modulus = 123;
$type = 'public';
$key_string_representation = RSA::keyToString($key, $modulus, $type);

// Key from string
$key_string_representation = 'asdad';
list($modulus, $exponent, $key_type) = RSA::keyFromString($key_string_representation);