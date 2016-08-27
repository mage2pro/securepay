<?php
// 2016-08-26
namespace Dfe\SecurePay;
use Dfe\SecurePay\Settings as S;
/**
 * 2016-08-26
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
 */
final class Signer extends \Df\Payment\R\Signer {
	/**
	 * 2016-08-26
	 * https://github.com/thephpleague/omnipay-securepay/blob/v2.1.0/src/Message/DirectPostAuthorizeRequest.php#L32-L47
	 * @override
	 * @see \Df\Payment\R\Signer::sign()
	 * @return string
	 */
	public function sign() {return sha1(implode('|',
		dfa_insert(
			dfa_select_ordered($this->getData(), array_merge(
				['EPS_MERCHANT', 'EPS_TXNTYPE'],
				8 === $this['EPS_TXNTYPE']
					? ['EPS_STORETYPE', 'REFERENCEID'] : ['EPS_REFERENCEID', 'EPS_AMOUNT'],
				['EPS_TIMESTAMP']
			)),
			1, S::s()->transactionPassword())
		)
	);}
}
