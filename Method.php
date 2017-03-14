<?php
// 2016-08-25
namespace Dfe\SecurePay;
use Df\Payment\W\Event;
use Magento\Sales\Model\Order\Creditmemo as CM;
use Magento\Sales\Model\Order\Payment\Transaction as T;
/** @method Event|string|null responseF(string $k = null) */
final class Method extends \Df\PaypalClone\Method\Normal {
	/**
	 * 2016-08-31
	 * @override
	 * @see \Df\Payment\Method::amountFormat()
	 * @param float $amount
	 * @return float
	 */
	function amountFormat($amount) {
		if ($this->test()) {
			/** @var string $forceResult */
			$forceResult = $this->s()->forceResult();
			/** @var string $amountLast2 */
			$amountLast2 = dfp_last2($amount);
			/** @var bool $approved */
			$approved = in_array($amountLast2, ['00', '08', '11', '16']);
			/** @var bool $approve */
			$approve = 'approve' === $forceResult;
			/** @var bool $needAdjust */
			$needAdjust = ('no' !== $forceResult) && ($approve !== $approved);
			if ($needAdjust) {
				$amount = $approve ? round($amount) : $amount + 0.01;
			}
		}
		return $amount;
	}

	/**
	 * 2016-08-28
	 * @override
	 * @see \Df\Payment\Method::canRefund()
	 * @return bool
	 */
	function canRefund() {return true;}

	/**
	 * 2016-08-27
	 * Первый параметр — для test, второй — для live.
	 * 2017-02-16
	 * SecurePay has changed the URL for Direct Post API testing: https://mage2.pro/t/2779
	 * @override
	 * @see \Df\PaypalClone\Method\Normal::stageNames()
	 * @used-by \Df\PaypalClone\Method\Normal::url()
	 * @used-by \Df\PaypalClone\Refund::stageNames()
	 * @return string[]
	 */
	function stageNames() {return ['test.', ''];}

	/**
	 * 2016-08-30
	 * @override
	 * @see \Df\Payment\Method::_refund()
	 * @used-by \Df\Payment\Method::refund()
	 * @param float|null $amount
	 * @return void
	 */
	protected function _refund($amount) {
		/** @var string $id */
		/** @var array(string => mixed) $p */
		list($id, $p) = Refund::p($this);
		// 2016-08-20
		// Иначе автоматический идентификатор будет таким: <первичная транзакция>-capture-refund
		$this->ii()->setTransactionId(self::e2i($id));
		$this->iiaSetTR($p);
	}

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

	/**
	 * 2016-08-27
	 * 2017-02-16
	 * SecurePay has changed the URL for Direct Post API testing: https://mage2.pro/t/2779
	 * @override
	 * @see \Df\PaypalClone\Method\Normal::redirectUrl()
	 * @used-by \Df\PaypalClone\Method\Normal::getConfigPaymentAction()
	 * @return string
	 */
	protected function redirectUrl() {return
		'https://{stage}api.securepay.com.au/live/directpost/authorise'
	;}
}