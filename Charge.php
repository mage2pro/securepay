<?php
namespace Dfe\SecurePay;
/**
 * 2016-08-26
 * 2017-08-13
 * The charge parameters are described folowing places
 * of the «Direct Post Integration Guide», Version 1.4, 2017-02-28:
 * 1) Formally: in the Chapter 5.1 «Appendix 1: Accepted Input Fields» (pages 24-31)
 * 2) Informally: in the Chapters 3.3 «Mandatory Fields» and 3.4 «Optional Features» (pages 10-19)
 * https://mage2.pro/t/4271
 * @method Method m()
 * @method Settings s()
 */
final class Charge extends \Df\PaypalClone\Charge {
	/**
	 * 2016-08-29
	 * 2016-08-26
	 * «5.1.1.4 Payment Reference».
	 * Mandatory
	 * String, min length 1, max length 60
	 * «A string that identifies the transaction.
	 * This string is stored by SecurePay as the Transaction Reference.
	 * This field is typically a shopping cart id or invoice number
	 * and is used to match the SecurePay transaction to your application.»
	 * @override
	 * @see \Df\PaypalClone\Charge::k_RequestId()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function k_RequestId() {return 'EPS_REFERENCEID';}

	/**
	 * 2016-08-27
	 * @override
	 * @see \Df\PaypalClone\Charge::k_Signature()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function k_Signature() {return 'EPS_FINGERPRINT';}

	/**
	 * 2016-09-07
	 * @override
	 * @see \Df\Payment\Operation::amountFormat()
	 * @used-by \Df\Payment\Operation::amountF()
	 * 2016-08-26
	 * «5.1.1.2 Transaction Amount».
	 * Mandatory
	 * Numeric, two decimal places, from 0.01 to 99999999.99
	 * «The total amount of the purchase transaction.
	 * By default the currency is AUD (Australian Dollars).»
	 * @param float $a
	 * @return string
	 */
	protected function amountFormat($a) {return df_f2(parent::amountFormat($a));}

