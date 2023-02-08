<?php
# 2016-08-25
namespace Dfe\SecurePay;
use Magento\Sales\Model\Order\Creditmemo as CM;
use Magento\Sales\Model\Order\Payment\Transaction as T;
final class Method extends \Df\PaypalClone\Method {
	/**
	 * 2016-08-31
	 * 2017-11-14
	 * AlphaCommerceHub (another Australian payment service provider)
	 * has a similar rule: @see \Dfe\AlphaCommerceHub\Method::amountFormat()
	 * "The last 3 digits of every payment amount should be «000» in the test mode for Westpac":
	 * https://github.com/mage2pro/alphacommercehub/issues/17
	 * https://github.com/mage2pro/alphacommercehub/blob/0.2.8/Method.php#L5-L26
	 * @override
	 * @see \Df\Payment\Method::amountFormat()
	 * @used-by \Df\Payment\ConfigProvider::config()
	 * @used-by \Df\Payment\Operation::amountFormat()
	 * @return float
	 */
	function amountFormat(float $a) {
		if ($this->test()) {
			$approved = in_array(dfp_last2($a), ['00', '08', '11', '16']); /** @var bool $approved */
			/** @var bool $approve */ /** @var string $forceResult */
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
	 * 2017-12-06
	 * 1) @used-by \Magento\Sales\Model\Order\Payment::canRefund():
	 *		public function canRefund() {
	 *			return $this->getMethodInstance()->canRefund();
	 *		}
	 * https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Sales/Model/Order/Payment.php#L271-L277
	 * https://github.com/magento/magento2/blob/2.2.1/app/code/Magento/Sales/Model/Order/Payment.php#L303-L309
	 * 2) @used-by \Magento\Sales\Model\Order\Payment::refund()
	 *		$gateway = $this->getMethodInstance();
	 *		$invoice = null;
	 *		if ($gateway->canRefund()) {
	 *			<...>
	 *		}
	 * https://github.com/magento/magento2/blob/2.0.0/app/code/Magento/Sales/Model/Order/Payment.php#L617-L654
	 * https://github.com/magento/magento2/blob/2.2.1/app/code/Magento/Sales/Model/Order/Payment.php#L655-L698
	 * 3) @used-by \Magento\Sales\Model\Order\Invoice\Validation\CanRefund::canPartialRefund()
	 *		private function canPartialRefund(MethodInterface $method, InfoInterface $payment) {
	 *			return $method->canRefund() &&
	 *			$method->canRefundPartialPerInvoice() &&
	 *			$payment->getAmountPaid() > $payment->getAmountRefunded();
	 *		}
	 * https://github.com/magento/magento2/blob/2.2.1/app/code/Magento/Sales/Model/Order/Invoice/Validation/CanRefund.php#L84-L94
	 * It is since Magento 2.2: https://github.com/magento/magento2/commit/767151b4
	 */
	function canRefund():bool {return true;}

	/**
	 * 2016-08-30
	 * @override
	 * @see \Df\Payment\Method::_refund()
	 * @used-by \Df\Payment\Method::refund()
	 */
	protected function _refund(float $a):void {$this->ii()->setTransactionId($this->tid()->e2i(Refund::p($this)));}

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