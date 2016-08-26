<?php
// 2016-08-25
namespace Dfe\SecurePay;
/** @method Settings s() */
class ConfigProvider extends \Df\Payment\ConfigProvider\BankCard {
	/**
	 * 2016-08-25
	 * @override
	 * @see \Df\Payment\ConfigProvider::config()
	 * @used-by \Df\Payment\ConfigProvider::getConfig()
	 * @return array(string => mixed)
	 */
	protected function config() {return [
		'forceResult' => $this->s()->forceResult()
	] + parent::config();}

	/**
	 * 2016-08-26
	 * @override
	 * @see \Df\Payment\ConfigProvider::route()
	 * @used-by \Df\Payment\ConfigProvider::getConfig()
	 * @return string
	 */
	protected function route() {return 'dfe-securepay';}
}