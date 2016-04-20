<?php

namespace Olm\Perseo;

class TimeLength {
	protected $seconds;

	private function __construct($seconds)
	{
		
		$this->seconds = $seconds;
	}

	public static function fromSeconds($seconds)
	{
		return new static($seconds);
	}

	public static function fromMinutes($minute)
	{
		return new static($minute * 60);
	}

	public static function fromHours($hour)
	{
		return new static($hour * 60 * 60);
	}

	public function inSeconds()
	{
		$this->seconds;
	}
	public function inMinutes()
	{
		$this->seconds / 60;
	}
	public function inHours()
	{
		$this->seconds / 60 / 60;
	}
}