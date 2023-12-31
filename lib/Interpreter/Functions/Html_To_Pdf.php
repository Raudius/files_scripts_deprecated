<?php

namespace OCA\FilesScriptsDeprecated\Interpreter\Functions;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use OCA\FilesScripts\Interpreter\RegistrableFunction;
use OCP\ITempManager;
use Psr\Log\LoggerInterface;

/**
 * `html_to_pdf(String html, [Table config]={}, [Table position]={}): string|nil`
 *
 * Renders the HTML onto a PDF file.
 *
 * A configuration table can be passed to configure various aspects of PDF generation. For more information see the [MPDF documentation](https://mpdf.github.io/reference/mpdf-variables/overview.html).
 * The position (x, y, w, h) of where to render the HTML onto the page can also be provided. For more information see the [MPDF documentation](https://mpdf.github.io/reference/mpdf-functions/writefixedposhtml.html)
 *
 * Returns the PDF as a string (or `nil` if PDF generation failed).
 */
class Html_To_Pdf extends RegistrableFunction {
	private ITempManager $tempManager;
	private LoggerInterface $logger;

	public function __construct(ITempManager $templateManager, LoggerInterface $logger) {
		$this->tempManager = $templateManager;
		$this->logger = $logger;
	}

	public function run($html = '', $config = [], $pos = []): ?string {
		try {
			// Normalise format array (Lua uses 1-index)
			if (is_array($config['format'] ?? null) && !empty($config['format'])) {
				$config['format'] = array_values($config['format']);
			}
			$config['tempDir'] = $this->tempManager->getTemporaryFolder();
			$mpdf = new Mpdf($config);

			if (isset($pos['x'], $pos['y'], $pos['w'], $pos['h'])) {
				$overflow = $pos['overflow'] ?? 'visible';
				$mpdf->WriteFixedPosHTML($html, $pos['x'], $pos['y'], $pos['w'], $pos['h'], $overflow);
			} else {
				$mpdf->WriteHTML($html);
			}
			return $mpdf->Output('', Destination::STRING_RETURN);
		} catch (\Throwable $e) {
			return null;
		}
	}
}
