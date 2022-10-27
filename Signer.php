<?php
namespace Dfe\SecurePay;
/**
 * 2016-08-26  
 * @see \Dfe\SecurePay\Signer\Request 
 * @see \Dfe\SecurePay\Signer\Response 
 * @method Settings s()
 */
abstract class Signer extends \Df\PaypalClone\Signer {
	/**
	 * 2016-08-27
	 * @used-by self::sign()
	 * @see \Dfe\SecurePay\Signer\Request::values()
	 * @see \Dfe\SecurePay\Signer\Response::values()
	 * @return string[]
	 */
	abstract protected function values();

	/**
	 * 2016-08-27
	 * https://github.com/thephpleague/omnipay-securepay/blob/v2.1.0/src/Message/DirectPostAuthorizeRequest.php#L32-L47
	 * @override
	 * @see \Df\PaypalClone\Signer::sign()
	 * @used-by \Df\PaypalClone\Signer::_sign()
	 * @return string
	 */
	final protected function sign() {return sha1(implode('|', dfa_insert($this->values(), 1, $this->s()->password())));}
}