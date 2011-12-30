<?php

class Math
{

	public static function dec2bin($dec)
	{
		return decbin($dec);
	}

	public static function bin2dec($bin)
	{
		return bindec($bin);
	}

	public static function generatePrimeNumber($len)
	{

	}

	public static function mod($number, $modul)
	{
		return $number % $modul;
	}

	public static function mul($a, $b)
	{
		return $a * $b;
	}

	public static function pow($number, $pow, $modul)
	{
		/*$bin = self::dec2bin($pow);

		$res = $number;
		for ($i = 1, $len = strlen($bin); $i < $len; $i++) {
			if ($bin[$i] === '0') {
				$res = self::mod($res * $res, $modul);
			} else {
				$res = self::mod($res * $res, $modul);
				$res = self::mod($res * $number, $modul);
			}
		}

		return $res;*/

		$y = 1;
		while ($pow) {
			$b = self::mod($number, $modul);

			if (self::mod($pow, 2)) {
				$y = self::mod($y * $b, $modul);
				$pow--;
			}

			$pow /= 2;
			$number = self::mod($b * $b, $modul);
		}

		return $y;
	}

	public static function GCF($a, $b)
	{
		while ($c = self::mod($a, $b)) {
			$a = $b;
			$b = $c;
		}

		return $b;
	}

	public static function advEvk($num1, $num2)
	{
		$a = abs($num1);
		$b = abs($num2);

		$f = 0;
		if ($b > $a) {
			$f = 1;
			list($a, $b) = array($b, $a);
		}

		$D = array(
			array($a, 1, 0),
			array($b, 0, 1)
		);
		$q = array(0, (int) ($a / $b));

		$last = 1;
		while (true) {
			$row = array();
			for ($j = 0; $j < 3; $j++) {
				$row[] = $D[$last - 1][$j] - $D[$last][$j] * $q[$last];
			}

			if (!$row[0]) {
				break;
			}

			$D[] = $row;
			$last++;
			$q[] = (int) ($D[$last - 1][0] / $D[$last][0]);
		}

		if ($f) {
			$k1 = $D[$last][2];
			$k2 = $D[$last][1];
		} else {
			$k1 = $D[$last][1];
			$k2 = $D[$last][2];
		}

		$M = array(1, -1);
		foreach ($M as $s1) {
			foreach ($M as $s2) {
				if ($D[$last][0] === $s1 * $k1 * $num1 + $s2 * $k2 * $num2) {
					$k1 = $s1 * $k1;
					$k2 = $s2 * $k2;
					break 2;
				}
			}
		}

		return array($D[$last][0], $k1, $k2);
	}

	public static function multInver($number, $modul)
	{
		list($d, $x, $y) = self::advEvk($number, $modul);

		if ($d !== 1) {
			throw new InvalidArgumentException('Нет мультпликативного обратного');
		}

		if ($x < 0) {
			$x = $x + $modul;
		}

		return $x;
	}
}
