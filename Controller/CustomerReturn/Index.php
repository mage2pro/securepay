<?php
// 2016-08-27
namespace Dfe\SecurePay\Controller\CustomerReturn;
use Magento\Framework\Controller\Result\Redirect;
class Index extends \Magento\Framework\App\Action\Action {
	/**
	 * 2016-08-27
	 * @override
	 * @see \Magento\Framework\App\Action\Action::execute()
	 * @return Redirect
	 */
	public function execute() {
		df_log(__METHOD__);
		df_log($_REQUEST);
		return $this->_redirect('checkout/onepage/success');
	}
}


