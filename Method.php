<?php
// 2016-08-25
namespace Dfe\SecurePay;
use Dfe\SecurePay\Settings as S;
class Method extends \Df\Payment\Method {
	/**
	 * 2016-08-26
	 * @return string
	 */
	private static function url() {
		/** @var string $stage */
		$stage = S::s()->test() ? 'test' : 'live';
		return "https://api.securepay.com.au/{$stage}/directpost/authorise";
	}
}