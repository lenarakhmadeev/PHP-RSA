<?php

require_once __DIR__ . '/../RSA.php';
require_once __DIR__ . '/../Math/BCMath.php';

// TODO : 100% coverage, refactoring

/**
 * Test class for RSA.
 */
class RSATest extends PHPUnit_Framework_TestCase
{

	/** @var RSA */
	protected $RSA;

	function setUp()
	{
		$math = new BCMath();
		$this->RSA = new RSA($math);
	}

	function testGeneratePairFromPrime()
	{
		/*
		1. p = 3, q = 11
		2. n = 33
		3. Phi(p,q) = 2 * 10 = 20
		4. d = 257
		5. 257 * 13 mod 20 = 1, e = 13
		*/

		list($public, $private, $n) = $this->RSA->generateKeysPair(3, 11, 257);

		$this->assertEquals(257, $public);
		$this->assertEquals(13, $private);
		$this->assertEquals(33, $n);

		//
		list($public, $private, $n) = $this->RSA->generateKeysPair(3557, 2579, 3);

		$this->assertEquals(3, $public);
		$this->assertEquals(6111579, $private);
		$this->assertEquals(9173503, $n);
	}

	function testEncryptNumber()
	{
		$message = 111111;
		$this->RSA->public_key = 3;
		$this->RSA->N = 9173503;

		$this->assertEquals(4051753, $this->RSA->encrypt($message));
	}

	function testDecryptNumber()
	{
		$encrypted_message = 4051753;
		$this->RSA->private_key = 6111579;
		$this->RSA->N = 9173503;

		$this->assertEquals(111111, $this->RSA->decrypt($encrypted_message));
	}
}