<?php
// 2016-08-27
namespace Dfe\SecurePay;
class Webhook extends \Df\PaypalClone\Confirmation {
	/**
	 * 2016-08-27
	 * @override
	 * @see \Df\Payment\Webhook::config()
	 * @used-by \Df\Payment\Webhook::configCached()
	 * @return array(string => mixed)
	 */
	final protected function config() {return [
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
	 * @see \Df\Payment\Webhook::parentIdRawKey()
	 * @used-by \Df\Payment\Webhook::parentIdRaw()
	 * @return string
	 */
	final protected function parentIdRawKey() {return 'refid';}
}