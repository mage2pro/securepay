<?php
namespace Dfe\SecurePay\Signer;
use Dfe\SecurePay\W\Handler as W;
// 2016-08-27
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
	protected function values() {/** @var array(string => mixed) $v */$v = $this->v(); return array_merge(
		[$v['merchant']],
		8 === intval($this->req('EPS_TXNTYPE'))
			? [$this->req('EPS_STORETYPE'), $v['refid']] : [$v['refid'], $this->req('EPS_AMOUNT')],
		[$v['timestamp'], $v['summarycode']]
	);}

	/**
	 * 2016-08-27
	 * @param string $p
	 * @return string|null
	 */
	private function req($p) {return $this->caller()->parentInfo($p);}
}