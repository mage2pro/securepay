<?php
namespace Dfe\SecurePay\Source;
final class ForceResult extends \Df\Config\Source {
	/**
	 * 2016-08-26
	 * «3.6.2 Simulating Approved and Declined Transactions
	 * You can simulate approved and declined transactions by submitting alternative payment amounts.
	 * If the payment amount ends in 00, 08, 11 or 16,
	 * the transaction will be approved once card details are submitted.
	 * All other options will cause a declined transaction.»
	 * @override
	 * @see \Df\Config\Source::map()
	 * @used-by \Df\Config\Source::toOptionArray()
	 * @return array(string => string)
	 */
	protected function map() {return ['no' => 'No', 'approve' => 'Approve', 'decline' => 'Decline'];}
}