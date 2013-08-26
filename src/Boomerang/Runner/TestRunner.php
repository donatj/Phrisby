<?php

namespace Boomerang\Runner;

use Boomerang\Boomerang;

class TestRunner {

	private $path;
	private $files;
	/**
	 * @var UserInterface
	 */
	private $ui;

	function __construct( $path, UserInterface $ui ) {
		$this->path  = $path;
		$this->files = $this->getFileList($this->path);
		$this->ui    = $ui;
	}

	/**
	 * @param $path
	 * @return \Iterator
	 */
	function getFileList( $path ) {
		if( $real = realpath($path) ) {
			$path = $real;
		}

		$path = rtrim($path, DIRECTORY_SEPARATOR);

		if( is_dir($path) ) {
			$dir   = new \RecursiveDirectoryIterator($path);
			$ite   = new \RecursiveIteratorIterator($dir);
			$files = new \RegexIterator($ite, "/Spec\.php$/");

			return $files;
		} elseif( is_readable($path) ) {
			return new \ArrayIterator(array( $path ));
		}

		UserInterface::dropError("Cannot find file \"$path\"");

	}

	function runTests() {
		$scope = function ( $file ) { require($file); };

		foreach( $this->files as $file ) {
			$scope($file);

			$this->ui->updateExpectationDisplay();
		}
	}

}