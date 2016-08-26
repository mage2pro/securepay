<?php
// 2016-08-25
namespace Dfe\SecurePay;
use Df\Payment\PlaceOrder;
use Dfe\SecurePay\Settings as S;
class Method extends \Df\Payment\Method {
	/**
	 * @override
	 * @see \Df\Payment\Method::getConfigPaymentAction()
	 * @return string
	 *
	 * 2016-08-26
	 * Сюда мы попадаем только из метода @used-by \Magento\Sales\Model\Order\Payment::place()
	 * причём там наш метод вызывается сразу из двух мест и по-разному.
	 * Умышленно возвращаем null.
	 * @used-by \Magento\Sales\Model\Order\Payment::place()
	 * https://github.com/magento/magento2/blob/ffea3cd/app/code/Magento/Sales/Model/Order/Payment.php#L334-L355
	 */
	public function getConfigPaymentAction() {
		/** @var array(string => mixed) $params */
		$params = Charge::request($this->ii());
		/** @var string $stage */
		$stage = S::s()->test() ? 'test' : 'live';
		/** @var string $url */
		$url = "https://api.securepay.com.au/{$stage}/directpost/authorise";
		/**
		 * 2016-07-01
		 * К сожалению, если передавать в качестве результата ассоциативный массив,
		 * то его ключи почему-то теряются. Поэтому запаковываем массив в JSON.
		 */
		$this->iiaSet(PlaceOrder::DATA, df_json_encode(['params' => $params, 'uri' => $url]));
		// 2016-05-06
		// Письмо-оповещение о заказе здесь ещё не должно отправляться.
		// «How is a confirmation email sent on an order placement?» https://mage2.pro/t/1542
		$this->o()->setCanSendNewEmailFlag(false);
		// 2016-07-10
		// Сохраняем информацию о транзакции.
		$this->saveRequest($this->o()->getIncrementId(), $url, $params);
		return null;
	}

	/**
	 * 2016-08-26
	 * @return string
	 */
	private static function url() {
		/** @var string $stage */
		$stage = S::s()->test() ? 'test' : 'live';
		return "https://api.securepay.com.au/{$stage}/directpost/authorise";
	}
}