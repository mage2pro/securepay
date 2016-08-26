<?php
// 2016-08-25
namespace Dfe\SecurePay;
/** @method static Settings s() */
class Settings extends \Df\Payment\Settings\BankCard {
	/**
	 * 2016-08-26
	 * «Mage2.PRO» → «Payment» → «SecurePay» → «Force the payment transactions result?»
	 * @return bool
	 */
	public function forceResult() {return $this->v();}
}