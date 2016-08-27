<?php
// 2016-08-27
namespace Dfe\SecurePay\Controller\Confirm;
use Df\Framework\Controller\Result\Text;
class Index extends \Magento\Framework\App\Action\Action {
	/**
	 * 2016-08-27
	 * @override
	 * @see \Magento\Framework\App\Action\Action::execute()
	 * @return Text
	 */
	public function execute() {
		df_log(__METHOD__);
		df_log($_REQUEST);
		return Text::i('1|OK');
	}
}