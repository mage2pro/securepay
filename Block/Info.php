<?php
// 2016-08-28
namespace Dfe\SecurePay\Block;
use Dfe\SecurePay\Method;
use Dfe\SecurePay\Response as R;
use Magento\Framework\DataObject;
/**
 * @method Method method()
 * @method R|string|null responseF(string $key = null)
 */
class Info extends \Df\Payment\Block\ConfigurableInfo {
	/**
	 * 2016-08-28
	 * @override
	 * @see \Magento\Payment\Block\ConfigurableInfo::_prepareSpecificInformation()
	 * @used-by \Magento\Payment\Block\Info::getSpecificInformation()
	 * @param DataObject|null $transport
	 * @return DataObject
	 */
	protected function _prepareSpecificInformation($transport = null) {
		/** @var DataObject $result */
		$result = parent::_prepareSpecificInformation($transport);
		$result['Card Number'] = str_replace('...', '*******', $this->responseF('pan'));
		if ($this->isBackend()) {
			$result['SecurePay ID'] = $this->responseF('txnid');
		}
		$this->markTestMode($result);
		return $result;
	}
}


