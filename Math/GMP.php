<?php

class GMP implements RSAMathInterface
{

	public function dec($number)
	{
		return gmp_sub($number, 1);
	}

	public function isCoprime($number1, $number2)
	{
		$gcf = gmp_gcd($number1, $number2);
		return $this->equal($gcf, 1);
	}

	public function generatePrimeNumber($length)
	{
		$bin_random = '1' . $this->binRandom($length - 2) . '1';
		$dec_random = $this->bin2dec($bin_random);


		return gmp_nextprime($dec_random);
	}

	public function isPrime($number)
	{
		return gmp_prob_prime($number) ? true : false;
	}

	public function equal($number1, $number2)
	{
		return gmp_cmp($number1, $number2) === 0;
	}

	public function mul($number1, $number2)
	{
		return gmp_mul($number1, $number2);
	}

	public function modularExponentiation($base, $exponent, $modulus)
	{
		return gmp_powm($base, $exponent, $modulus);
	}

	public function multiplicativeInverse($number, $modulus)
	{
		return gmp_invert($number, $modulus);
	}

	public function binRandom($length)
	{
		$bin = '';

		while ($length--) {
			$bin .= mt_rand(0, 1);
		}

		return $bin;
	}

	public function bin2dec($bin)
	{
		$dec = '0';
		for ($i = 0, $len = strlen($bin); $i < $len; $i++) {

			$dec = gmp_add(gmp_mul($dec, 2), $bin[$i]);
		}

		return $dec;
	}

}