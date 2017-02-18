<?php
namespace Dfe\SecurePay\Block;
use Dfe\SecurePay\Webhook as W;
/**
 * 2016-08-28
 * @final
 * @method W|string|null responseF(string $key = null)
 */
class Info extends \Df\PaypalClone\BlockInfo {
	/**
	 * 2016-08-28
	 * @override
	 * @see \Df\Payment\Block\Info::prepare()
	 * @used-by \Df\Payment\Block\Info::_prepareSpecificInformation()
	 */
	protected function prepare() {
		$this->si('Card Number', str_replace('...', '*******', $this->responseF('pan')));
		$this->siB('SecurePay ID', $this->responseF('txnid'));
	}
}