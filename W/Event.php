<?php
namespace Dfe\SecurePay\W;
# 2017-03-16
final class Event extends \Df\PaypalClone\W\Event {
	/**
	 * 2017-03-16
	 * @override
	 * @see \Df\PaypalClone\W\Event::k_idE()
	 * @used-by \Df\PaypalClone\W\Event::idE()
	 * @return string
	 */
	protected function k_idE() {return 'txnid';}

	/**
	 * 2017-03-16
	 * @override
	 * @see \Df\Payment\W\Event::k_pid()
	 * @used-by \Df\Payment\W\Event::pid()
	 * @return string
	 */
	protected function k_pid() {return 'refid';}

	/**
	 * 2017-03-18
	 * @override
	 * @see \Df\PaypalClone\W\Event::k_signature()
	 * @used-by \Df\PaypalClone\W\Event::signatureProvided()
	 * @return string
	 */
	protected function k_signature() {return 'fingerprint';}

	/**
	 * 2017-03-18
	 * @override
	 * @see \Df\PaypalClone\W\Event::k_status()
	 * @used-by \Df\PaypalClone\W\Event::status()
	 * @return string
	 */
	protected function k_status() {return 'summarycode';}

	/**
	 * 2017-01-18
	 * @override
	 * @see \Df\PaypalClone\W\Event::k_statusT()
	 * @used-by \Df\PaypalClone\W\Event::statusT()
	 * @return string|null
	 */
	protected function k_statusT() {return 'restext';}

	/**
	 * 2017-03-18
	 * @override
	 * @see \Df\PaypalClone\W\Event::statusExpected()
	 * @used-by \Df\PaypalClone\W\Event::isSuccessful()
	 * @return int
	 */
	protected function statusExpected() {return 1;}
}