<?php
namespace Dfe\SecurePay\Signer;
use Df\Payment\W\F;
use Dfe\SecurePay\W\Handler as W;
// 2016-08-27
/** @method W caller() */
final class Response extends \Dfe\SecurePay\Signer {
	/**
	 * 2016-08-27
	 * For EPS_TXNTYPE 0-7: merchant, transaction password, reference, amount, timestamp, summarycode
	 * For EPS_TXNTYPE 8: merchant, transaction password, store type, reference, timestamp, summarycode
	 * @override
	 * @see \Dfe\SecurePay\Signer::values()
	 * @used-by \Dfe\SecurePay\Signer::sign()
	 * @return string[]
	 */
	protected function values() {
		/** @var array(string => mixed) $p */
		$p = df_trans_rd(F::s($this)->nav()->p());
		/** @var array(string => mixed) $v */
		$v = $this->v();
		return array_merge(
			[$v['merchant']]
			,8 === intval($p['EPS_TXNTYPE']) 
				? [$p['EPS_STORETYPE'], $v['refid']] : [$v['refid'], $p['EPS_AMOUNT']]
			,[$v['timestamp'], $v['summarycode']]
		);
	}
}