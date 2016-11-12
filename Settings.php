<?php
// 2016-08-25
namespace Dfe\SecurePay;
/** @method static Settings s() */
final class Settings extends \Df\Payment\Settings\BankCard {
	/**
	 * 2016-08-26
	 * «Mage2.PRO» → «Payment» → «SecurePay» → «Enable 3D Secure Validation?»
	 * @return bool
	 */
	public function enable3DS() {return $this->b();}

	/**
	 * 2016-08-26
	 * «Mage2.PRO» → «Payment» → «SecurePay» → «Force the payment transactions result?»
	 * @return bool
	 */
	public function forceResult() {return $this->v();}

	/**
	 * 2016-08-26
	 * @return string
	 */
	public function merchantID() {return $this->testable();}

	/**
	 * 2016-08-26
	 * «Mage2.PRO» → «Payment» → «SecurePay» → «Merchant ID for 3D Secure Validation»
	 * @return bool
	 */
	public function merchantID_3DS() {return $this->v();}

	/**
	 * 2016-08-26
	 * @return string
	 */
	public function transactionPassword() {return $this->testablePV();}
}