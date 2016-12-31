<?php
// 2016-08-27
namespace Dfe\SecurePay\Signer;
use Dfe\SecurePay\Webhook as W;
/** @method W caller() */
final class Response extends \Dfe\SecurePay\Signer {
	/**
	 * 2016-08-27
	 *
	 * For EPS_TXNTYPE 0-7: merchant, transaction password, reference, amount, timestamp, summarycode
	 * For EPS_TXNTYPE 8: merchant, transaction password, store type, reference, timestamp, summarycode
	 *
	 * @override
	 * @see \Dfe\SecurePay\Signer::values()
	 * @used-by \Dfe\SecurePay\Signer::sign()
	 * @return string[]
	 */
	protected function values() {return array_merge(
		[$this['merchant']],
		8 === intval($this->req('EPS_TXNTYPE'))
			? [$this->req('EPS_STORETYPE'), $this['refid']] : [$this['refid'], $this->req('EPS_AMOUNT')],
		[$this['timestamp'], $this['summarycode']]
	);}

	/**
	 * 2016-08-27
	 * @param string $p
	 * @return string|null
	 */
	private function req($p) {return $this->caller()->parentInfo($p);}
}
