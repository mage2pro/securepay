<?php
// 2016-08-25
namespace Dfe\SecurePay;
use Df\Payment\W\Event;
use Magento\Sales\Model\Order\Creditmemo as CM;
use Magento\Sales\Model\Order\Payment\Transaction as T;
final class Method extends \Df\PaypalClone\Method {
	/**
	 * 2016-08-31
	 * @override
	 * @see \Df\Payment\Method::amountFormat()
	 * @param float $a
	 * @return float
	 */
	function amountFormat($a) {
		if ($this->test()) {
			/** @var bool $approved */
			$approved = in_array(dfp_last2($a), ['00', '08', '11', '16']);
			/** @var bool $approve */
			/** @var string $forceResult */
			$approve = 'approve' === ($forceResult = $this->s()->forceResult());
			if ('no' !== $forceResult && $approve !== $approved) {
				$a = $approve ? round($a) : $a + 0.01;
			}
		}
		return $a;
	}

	/**
	 * 2016-08-28
	 * @override
	 * @see \Df\Payment\Method::canRefund()
	 * @return bool
	 */
	function canRefund() {return true;}

	/**
	 * 2016-08-30
	 * @override
	 * @see \Df\Payment\Method::_refund()
	 * @used-by \Df\Payment\Method::refund()
	 * @param float|null $amt
	 */
	protected function _refund($amt) {$this->ii()->setTransactionId($this->tid()->e2i(Refund::p($this)));}

	/**
	 * 2017-02-08
	 * @override
	 * The result should be in the basic monetary unit (like dollars), not in fractions (like cents).
	 * I did not find such information on the SecurePay website.
	 * «Does SecurePay have minimum and maximum amount limitations on a single payment?»
	 * https://mage2.pro/t/2693
	 * https://mail.google.com/mail/u/0/#sent/15a1f5d2ca41fb90
	 * @see \Df\Payment\Method::amountLimits()
	 * @used-by \Df\Payment\Method::isAvailable()
	 * @return null
	 */
	protected function amountLimits() {return null;}
}