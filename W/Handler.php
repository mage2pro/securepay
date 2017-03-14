<?php
// 2016-08-27
namespace Dfe\SecurePay\W;
final class Handler extends \Df\PaypalClone\W\Confirmation {
	/**
	 * 2016-08-27
	 * @override
	 * @see \Df\Payment\W\Handler::config()
	 * @used-by \Df\Payment\W\Handler::configCached()
	 * @return array(string => mixed)
	 */
	protected function config() {return [
		self::$externalIdKey => 'txnid'
		,self::$needCapture => true
		,self::$readableStatusKey => 'restext'
		,self::$signatureKey => 'fingerprint'
		,self::$statusExpected => 1
		,self::$statusKey => 'summarycode'
	];}

	/**
	 * 2016-08-29
	 * @override
	 * @see \Df\Payment\W\Handler::parentIdRawKey()
	 * @used-by \Df\Payment\W\Handler::parentIdRaw()
	 * @return string
	 */
	protected function parentIdRawKey() {return 'refid';}
}