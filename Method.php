<?php
// 2016-08-25
namespace Dfe\SecurePay;
use Magento\Sales\Model\Order\Creditmemo as CM;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Payment\Transaction as T;
class Method extends \Df\Payment\R\Method {
	/**
	 * 2016-08-28
	 * @override
	 * @see \Df\Payment\Method::canRefund()
	 * @return bool
	 */
	//public function canRefund() {return true;}

	/**
	 * 2016-08-28
	 * @override
	 * @see \Df\Payment\Method::refund()
	 * @param float|null $amount
	 * @return void
	 */
	protected function _refund($amount) {
		/**
		 * 2016-03-17
		 * Метод @uses \Magento\Sales\Model\Order\Payment::getAuthorizationTransaction()
		 * необязательно возвращает транзакцию типа «авторизация»:
		 * в первую очередь он стремится вернуть родительскую транзакцию:
		 * https://github.com/magento/magento2/blob/8fd3e8/app/code/Magento/Sales/Model/Order/Payment/Transaction/Manager.php#L31-L47
		 * Это как раз то, что нам нужно, ведь наш модуль может быть настроен сразу на capture,
		 * без предварительной транзакции типа «авторизация».
		 */
		/** @var T|false $tFirst */
		$tFirst = $this->ii()->getAuthorizationTransaction();
		if ($tFirst) {
			/** @var CM $cm */
			$cm = $this->ii()->getCreditmemo();
			/**
			 * 2016-03-24
			 * Credit Memo и Invoice отсутствуют в сценарии Authorize / Capture
			 * и присутствуют в сценарии Capture / Refund.
			 */
			df_assert($cm);
			/** @var Invoice $invoice */
			$invoice = $cm->getInvoice();
			// 2016-08-20
			// Иначе автоматический идентификатор будет таким: <первичная транзакция>-capture-refund
			$this->ii()->setTransactionId(self::transactionIdL2G('refund'));
			$this->iiaSetTR([]);
		}
	}

	/**
	 * 2016-08-27
	 * @used-by \Df\Payment\R\Method::getConfigPaymentAction()
	 * @override
	 * @see \Df\Payment\R\Method::stageNames()
	 * @return string
	 */
	protected function redirectUrl() {return 'https://api.securepay.com.au/{stage}/directpost/authorise';}

	/**
	 * 2016-08-27
	 * Первый параметр — для test, второй — для live.
	 * @override
	 * @see \Df\Payment\R\Method::stageNames()
	 * @used-by \Df\Payment\R\Method::getConfigPaymentAction()
	 * @return string[]
	 */
	protected function stageNames() {return ['test', 'live'];}
}