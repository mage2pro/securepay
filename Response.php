<?php
// 2016-08-27
namespace Dfe\SecurePay;
class Response extends \Df\Payment\R\Response {
	/**
	 * 2016-08-27
	 * @override
	 * @see \Df\Payment\Webhook\Response::config()
	 * @used-by \Df\Payment\Webhook\Response::configCached()
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
	 * @see \Df\Payment\Webhook\Response::parentIdKey()
	 * @used-by \Df\Payment\Webhook\Response::parentId()
	 * @return string
	 */
	protected function parentIdKey() {return 'refid';}
}