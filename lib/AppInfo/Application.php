<?php
namespace OCA\FilesScriptsDeprecated\AppInfo;

use OCA\FilesScripts\Event\RegisterScriptFunctionsEvent;
use OCA\FilesScriptsDeprecated\Listeners\LoadAdditionalScriptsListener;
use OCA\FilesScriptsDeprecated\Listeners\RegisterScriptFunctionListener;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\Collaboration\Resources\LoadAdditionalScriptsEvent;

class Application extends App implements IBootstrap {
	public const APP_ID = 'files_scripts_deprecated';

	public function __construct(array $urlParams = array()) {
		parent::__construct(self::APP_ID, $urlParams);
	}

	public function register(IRegistrationContext $context): void {
		$context->registerEventListener(RegisterScriptFunctionsEvent::class, RegisterScriptFunctionListener::class);
	}
	public function boot(IBootContext $context): void {
	}
}
