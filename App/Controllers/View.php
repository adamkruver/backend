<?php
declare(strict_types=1);

namespace App\Controllers;
class View {
	
	private static $view;
	
	private $document;
	private $headers;
	
	public static function GetInstance(): View
	{
		if(empty(self::$view))
		{
			self::$view = new View();
		}
		return self::$view;
	}
	
	public static function WriteAll()
	{
		self::$view->writeHeaders();
		self::$view->writeDocument();		
		exit;
	}
	
	private function __construct()
	{
		$this->headers = Array();
	}
	
	public function setHeader(string $header)
	{
		$this->headers[] = $header;
	}
	
	private function writeHeaders()
	{
		foreach($this->headers as $header)
		{
			header($header);
		}
	}
	
	private function writeDocument()
	{
		echo $this->document;
	}
	
	public function write(string $line)
	{
		$this->document = $this->document.$line;
	}
	
}
