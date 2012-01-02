<?php

interface RSAMathInterface
{
	/**
	 * Decrement number
	 *
	 * @param string $number
	 * @return string
	 * @access public
	 */
	public function dec($number);

	/**
	 * Is numbers coprime
	 *
	 * @param string $number1
	 * @param string $number2
	 * @return bool
	 */
	public function isCoprime($number1, $number2);

	/**
	 * Generate prime number
	 *
	 * @param  int $length - bits in the number
	 * @return string
	 */
	public function generatePrimeNumber($length);

	/**
	 * Is prime number
	 *
	 * @param string $number
	 * @return bool
	 */
	public function isPrime($number);

	/**
	 * Is numbers equal
	 *
	 * @param string $number1
	 * @param string $number2
	 * @return bool
	 */
	public function equal($number1, $number2);

	/**
	 * Multiplication of numbers
	 *
	 * @param string $number1
	 * @param string $number2
	 * @return string
	 */
	public function mul($number1, $number2);

	/**
	 * Modular exponentiation
	 *
	 * @param string $base
	 * @param string $exponent
	 * @param string $modulus
	 * @return string
	 */
	public function modularExponentiation($base, $exponent, $modulus);

	/**
	 * Modular multiplicative inverse
	 *
	 * @param string $number
	 * @param string $modulus
	 * @return string
	 */
	public function multiplicativeInverse($number, $modulus);
}