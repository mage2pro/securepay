<?php
namespace Dfe\SecurePay;
// 2017-03-23
final class Url extends \Df\Payment\Url {
	/**
	 * 2016-08-27
	 * Первый параметр — для test, второй — для live.
	 * 2017-02-16
	 * SecurePay has changed the URL for Direct Post API testing: https://mage2.pro/t/2779
	 * @override
	 * @see \Df\Payment\Url::stageNames()
	 * @used-by \Df\Payment\Url::url()
	 * @return string[]
	 */
	protected function stageNames() {return ['test.', ''];}
}


