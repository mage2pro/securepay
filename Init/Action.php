<?php
namespace Dfe\SecurePay\Init;
// 2017-03-21
/** @method \Dfe\SecurePay\Method m() */
final class Action extends \Df\PaypalClone\Init\Action {
	/**
	 * 2016-08-27
	 * 2017-02-16
	 * SecurePay has changed the URL for Direct Post API testing: https://mage2.pro/t/2779
	 * @override
	 * @see \Df\Payment\Init\Action::redirectUrl()
	 * @used-by \Df\Payment\Init\Action::action()
	 * @return string
	 */
	protected function redirectUrl() {return $this->m()->url(
		'https://{stage}api.securepay.com.au/live/directpost/authorise'
	);}
}