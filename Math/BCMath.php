<?php

require_once 'math_interface.php';

class BCMath implements RSAMathInterface
{
	public function bin2dec($bin)
	{
		$dec = '0';
		for ($i = 0, $len = strlen($bin); $i < $len; $i++) {

			$dec = bcadd($this->mul($dec, '2'), $bin[$i]);
		}

		return $dec;
	}

	public function dec2bin($dec)
	{
		$bin = '';

		do {
			$bin = $this->mod($dec, '2') . $bin;
			$dec = $this->div($dec, '2');
		} while (!$this->isZero($dec));

		return $bin;
	}


	/**
	 * Decrement number
	 *
	 * @param string $number
	 * @return string
	 * @access public
	 */
	public function dec($number)
	{
		return bcsub($number, '1');
	}

	public function inc($number)
	{
		return bcadd($number, '1');
	}

	/**
	 * Is numbers coprime
	 *
	 * @param string $number1
	 * @param string $number2
	 * @return bool
	 */
	public function isCoprime($number1, $number2)
	{
		$gcf = $this->greatestCommonDivisor($number1, $number2);
		return $this->isOne($gcf);
	}

	/**
	 * Is equal to unity
	 *
	 * @param string $number
	 * @return bool
	 * @access public
	 */
	public function isOne($number)
	{
		return $this->equal($number, '1');
	}

	/**
	 * Is equal to zero
	 *
	 * @param string $number
	 * @return bool
	 * @access public
	 */
	public function isZero($number)
	{
		return $this->equal($number, '0');
	}

	/**
	 * Is number1 lower than number2
	 *
	 * @param string $number1
	 * @param string $number2
	 * @return bool
	 */
	public function isLower($number1, $number2)
	{
		return bccomp($number1, $number2) === -1;
	}

	/**
	 * Is number1 divides to number2
	 *
	 * @param string $number1
	 * @param string $number2
	 * @return bool
	 */
	public function isDivides($number1, $number2)
	{
		$d = $this->mod($number1, $number2);
		return $this->equal($d, '0');
	}

	/**
	 * Is mod(number1, modulus) equal mod(number2, modulus)
	 *
	 * @param $number1
	 * @param $number2
	 * @param $modulus
	 * @return bool
	 */
	public function modularEqual($number1, $number2, $modulus)
	{
		return $this->equal(
			$this->mod($number1, $modulus),
			$this->mod($number2, $modulus));
	}

	/**
	 * Generate prime number
	 *
	 * @param  int $length - bits in the number
	 * @return string
	 */
	public function generatePrimeNumber($length)
	{
		$bin_random = '1' . $this->binRandom($length - 2) . '1';
		$dec_random = $this->bin2dec($bin_random);
		while (!$this->isPrime($dec_random)) {
			$dec_random = $this->inc($dec_random);
		}

		return $dec_random;
	}

	/**
	 * Is prime number
	 *
	 * @param string $number
	 * @return bool
	 */
	public function isPrime($number)
	{
		// Caching
		static $tested = array();

		if (array_key_exists($number, $tested)) {
			return $tested[$number];
		}

		if ($this->isDivides($number, '2')) {
			$is_prime =  true;
		} else {
			// TODO : LOG from BC number?
			$is_prime =  $this->MillerRabin($number, (int) log($number, 2));
		}

		$tested[$number] = $is_prime;
		return $is_prime;
	}

	/**
	 * Is numbers equal
	 *
	 * @param string $number1
	 * @param string $number2
	 * @return bool
	 */
	public function equal($number1, $number2)
	{
		return bccomp($number1, $number2) === 0;
	}

	/**
	 * Return modulus after division
	 *
	 * @param string $number
	 * @param string $modulus
	 * @return string
	 */
	public function mod($number, $modulus)
	{
		return bcmod($number, $modulus);
	}

	/**
	 * @param $number1
	 * @param $number2
	 * @return string
	 */
	public function div($number1, $number2)
	{
		$a = bcsub($number1, $this->mod($number1, $number2));
		return bcdiv($a, $number2);
	}

	/**
	 * Multiplication of numbers
	 *
	 * @param string $number1
	 * @param string $number2
	 * @return string
	 */
	public function mul($number1, $number2)
	{
		return bcmul($number1, $number2);
	}

	/**
	 * Modular exponentiation
	 *
	 * @param string $base
	 * @param string $exponent
	 * @param string $modulus
	 * @return string
	 */
	public function modularExponentiation($base, $exponent, $modulus)
	{
		return bcpowmod($base, $exponent, $modulus);
	}

	/**
	 *  Greatest common divisor
	 *
	 * @param string $number1
	 * @param string $number2
	 * @return string
	 */
	public function greatestCommonDivisor($number1, $number2)
	{
		do {
			list($number1, $number2) = array($number2, $this->mod($number1, $number2));
		} while (!$this->isZero($number2));
		return $number1;
	}

	/**
	 * Extended Euclidean algorithm
	 *
	 * ax + by = GCD(a, b) = d
	 *
	 * @param $a
	 * @param $b
	 * @return array - array(x, y, d)
	 */
	public function extendedEuclidean($a, $b)
	{
		$x = '0'; $last_x = '1';
		$y = '1'; $last_y = '0';

		while (!$this->isZero($b)) {
			$quotient = $this->div($a, $b);

			list($a, $b) = array($b, $this->mod($a, $b));
			list($x, $last_x) = array(bcsub($last_x, $this->mul($quotient, $x)), $x);
			list($y, $last_y) = array(bcsub($last_y, $this->mul($quotient, $y)), $y);
		}

		return array($last_x, $last_y, $a);
	}

	/**
	 * Modular multiplicative inverse
	 *
	 * @param string $number
	 * @param string $modulus
	 * @return string
	 */
	public function multiplicativeInverse($number, $modulus)
	{
		list($x, $y, $d) = $this->extendedEuclidean($number, $modulus);

		if (!$this->isOne($d)) {
			throw new InvalidArgumentException("Don't have multiplicative inverse");
		}

		if ($this->isLower($x, '0')) {
			$x = bcadd($x, $modulus);
		}

		return $x;
	}

	/**
	 * Miller-Rabin primality test
	 *
	 * @param string $number
	 * @param string $rounds
	 * @return bool
	 */
	public function MillerRabin($number, $rounds)
	{
		$s = 0;
		$t = $this->dec($number);
		while ($this->isDivides($t, '2')) {
			$t = bcdiv($t, '2');
			$s++;
		}

		for (; !$this->isZero($rounds); $rounds = $this->dec($rounds)) {
			$a = $this->random('2', $this->dec($number));
			$x = $this->modularExponentiation($a, $t, $number);

			if ($this->equal($x, '1') || $this->equal($x, $this->dec($number))) {
				continue;
			}

			$continue_first = false;
			for ($i = 0;  $i < $s; $i++) {
				$x = $this->modularExponentiation($x, '2', $number);

				if ($this->equal($x, '1')) {
					return false;
				}

				if ($this->equal($this->dec($number), $x)) {
					$continue_first = true;
					break;
				}
			}

			if ($continue_first) {
				continue;
			}

			return false;
		}

		return true;
	}

	// TODO : asdasd

	public function random($min, $max)
	{
		return mt_rand((int)$min, min((int)$max, mt_getrandmax()));
	}

	public function binRandom($length)
	{
		$bin = '';

		while ($length--) {
			$bin .= $this->random(0, 1);
		}

		return $bin;
	}
}
