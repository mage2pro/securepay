<?php
// 2016-08-30
namespace Dfe\SecurePay;
use Df\Xml\X;
use Dfe\SecurePay\Settings as S;
use Magento\Sales\Model\Order\Payment\Transaction as T;
/** @method Method m() */
final class Refund extends \Df\Payment\R\Refund {
	/**
	 * 2016-08-31
	 * @override
	 * @see \Df\Payment\Operation::amount()
	 * @return float
	 */
	protected function amount() {return dfc($this, function() {return
		$this->m()->adjustAmount(parent::amount());
	});}

	/**
	 * 2016-08-31
	 * Первый параметр — для test, второй — для live.
	 * @override
	 * @see \Df\Payment\R\Refund::stageNames()
	 * @return string[]
	 */
	protected function stageNames() {return ['test', 'api'];}

	/**
	 * 2016-08-20
	 * @used-by \Dfe\SecurePay\Refund::p()
	 * @return void
	 */
	private function process() {
		/** @var S $s */
		$s = S::s();
		/** @var string $xA */
		$xA = df_xml_g('SecurePayMessage', [
			'MessageInfo' => [
				'messageID' => df_cdata(df_cc('-', $this->cm()->getIncrementId(), df_uid(4)))
				,'messageTimestamp' => $this->timestamp()
				,'timeoutValue' => 120
				,'apiVersion' => 'xml-4.2'
			]
			,'MerchantInfo' => [
				'merchantID' => $s->merchantID()
				,'password' => df_cdata($s->transactionPassword())
			]
			,'RequestType' => 'Payment'
			,'Payment' => [
				df_xml_node('TxnList', ['count' => 1], [
					df_xml_node('Txn', ['ID' => 1], [
						'txnType' => 4
						,'txnSource' => 23
						,'amount' => round(100 * $this->amount())
						,'purchaseOrderNo' => df_cdata($this->requestP('EPS_REFERENCEID'))
						,'txnID' => $this->responseF('txnid')
					])
				])
			]
		]);
		/** @var \Zend_Http_Client $c */
		$c = new \Zend_Http_Client;
		$c->setHeaders('content-type', 'text/xml');
		$c->setConfig(['timeout' => 120]);
		$c->setUri($this->m()->url('https://{stage}.securepay.com.au/xmlapi/payment'));
		$c->setRawData($xA);
		/** @var string $xB */
		$xB = $c->request(\Zend_Http_Client::POST)->getBody();
		/** @var string $xAL */
		$xAL = df_xml_prettify(str_replace($s->transactionPassword(), '*****', $xA));
		/** @var string $xBL */
		$xBL = df_xml_prettify($xB);
		$this->m()->iiaSetTRR([$xAL, $xBL]);
		/** @var X $xxB */
		$xxB = df_xml_parse($xB);
		/** @var X $status */
		$status = $xxB->{'Status'};
		/** @var string $code */
		$code = df_leaf_sne($status->{'statusCode'});
		/** @var $errorMessage */
		$errorMessage = null;
		if ('000' !== $code) {
			/** @var string $message */
			$errorMessage = df_leaf_sne($status->{'statusDescription'});
		}
		/**
		 * 2016-09-01
		 * При повторной попытке возврата SecurePay всё равно возвращает:
			<Status>
				<statusCode>000</statusCode>
				<statusDescription>Normal</statusDescription>
			</Status>
		 */
		else {
			$errorMessage = df_leaf_sne(
				$xxB->{'Payment'}->{'TxnList'}->{'Txn'}->{'thinlinkEventStatusText'}
			);
		}
		if ($errorMessage) {
			/**
			 * 2016-09-01
			 * Из-за бага в ядре исключительная ситуация при refund не только не логируется,
			 * а и вообще теряется. Поэтому мы и логируем её сами.
			 */
			/** @var string $code */
			$code = dfp_method_code($this);
			/** @var string $namePrefix */
			$namePrefix = "mage2.pro/{$code}-{date}--{time}--";
			df_report("{$namePrefix}request.log", $xAL);
			df_report("{$namePrefix}response.log", $xBL);
			df_error($errorMessage);
		}
	}

	/**
	 * 2016-08-31
	 * https://github.com/thephpleague/omnipay-securepay/blob/a7b1b5/src/Message/SecureXMLAbstractRequest.php#L124-L138
	 * @return string
	 */
	private function timestamp() {
		/** @var \DateTime $date */
		$date = new \DateTime;
		// API requires the timezone offset in minutes
		return $date->format(sprintf('YmdHis000%+04d', $date->format('Z') / 60));
	}

	/**
	 * 2016-08-27
	 * @param Method $method
	 * @return array(string, array(string => mixed))
	 */
	public static function p(Method $method) {
		/** @var self $i */
		$i = new static([self::$P__METHOD => $method]);
		$i->process();
		return [$i->cm()->getIncrementId(), []];
	}
}