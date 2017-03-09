<?php
namespace Dfe\SecurePay\Block;
/**
 * 2016-08-28
 * @final Unable to use the PHP «final» keyword because of the M2 code generation.
 * @method string|null responseF(string $k = null)
 */
class Info extends \Df\PaypalClone\BlockInfo {
	/**
	 * 2016-08-28
	 * @override
	 * @see \Df\Payment\Block\Info::prepare()
	 * @used-by \Df\Payment\Block\Info::_prepareSpecificInformation()
	 */
	final protected function prepare() {
		$this->si('Card Number', str_replace('...', '*******', $this->responseF('pan')));
		$this->siB('SecurePay ID', $this->responseF('txnid'));
	}
}