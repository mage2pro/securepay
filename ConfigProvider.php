<?php
# 2016-08-25
namespace Dfe\SecurePay;
/** @method Settings s() */
final class ConfigProvider extends \Df\Payment\ConfigProvider\BankCard {
	/**
	 * 2016-08-25
	 * @override
	 * @see \Df\Payment\ConfigProvider\BankCard::config()
	 * @used-by \Df\Payment\ConfigProvider::getConfig()
	 * @return array(string => mixed)
	 */
	protected function config():array {return ['forceResult' => $this->s()->forceResult()] + parent::config();}
}