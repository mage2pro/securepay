<?php
// 2016-08-27
namespace Dfe\SecurePay;
class Response extends \Df\Payment\R\Response {
	/**
	 * 2016-08-27
	 * @override
	 * @see \Df\Payment\R\Response::config()
	 * @used-by \Df\Payment\R\Response::configCached()
	 * @return array(string => mixed)
	 */
	protected function config() {return [
		self::$externalIdKey => 'txnid'
		,self::$needCapture => true
		,self::$requestIdKey => 'refid'
		,self::$signatureKey => 'fingerprint'
		,self::$statusExpected => 1
		,self::$statusKey => 'summarycode'
	];}
}