<?php
// 2016-08-28
namespace Dfe\SecurePay\Block;
use Dfe\SecurePay\Response as R;
/** @method R|string|null responseF(string $key = null) */
class Info extends \Df\Payment\R\BlockInfo {
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