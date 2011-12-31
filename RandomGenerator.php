<?php

class RandomGenerator
{
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
