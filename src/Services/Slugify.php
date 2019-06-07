<?php


namespace App\Services;

class Slugify
{


	public function generate(string $input) :string
	{
		$input = trim(strtolower($input));

		$utf8 = [
			'/[áàâãªä]/' => 'a',
			'/[ÁÀÂÃÄ]/' => 'A',
			'/[ÍÌÎÏ]/' => 'I',
			'/[íìîï]/' => 'i',
			'/[éèêë]/' => 'e',
			'/[ÉÈÊË]/' => 'E',
			'/[óòôõºö]/' => 'o',
			'/[ÓÒÔÕÖ]/' => 'O',
			'/[úùûü]/' => 'u',
			'/[ÚÙÛÜ]/' => 'U',
			'/ç/' => 'c',
			'/Ç/' => 'C',
			'/ñ/' => 'n',
			'/Ñ/' => 'N',
			'/ /' => '-',
			'/!/' => '-',
			'/\'/' => '-',
			'/,/' => '-',
			'/\//' => '-',
			'/\./' => '-'
		];

		foreach ($utf8 as $key => $value) {
			$input = preg_replace($key, $value, $input);
		}

		$input = str_split($input);
		for ($i=0; $i< count($input); $i++) {
			if($input[$i] == '-' && $input[$i-1] == $input[$i]){
				array_pop($input);
				$input[$i] = str_replace('-','', $input[$i]);
			}
		}
		for ($i= 0; $i <= count($input)-1; $i++){
			if($input[$i] == '-' && $i === count($input)-1) {
				array_pop($input);
			}
		}
		return implode($input);
	}

	public function removeAccentsForEmail($name){
		$name = trim(strtolower($name));

		$utf8 = [
			'/[áàâãªä]/' => 'a',
			'/[ÁÀÂÃÄ]/' => 'A',
			'/[ÍÌÎÏ]/' => 'I',
			'/[íìîï]/' => 'i',
			'/[éèêë]/' => 'e',
			'/[ÉÈÊË]/' => 'E',
			'/[ö]/' => 'o',
			'/[óòôõºö]/' => 'o',
			'/[ÓÒÔÕÖ]/' => 'O',
			'/[úùûü]/' => 'u',
			'/[ÚÙÛÜ]/' => 'U',
			'/ç/' => 'c',
			'/Ç/' => 'C',
			'/ñ/' => 'n',
			'/Ñ/' => 'N',
			'/ /' => '-',
			'/!/' => '-',
			'/\'/' => '-',
			'/,/' => '-',
			'/\//' => '-',
			'/\./' => '-'
		];

		foreach ($utf8 as $key => $value) {
			$name = preg_replace($key, $value, $name);
		}

		return $name;
	}
}
