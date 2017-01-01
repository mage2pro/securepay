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
	 * @see \Df\Payment\Webhook::parentIdKey()
	 * @used-by \Df\Payment\Webhook::parentId()
	 * @return string
	 */
	protected function parentIdKey() {return 'refid';}
}