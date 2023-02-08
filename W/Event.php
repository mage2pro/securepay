<?php
namespace Dfe\SecurePay\W;
# 2017-03-16
final class Event extends \Df\PaypalClone\W\Event {
	/**
	 * 2017-03-16
	 * @override
	 * @see \Df\PaypalClone\W\Event::k_idE()
	 * @used-by \Df\PaypalClone\W\Event::idE()
	 */
	protected function k_idE():string {return 'txnid';}

	/**
	 * 2017-03-16
	 * @override
	 * @see \Df\Payment\W\Event::k_pid()
	 * @used-by \Df\Payment\W\Event::pid()
	 */
	protected function k_pid():string {return 'refid';}

	/**
	 * 2017-03-18
	 * @override
	 * @see \Df\PaypalClone\W\Event::k_signature()
	 * @used-by \Df\PaypalClone\W\Event::signatureProvided()
	 */
	protected function k_signature():string {return 'fingerprint';}

	/**
	 * 2017-03-18
	 * @override
	 * @see \Df\PaypalClone\W\Event::k_status()
	 * @used-by \Df\PaypalClone\W\Event::status()
	 */
	protected function k_status():string {return 'summarycode';}

	/**
	 * 2017-01-18
	 * @override
	 * @see \Df\PaypalClone\W\Event::k_statusT()
	 * @used-by \Df\PaypalClone\W\Event::statusT()
	 */
	protected function k_statusT():string {return 'restext';}

	/**
	 * 2017-03-18
	 * @override
	 * @see \Df\PaypalClone\W\Event::statusExpected()
	 * @used-by \Df\PaypalClone\W\Event::isSuccessful()
	 */
	protected function statusExpected():string {return '1';}
}