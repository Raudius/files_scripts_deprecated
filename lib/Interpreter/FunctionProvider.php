<?php

namespace OCA\FilesScriptsDeprecated\Interpreter;

use OCA\FilesScripts\Interpreter\IFunctionProvider;
use OCA\FilesScriptsDeprecated\Interpreter\Functions\Html_To_Pdf;

class FunctionProvider implements IFunctionProvider {
	private array $functions;
	public function __construct(Html_To_Pdf $html_to_pdf) {
		$this->functions = [$html_to_pdf];
	}

	public function getFunctions(): iterable {
		foreach ($this->functions as $function) {
			yield $function;
		}
	}

	public function isRegistrable(): bool {
		return true;
	}
}
