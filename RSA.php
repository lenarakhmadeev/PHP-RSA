<?php

// TODO : Комментарии
// TODO : рефакторинг

class RSA
{
	/**
	 * @var RSAMathInterface
	 */
	protected $math;

	public $public_key;
	public $private_key;
	public $modulus;

	public function __construct($math = 'default')
	{
		$this->loadMath($math);
	}

	public function generateKeys($key_len = 1024)
	{
		// Generate two unequal prime numbers
		$first_prime_len  = (int)(($key_len + 1) / 2);
		$second_prime_len = $key_len - $first_prime_len;

		do {
			$first_prime  = $this->math->generatePrimeNumber($first_prime_len);
			$second_prime = $this->math->generatePrimeNumber($second_prime_len);
		} while ($this->math->equal($first_prime, $second_prime));

		// Generate keys
		list($this->public_key,
		     $this->private_key,
		     $this->modulus) = $this->generateKeysPair($first_prime, $second_prime);
	}

	public function encrypt($message)
	{
		return $this->math->modularExponentiation(
			$message,
			$this->public_key,
			$this->modulus);
	}

	public function decrypt($encrypted_message)
	{
		return $this->math->modularExponentiation(
			$encrypted_message,
			$this->private_key,
			$this->modulus);
	}

	// TODO : generate public if not set and example

	public function generateKeysPair($first_prime, $second_prime, $public_key = 65537)
	{
		if (!$this->math->isPrime($first_prime) || !$this->math->isPrime($second_prime)) {
			throw new InvalidArgumentException('$first_prime and $second_prime must be prime');
		}

		// Compute $modulus = $first_prime * $second_prime
		$modulus = $this->math->mul($first_prime, $second_prime);

		// Compute the totient of the product as ϕ($modulus) = ($first_prime − 1)($second_prime − 1) giving
		$dec_p = $this->math->dec($first_prime);
		$dec_q = $this->math->dec($second_prime);
		$phi   = $this->math->mul($dec_p, $dec_q);

		// Choose any number e that is coprime to ϕ($modulus)
		if (!$this->math->isCoprime($public_key, $phi)) {
			throw new InvalidArgumentException('public_key must be coprime to (first_prime - 1)(second_prime - 1)');
		}

		// Compute $private_key, the modular multiplicative inverse of $public_key (mod ϕ($modulus)) yielding
		$private_key = $this->math->multiplicativeInverse($public_key, $phi);

		return array($public_key, $private_key, $modulus);
	}

	// TODO : Тесты и комменты

	public static function keyToString($key, $modulus, $type)
	{
		return base64_encode(
			serialize(
				array($modulus, $key, $type)
			));
	}

	public static function keyFromString($key_string_representation)
	{
		return unserialize(
			base64_decode(
				$key_string_representation
			));
	}

	protected function loadMath($math_name)
	{
		$default = 'GMP';

		if ($math_name === 'default') {
			$this->math = new $default();
		} else {
			$this->math = new $math_name();
		}
	}

}
