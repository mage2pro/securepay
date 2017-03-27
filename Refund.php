<?php
namespace Dfe\SecurePay;
use Df\Payment\TM;
use Df\Xml\X;
use Magento\Sales\Model\Order\Payment\Transaction as T;
// 2016-08-30
/** @method Method m() */
final class Refund extends \Df\PaypalClone\Refund {
	/**
	 * 2016-09-07
	 * @override
	 * @see \Df\Payment\Operation::amountFormat()
	 * @used-by \Df\Payment\Operation::amountF()
	 * @param float $amount
	 * @return int
	 */
	protected function amountFormat($amount) {return round(100 * parent::amountFormat($amount));}

	/**
	 * 2016-08-20
	 * @used-by \Dfe\SecurePay\Refund::p()
	 * @return void
	 */
	private function process() {
		/** @var Settings $s */
		$s = $this->s();
		/** @var TM $tm */
		$tm = df_tm($this->m());
		/** @var string $xA */
		$xA = df_xml_g('SecurePayMessage', [
			'MessageInfo' => [
				'messageID' => df_cdata(df_cc('-', $this->cm()->getIncrementId(), df_uid(4)))
				,'messageTimestamp' => $this->timestamp()
				,'timeoutValue' => 120
				,'apiVersion' => 'xml-4.2'
			]
			,'MerchantInfo' => ['merchantID' => $s->merchantID(), 'password' => df_cdata($s->password())]
			,'RequestType' => 'Payment'
			,'Payment' => [
				df_xml_node('TxnList', ['count' => 1], [
					df_xml_node('Txn', ['ID' => 1], [
						'txnType' => 4
						,'txnSource' => 23
						,'amount' => $this->amountF()
						,'purchaseOrderNo' => df_cdata($tm->req('EPS_REFERENCEID'))
						,'txnID' => $tm->responseF('txnid')
					])
				])
			]
		]);
		/** @var \Zend_Http_Client $c */
		$c = (new \Zend_Http_Client)
			->setHeaders('content-type', 'text/xml')
			->setConfig(['timeout' => 120])
			->setUri(dfp_url($this, 'https://{stage}.securepay.com.au/xmlapi/payment', ['test', 'api']))
			->setRawData($xA)
		;
		/** @var string $xB */
		$xB = $c->request(\Zend_Http_Client::POST)->getBody();
		/** @var string $xAL */
		$xAL = df_xml_prettify(str_replace($s->password(), '*****', $xA));
		/** @var string $xBL */
		$xBL = df_xml_prettify($xB);
		$this->m()->iiaSetTRR($xAL, $xBL);
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
		else {
			// 2016-09-01
			// При повторной попытке возврата SecurePay всё равно возвращает:
			//	<Status>
			//		<statusCode>000</statusCode>
			//		<statusDescription>Normal</statusDescription>
			//	</Status>
			//
			// Поэтому допольнительно смотрим другой кусок ответа: Payment/TxnList/Txn
			// В случае успеха там:
			//		<approved>Yes</approved>
			//		<responseCode>00</responseCode>
			//		<responseText>Approved</responseText>
			//		<thinlinkResponseCode>100</thinlinkResponseCode>
			//		<thinlinkResponseText>000</thinlinkResponseText>
			//		<thinlinkEventStatusCode>000</thinlinkEventStatusCode>
			//		<thinlinkEventStatusText>Normal</thinlinkEventStatusText>
			// В случае сбоя там:
			//		<approved>No</approved>
			//		<responseCode>134</responseCode>
			//		<responseText>Transaction already fully refunded</responseText>
			//		<thinlinkResponseCode>300</thinlinkResponseCode>
			//		<thinlinkResponseText>000</thinlinkResponseText>
			//		<thinlinkEventStatusCode>999</thinlinkEventStatusCode>
			//		<thinlinkEventStatusText>Error - Transaction Already Fully Refunded/Only $x.xx Available for Refund</thinlinkEventStatusText>
			/** @var X $txn */
			$txn = $xxB->{'Payment'}->{'TxnList'}->{'Txn'};
			if ('Yes' !== df_leaf_sne($txn->{'approved'})) {
				$errorMessage = df_leaf_sne($txn->{'thinlinkEventStatusText'});
			}
		}
		if ($errorMessage) {
			// 2016-09-01
			// Из-за бага в ядре исключительная ситуация при refund не только не логируется,
			// а и вообще теряется. Поэтому мы и логируем её сами.
			dfp_report($this, $xAL, 'request');
			dfp_report($this, $xBL, 'response');
			df_error($errorMessage);
		}
	}

	/**
	 * 2016-08-31
	 * API requires the timezone offset in minutes
	 * https://github.com/thephpleague/omnipay-securepay/blob/a7b1b5/src/Message/SecureXMLAbstractRequest.php#L124-L138
	 * @return string
	 */
	private function timestamp() {/** @var \DateTime $d */ $d = new \DateTime; return $d->format(
		sprintf('YmdHis000%+04d', $d->format('Z') / 60)
	);}

	/**
	 * 2016-08-27
	 * @param Method $m
	 * @return string
	 */
	static function p(Method $m) {
		/** @var self $i */
		$i = new self($m);
		$i->process();
		return $i->cm()->getIncrementId();
	}
}