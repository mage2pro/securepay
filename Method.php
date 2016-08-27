<?php
// 2016-08-25
namespace Dfe\SecurePay;
class Method extends \Df\Payment\R\Method {
	/**
	 * 2016-08-27
	 * @used-by \Df\Payment\R\Method::getConfigPaymentAction()
	 * @override
	 * @see \Df\Payment\R\Method::stageNames()
	 * @return string
	 */
	protected function redirectUrl() {return 'https://api.securepay.com.au/{stage}/directpost/authorise';}

	/**
	 * 2016-08-27
	 * Первый параметр — для test, второй — для live.
	 * @override
	 * @see \Df\Payment\R\Method::stageNames()
	 * @used-by \Df\Payment\R\Method::getConfigPaymentAction()
	 * @return string[]
	 */
	protected function stageNames() {return ['test', 'live'];}
}