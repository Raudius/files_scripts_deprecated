<?php
namespace OCA\FilesScriptsDeprecated\Listeners;

use OCA\FilesScripts\Event\RegisterScriptFunctionsEvent;
use OCA\FilesScripts\Interpreter\IFunctionProvider;
use OCA\FilesScriptsDeprecated\Interpreter\FunctionProvider;
use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;

class RegisterScriptFunctionListener implements IEventListener {
	private IFunctionProvider $functionProvider;

	public function __construct(FunctionProvider $functionProvider) {
		$this->functionProvider = $functionProvider;
	}

	public function handle(Event $event): void {
		if (!($event instanceof RegisterScriptFunctionsEvent)) {
			return;
		}

		$event->registerFunctions($this->functionProvider);
	}
}
