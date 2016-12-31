<?php
// 2016-08-26
namespace Dfe\SecurePay;
use Dfe\SecurePay\Settings as S;
abstract class Signer extends \Df\PaypalClone\Signer {
	/**
	 * 2016-08-27
	 * @used-by \Dfe\SecurePay\Signer::sign()
	 * @return string[]
	 */
	abstract protected function values();

	/**
	 * 2016-08-27
	 * https://github.com/thephpleague/omnipay-securepay/blob/v2.1.0/src/Message/DirectPostAuthorizeRequest.php#L32-L47
	 * @override
	 * @see \Df\PaypalClone\Signer::sign()
	 * @return string
	 */
	final protected function sign() {return sha1(implode('|',
		dfa_insert($this->values(), 1, S::s()->transactionPassword())
	));}
}
