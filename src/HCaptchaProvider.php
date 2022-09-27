<?php declare(strict_types = 1);

namespace Contributte\HCaptcha;

use Nette\Forms\Controls\BaseControl;
use Nette\SmartObject;

/**
 * @method onValidateControl(HCaptchaProvider $provider, BaseControl $control)
 * @method onValidate(HCaptchaProvider $provider, mixed $response)
 */
class HCaptchaProvider
{

	use SmartObject;

	// HCaptcha FTW!
	public const FORM_PARAMETER = 'h-captcha-response';
	public const VERIFICATION_URL = 'https://hcaptcha.com/siteverify';

	/** @var callable[] */
	public $onValidate = [];

	/** @var callable[] */
	public $onValidateControl = [];

	/** @var string */
	private $siteKey;

	/** @var string */
	private $secretKey;

	public function __construct(string $siteKey, string $secretKey)
	{
		$this->siteKey = $siteKey;
		$this->secretKey = $secretKey;
	}

	public function getSiteKey(): string
	{
		return $this->siteKey;
	}

	/**
	 * @param mixed $response
	 */
	public function validate($response): ?HCaptchaResponse
	{
		// Fire events!
		$this->onValidate($this, $response);

		// Load response
		$response = $this->makeRequest($response);

		// Response is empty or failed..
		if (empty($response)) {
			return null;
		}

		// Decode server answer (with key assoc reserved)
		$answer = json_decode($response, true);

		// Return response
		return $answer['success'] === true ? new HCaptchaResponse(true) : new HCaptchaResponse(false, $answer['error-codes'] ?? null);
	}

	public function validateControl(BaseControl $control): bool
	{
		// Fire events!
		$this->onValidateControl($this, $control);

		// Get response
		$response = $this->validate($control->getValue());

		if ($response !== null) {
			return $response->isSuccess();
		}

		return false;
	}


	/**
	 * @param mixed $response
	 * @return mixed
	 */
	protected function makeRequest($response, ?string $remoteIp = null)
	{
		if (empty($response)) {
			return null;
		}

		$params = [
			'secret' => $this->secretKey,
			'response' => $response,
		];

		if ($remoteIp !== null) {
			$params['remoteip'] = $remoteIp;
		}

		return @file_get_contents($this->buildUrl($params));
	}

	/**
	 * @param mixed[] $parameters
	 */
	protected function buildUrl(array $parameters = []): string
	{
		return self::VERIFICATION_URL . '?' . http_build_query($parameters);
	}

}
