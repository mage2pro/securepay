<?php
// 2016-08-25
namespace Dfe\SecurePay;
/** @method static Settings s() */
final class Settings extends \Df\Payment\Settings\BankCard {
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
	 * @return string
	 */
	public function transactionPassword() {return $this->testable();}

	/**
	 * 2016-08-26
	 * «Mage2.PRO» → «Payment» → «SecurePay» → «Live Merchant ID»
	 * @return string
	 */
	protected function liveMerchantID() {return $this->v();}

	/**
	 * 2016-08-26
	 * «Mage2.PRO» → «Payment» → «SecurePay» → «Live Transaction Password»
	 * @return string
	 */
	protected function liveTransactionPassword() {return $this->p();}

	/**
	 * 2016-08-26
	 * @return string
	 */
	protected function testMerchantID() {return $this->v();}

	/**
	 * 2016-08-26
	 * We do not encrypt the test keys.
	 * @return string
	 */
	protected function testTransactionPassword() {return $this->v();}
}