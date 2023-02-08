<?php
# 2016-08-25
namespace Dfe\SecurePay;
/** @method static Settings s() */
final class Settings extends \Df\Payment\Settings\BankCard {
	/**
	 * 2016-08-26 «Mage2.PRO» → «Payment» → «SecurePay» → «Enable 3D Secure Validation?»
	 */
	function enable3DS():bool {return $this->b();}

	/**
	 * 2016-08-26 «Mage2.PRO» → «Payment» → «SecurePay» → «Force the payment transactions result?»
	 */
	function forceResult():bool {return $this->v();}

	/**
	 * 2016-08-26 «Mage2.PRO» → «Payment» → «SecurePay» → «Merchant ID for 3D Secure Validation»
	 */
	function merchantID_3DS():bool {return $this->v();}

	/**
	 * 2016-08-26
	 * @used-by \Dfe\SecurePay\Refund::process()
	 * @used-by \Dfe\SecurePay\Signer::sign()
	 */
	function password():string {return $this->testablePV('transactionPassword');}
}