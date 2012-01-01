<?php

require_once __DIR__ . '/../Math/BCMath.php';

/**
 * Test class for BCMath.
 */
class BCMathTest extends PHPUnit_Framework_TestCase
{
	/** @var BCMath */
	protected $math;

	public function setUp()
	{
		$this->math = new BCMath();
	}

	public function testMod()
	{
		$this->assertEquals(8, $this->math->mod(107, 11));
	}

	public function testGCF()
	{
		$this->assertEquals(9, $this->math->greatestCommonDivisor(45, 18));
	}

	public function testPow()
	{
		$this->assertEquals(19, $this->math->modularExponentiation(1234, 13, 27));
	}

	public function testPowOfBig()
	{
		$this->assertEquals(4051753, $this->math->modularExponentiation(111111, 3, 9173503));
	}

	public function testMult()
	{
		$this->assertEquals(1942986, $this->math->mul(213, 9122));
	}

	/**
	 *@expectedException  InvalidArgumentException
	 */
	public function testDontHaveMultInver()
	{
		$this->assertEquals(123, $this->math->multiplicativeInverse(21123, 123));
	}

	public function testHaveMultInver()
	{
		$this->assertEquals(79, $this->math->multiplicativeInverse(21123, 122));
	}

	public function testMillerRabin15IsNotPrime()
	{
		$this->assertTrue(!$this->math->MillerRabin(15, 10));
	}

	public function testMillerRabin221IsNotPrime()
	{
		$this->assertTrue(!$this->math->MillerRabin(221, 200));
	}

	public function testMillerRabin77IsNotPrime()
	{
		$this->assertTrue(!$this->math->MillerRabin(77, 60));
	}

	public function testMillerRabin17IsPrime()
	{
		$this->assertTrue($this->math->MillerRabin(17, 10));
	}

	public function testMillerRabin257IsPrime()
	{
		$this->assertTrue($this->math->MillerRabin(257, 150));
	}

	public function testEvenNumberIsPrime()
	{
		$this->assertTrue($this->math->isPrime(2));
		$this->assertTrue($this->math->isPrime(4));
		$this->assertTrue($this->math->isPrime(64));
	}

	public function testIsPrime()
	{
		$this->assertTrue($this->math->isPrime('115249'));
		$this->assertTrue($this->math->isPrime('139969'));
		$this->assertTrue($this->math->isPrime('265252859812191058636308479999999'));
		$this->assertTrue($this->math->isPrime('263130836933693530167218012159999999'));

		// Duplicate to tests cache
		$this->assertTrue($this->math->isPrime('115249'));
		$this->assertTrue($this->math->isPrime('139969'));
		$this->assertTrue($this->math->isPrime('265252859812191058636308479999999'));
		$this->assertTrue($this->math->isPrime('263130836933693530167218012159999999'));
	}

	public function testModularEqual()
	{
		$this->assertTrue($this->math->modularEqual(123, 123, 120));
		$this->assertTrue($this->math->modularEqual(123, 3, 120));
		$this->assertTrue($this->math->modularEqual(10, 17, 7));
	}

	public function testAdvEvk()
	{
		$this->assertEquals(array(1, -1, 1), $this->math->extendedEuclidean(3, 2));
		$this->assertEquals(array(-9, 47, 1), $this->math->extendedEuclidean(120, 23));
	}

	public function testCoprime()
	{
		$this->assertTrue($this->math->isCoprime('123', '17'));
		$this->assertTrue($this->math->isCoprime('123', '16'));

		$this->assertTrue(!$this->math->isCoprime('400', '5'));
		$this->assertTrue(!$this->math->isCoprime('400', '20'));
	}

	public function testBin2Dec()
	{
		$bin = '1011010010';
		$this->assertEquals('722', $this->math->bin2dec($bin));
	}

	public function testDec2Bin()
	{
		$dec = '722';

		$this->assertEquals('1011010010', $this->math->dec2bin($dec));
	}

	public function testGeneratePrime()
	{
		$prime = $this->math->generatePrimeNumber(1024);

		$this->assertEquals(1024, strlen($this->math->dec2bin($prime)));
		$this->assertTrue($this->math->isPrime($prime));
	}
}
