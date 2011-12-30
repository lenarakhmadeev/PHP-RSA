<?php

class BCMath
{
	// TODO :  Random generator: Интерфейсдля реализаций, проверка
	// TODO : Порядок

	protected $random_generator;

	/*public function __construct($random_generator)
	{
		$this->random_generator = $random_generator;
	}*/

	/**
	 * Decrement number
	 *
	 * @param string $num
	 * @return string
	 * @access public
	 */
	public function dec($num)
	{
		return bcsub($num, '1');
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

	// TODO :
	/**
	 * Generate prime number
	 *
	 * @param  int $length - bits in the number
	 * @return string
	 */
	public function generatePrimeNumber($length)
	{
		// TODO : ASasd
		/*$bytes_n = intval($length / 8);
		$bits_n = $length % 8;
		do {
			$str = '';
			for ($i = 0; $i < $bytes_n; $i++) {
				$str .= chr(call_user_func($random_generator) & 0xff);
			}
			$n = call_user_func($random_generator) & 0xff;
			$n |= 0x80;
			$n >>= 8 - $bits_n;
			$str .= chr($n);
			$num = $this->bin2int($str);

			// search for the next closest prime number after [$num]
			if ($this->modularEqual($num, '0', '2')) {
				$num = bcadd($num, '1');
			}
			while (!$this->isPrime($num)) {
				$num = bcadd($num, '2');
			}
		} while ($this->bitLen($num) != $length);
		return $num;*/
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

		if ($this->modularEqual($number, 0, 2)) {
			$is_prime =  true;
		} else {
			// TODO : LOG from BC number?
			$is_prime =  $this->MillerRabin($number, log($number, 2));
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

	// TODO : name
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
		while ($this->modularEqual($t, '0', '2')) {
			$t = bcdiv($t, '2');
			$s++;
		}

		for (; $rounds > 0; $rounds--) {
			$a = mt_rand(2, min($number - 1, mt_getrandmax()));
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
}
