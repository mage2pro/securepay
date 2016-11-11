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
		,self::$signatureKey => 'fingerprint'
		,self::$statusExpected => 1
		,self::$statusKey => 'summarycode'
	];}

	/**
	 * 2016-08-29
	 * Потомки перекрывают этот метод, когда ключ идентификатора запроса в запросе
	 * не совпадает с ключем идентификатора запроса в ответе.
	 * Так, в частности, происходит в модуле SecurePay: @see \Dfe\SecurePay\Charge::requestIdKey()
	 * @override
	 * @see \Df\Payment\R\Response::requestIdKey()
	 * @used-by \Df\Payment\R\Response::requestId()
	 * @return string
	 */
	protected function requestIdKey() {return 'refid';}
}