	/**
	 * 2016-08-26
	 * @override
	 * @see \Df\PaypalClone\Charge::pCharge()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return array(string => mixed)
	 */
	protected function pCharge() {$s = $this->s(); return [
		// 2016-08-26
		// Mandatory when EPS_TXNTYPE includes 3D Secure
		// String, length 20
		// «3D Secure Transaction ID string.
		// MUST uniquely reference this transaction to the Merchant,
		// and MUST be 20 characters in length.
		// Any ASCII characters may be used to build this string.
		// E.g. May comprise of a timestamp padded with 0s for uniqueness: "20110714112034872000".»
		'3D_XID' => str_pad($this->id(), 20, '0')
		// 2016-08-26
		// «5.1.1.2 Transaction Amount».
		// Mandatory
		// Numeric, two decimal places, from 0.01 to 99999999.99
		// «The total amount of the purchase transaction.
		// By default the currency is AUD (Australian Dollars).»
		,'EPS_AMOUNT' => $this->amountF()
		// 2016-08-26
		// Optional
		// String, length 2, ISO 4217 currency code
		// «Payee’s Country two letter code»
		,'EPS_BILLINGCOUNTRY' => $this->addressB()->getCountryId()
		// 2016-08-27
		// Если этот параметр указан, то система передаёт сюда те же параметры, что и на EPS_RESULTURL.
		// Учитывая, что система всё равно передаст те же параметры на EPS_RESULTURL,
		// то изначально неочевиден смысл реализовывать ещё и  EPS_CALLBACKURL.
		//
		// Однако со временем увидел некоторые преимущества:
		// 1) унификация архитектуры с другими моими модулями
		// 2) упрощение кода контроллера
		// (не смешиваем обработку операций возвращения покупателя и оповещения о платеже)
		// 3) возможности увидеть статус обработки оповещения о платеже (код HTTP)
		// в личном кабинете магазина.
		// Поэтому оставил EPS_CALLBACKURL.
		,'EPS_CALLBACKURL' => $this->callback()
		// 2016-08-26
		// «5.1.1.12 Currency».
		// Optional (default AUD)
		// «If your bank supports multicurrency,
		// you may optionally set the currency of the transaction to one other than AUD.»
		// «Used to set the transaction currency sent to the bank for processing.
		// You must have a bank merchant facility
		// that accepts currencies other than AUD before using this feature.
		// Set the currency to any ISO 4217 three letter currency code. E.g. USD, NZD, GBP, etc.»
		,'EPS_CURRENCY' => $this->currencyC()
		// 2016-08-26
		// Optional
		// String, length 2, ISO 4217 currency code
		// «Order delivery country two letter code»
		,'EPS_DELIVERYCOUNTRY' => !$this->addressS() ? null : $this->addressS()->getCountryId()
		// 2016-08-26
		// Optional
		// String, length 2, ISO 4217 currency code
		// «Payee’s email address»
		,'EPS_EMAILADDRESS' => $this->customerEmail()
		// 2016-08-26
		// Optional
		// String, length less than 30
		// «Payee’s first name»
		,'EPS_FIRSTNAME' => $this->customerNameF()
		// 2016-08-26
		// Mandatory when EPS_TXNTYPE includes FraudGuard
		// String, length up to 15
		// «Payee’s IPV4 IP Address – should be obtained from the card holder’s browser.
		// Typically a programmatic environment variable such as remote IP.»
		,'EPS_IP' => $this->customerIp()
		// 2016-08-26
		// Optional
		// String, length less than 30
		// «Payee’s last name»
		,'EPS_LASTNAME' => $this->customerNameL()
		// 2016-08-26
		// «5.1.1.1 Merchant ID».
		// Mandatory
		// Alpha-numeric, length 7
		// «The Merchant ID field, “EPS_MERCHANT”, is mandatory.
		// It is the SecurePay account to process payments.
		// SecurePay Customer Support will supply your Merchant ID when your account is activated.
		// The Merchant ID will be of the format “ABC0010”,
		// where ABC is your unique three-letter account code,
		// also used for logging in to the SecurePay Merchant Log In.»
		,'EPS_MERCHANT' => $s->merchantID()
		// 2016-08-26
		// Mandatory when EPS_TXNTYPE includes 3D Secure
		// String, length less than 20
		// «Your online merchant number specified by your bank
		// which has been registered for Verified by Visa or SecureCode, or both.
		// This will be your bank merchant number, e.g. "22123456".»
		,'EPS_MERCHANTNUM' => !$s->enable3DS() ? null : $s->merchantID_3DS()
		/**
		 * 2016-08-26
		 * Result Page Redirects
		 * Optional (default “FALSE”)
		 * String, values “FALSE” or “TRUE”
		 *
		 * «If your web site redirects the Direct Post result to another page on your site,
		 * Direct will automatically follow the redirect.
		 * This will occur until Direct Post is no longer redirected.
		 *
		 * Direct Post will POST result parameters the first time it calls your server,
		 * but Direct Post will send result parameters using the GET method
		 * based on RFC 2616 standards after being redirected.»
		 *
		 * «Directs the system to redirect to the EPS_RESULTURL.
		 * Result parameters are appended to the URL as a GET string.
		 * Validate the result fingerprint to ensure integrity of the bank response.
		 * Use the EPS_CALLBACK if separate database update and page redirect URL’s are required.»
		 *
		 * 2016-08-27
		 * Для моего модуля здесь нужно ставить обязательно TRUE,
		 * потому что @see \Df\Payment\CustomerReturn::execute()
		 * делает редиректы (на «checkout/onepage/success» или к корзине).
		 */
		,'EPS_REDIRECT' => 'TRUE'
		/**
		 * 2016-08-26
		 * «5.1.1.11 Payment Reference».
		 * Mandatory
		 * String, fully-qualified URL
		 *
		 * «When a transaction is complete (approved or declined),
		 * Direct Post redirects the browser to your result page
		 * with the transaction result in a series of POST fields.
		 *
		 * If you redirect Direct Post to another URL,
		 * fields may be sent via the GET method. Please handle both GET and POST methods.»
		 *
		 * «The URL on the Merchant web site that accepts transaction result data as POST elements.
		 * The result page may be almost any form of web page,
		 * including static HTML pages, CGI scripts, ASP pages, JSP pages, PHP scripts, etc,
		 * however cookies or other forms of additional information
		 * will not be passed through the Payment Gateway.
		 *
		 * The EPS_RESULTURL must be a URL for a publicly visible page on a web server
		 * within a domain that is delegated to a public IP number.
		 * Internal machine names, such as "localhost", Windows-style machine names,
		 * and privately translated IP numbers will fail.»
		 */
		,'EPS_RESULTURL' => $this->customerReturnRemote()
		/**
		 * 2016-08-26
		 * «5.1.1.5 GMT Timestamp».
		 * Mandatory
		 * String, format "YYYYMMDDHHMMSS" in GMT.
		 * «The GMT time used for Fingerprint generation.
		 * This value must be the same submitted to generate a fingerprint
		 * as submitted with the transaction.
		 * SecurePay validates the time to within one hour of current time.
		 * The time component must be in 24 hour time format.»
		 * https://github.com/thephpleague/omnipay-securepay/blob/v2.1.0/src/Message/DirectPostAuthorizeRequest.php#L22
		 */
		,'EPS_TIMESTAMP' => gmdate('YmdHis')
		// 2016-08-26
		// Optional
		// String, length less than 30
		// «Payee’s town»
		,'PS_TOWN' => $this->addressSB()->getCity()
		/**
		 * 2016-08-26
		 * «5.1.1.2 Transaction Type».
		 * Mandatory
		 * Numeric
		 * «Used to determine the processing type for an individual transaction.
		 * May be one of the following:
		 *
		 * 0	PAYMENT: A card payment/purchase transaction.
		 *		Note: This is the only accepted type for PayPal payments.
		 *
		 * 1	PREAUTH: Used to pre-authorise an amount on a card.
		 * 		The result parameters include the “preauthid”
		 * 		which must be stored and used when completing the pre-authorisation
		 *
		 * 2	PAYMENT with FRAUDGUARD:
		 * 		A card payment/purchase transaction with the optional FraudGuard service
		 *
		 * 3	PREAUTH with FRAUDGUARD:
		 * 		A card preauthorisation transaction with the optional FraudGuard service
		 *
		 * 4	PAYMENT with 3D Secure:
		 * 		A card payment/purchase transaction with the optional 3D Secure service
		 *
		 * 5	PREAUTH with 3D Secure:
		 * 		A card preauthorisation transaction with the optional 3D Secure service
		 *
		 * 6	PAYMENT with FRAUDGUARD and 3D Secure:
		 * 		A card payment/purchase transaction with the optional FraudGuard and 3D Secure services
		 *
		 * 7	PREAUTH with FRAUDGUARD and 3D Secure:
		 * 		A card preauthorisation transaction with the optional FraudGuard and 3D Secure services
		 *
		 * 8	STORE ONLY: This will store the card details
		 * 		without taking a payment or preauthorisation.
		 * 		See section 3.4.4.4. for more details».
		 */
		,'EPS_TXNTYPE' => 0
		// 2016-08-26
		// Optional
		// String, length less than 30
		// «Payee’s zip/post code»
		,'EPS_ZIPCODE' => $this->addressSB()->getPostcode()
	];}
}