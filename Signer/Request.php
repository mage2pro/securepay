<?php
// 2016-08-26
namespace Dfe\SecurePay\Signer;
use Dfe\SecurePay\Settings as S;
final class Request extends \Dfe\SecurePay\Signer {
	/**
	 * 2016-08-27
	 *
	 * «5.1.1.6 Fingerprint».
	 * Mandatory
	 * String, length up to 60
	 * «A SHA1 hash of
	 * 		For EPS_TXNTYPE 0-7:
	 * 		EPS_MERCHANT|TransactionPassword|EPS_TXNTYPE|EPS_REFERENCEID|EPS_AMOUNT|EPS_TIMESTAMP
	 *
	 * 		For EPS_TXNTYPE 8:
	 * 		EPS_MERCHANT|TransactionPassword|EPS_TXNTYPE|EPS_STORETYPE|EPS_REFERENCEID|EPS_TIMESTAMP
	 * Where the EPS_ prefixed fields are sent in the request
	 * and “TransactionPassword” is obtained from SecurePay Support
	 * and maybe changed via the SecurePay Merchant Log In.»
	 *
	 * @override
	 * @see \Dfe\SecurePay\Signer::values()
	 * @used-by \Dfe\SecurePay\Signer::sign()
	 * @return string[]
	 */
	protected function values() {return dfa_select_ordered($this->getData(), array_merge(
		['EPS_MERCHANT', 'EPS_TXNTYPE'],
		8 === $this['EPS_TXNTYPE']
			? ['EPS_STORETYPE', 'REFERENCEID'] : ['EPS_REFERENCEID', 'EPS_AMOUNT'],
		['EPS_TIMESTAMP']
	));}
}
