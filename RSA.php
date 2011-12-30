<?php

// TODO : Комментарии

class RSA
{
	/** @var BCMath */
	protected $math;

	public $public_key;
	public $private_key;
	public $N;
	public $p;
	public $q;

	public function __construct($math, $key_len = 32)
	{
		$this->math = $math;
		$this->generateKeys($key_len);
	}

	public function generateKeys($key_len)
	{
		// Generate two unequal prime numbers
		$p_len = (int)(($key_len + 1) / 2);
		$q_len = $key_len - $p_len;

		do {
			$p = $this->math->generatePrimeNumber($p_len);
			$q = $this->math->generatePrimeNumber($q_len);
		} while ($this->math->equal($p, $q));

		$this->p = $p;
		$this->q = $q;

		// Generate keys
		list($this->public_key,
		     $this->private_key,
		     $this->N) = $this->generateKeysPair($p, $q);
	}

	public function encrypt($message)
	{
		return $this->math->modularExponentiation(
			$message,
			$this->public_key,
			$this->N);
	}

	public function decrypt($encrypted_message)
	{
		return $this->math->modularExponentiation(
			$encrypted_message,
			$this->private_key,
			$this->N);
	}

	public function generateKeysPair($p, $q, $e = 65537)
	{
		if (!$this->math->isPrime($p) || !$this->math->isPrime($q)) {
			throw new InvalidArgumentException('$p and $q must be prime');
		}

		// Compute n = pq
		$n = $this->math->mul($p, $q);

		// Compute the totient of the product as ϕ(n) = (p − 1)(q − 1) giving
		$dec_p = $this->math->dec($p);
		$dec_q = $this->math->dec($q);
		$phi   = $this->math->mul($dec_p, $dec_q);

		// Choose any number e that is coprime to ϕ(n)
		if (!$this->math->isCoprime($e, $phi)) {
			throw new InvalidArgumentException('e must be coprime to (p - 1)(q - 1)');
		}

		// Compute d, the modular multiplicative inverse of e (mod ϕ(n)) yielding
		$d = $this->math->multiplicativeInverse($e, $phi);

		return array($e, $d, $n);
	}

}
