<?php
namespace Dfe\SecurePay\Block;
use Dfe\SecurePay\W\Event;
/**
 * 2016-08-28
 * @final Unable to use the PHP «final» keyword here because of the M2 code generation.
 * @method Event|string|null e(...$k)
 */
class Info extends \Df\Payment\Block\Info {
	/**
	 * 2016-08-28
	 * @override
	 * @see \Df\Payment\Block\Info::prepare()
	 * @used-by \Df\Payment\Block\Info::prepareToRendering()
	 */
	final protected function prepare() {
		$this->si('Card Number', str_replace('...', '*******', $this->e('pan')));
		$this->siEx('SecurePay ID', $this->e()->idE());
	}
}