<?php
// 2016-08-25
namespace Dfe\SecurePay;
use Magento\Sales\Model\Order\Creditmemo as CM;
use Magento\Sales\Model\Order\Payment\Transaction as T;
/** @method Response|null responseF(string $key = null) */
class Method extends \Df\Payment\R\Method {
	/**
	 * 2016-08-28
	 * @override
	 * @see \Df\Payment\Method::canRefund()
	 * @return bool
	 */
	public function canRefund() {return true;}

	/**
	 * 2016-08-31
	 * @override
	 * @see \Df\Payment\Method::formatAmount()
	 * @param float $amount
	 * @return float
	 */
	public function formatAmount($amount) {
		if ($this->s()->test()) {
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
	 * 2016-08-27
	 * Первый параметр — для test, второй — для live.
	 * @override
	 * @see \Df\Payment\R\Method::stageNames()
	 * @used-by \Df\Payment\R\Method::getConfigPaymentAction()
	 * @used-by \Df\Payment\R\Refund::stageNames()
	 * @return string[]
	 */
	public function stageNames() {return ['test', 'live'];}

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
		$this->ii()->setTransactionId(self::transactionIdL2G($id));
		$this->iiaSetTR($p);
	}

	/**
	 * 2016-08-27
	 * @used-by \Df\Payment\R\Method::getConfigPaymentAction()
	 * @override
	 * @see \Df\Payment\R\Method::stageNames()
	 * @return string
	 */
	protected function redirectUrl() {return 'https://api.securepay.com.au/{stage}/directpost/authorise';}
}