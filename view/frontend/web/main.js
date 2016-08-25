// 2016-08-25
define ([
	'Df_Payment/card'
], function(parent) {'use strict'; return parent.extend({
	/**
	 * 2016-08-25
	 * https://mage2.pro/t/1989
	 * @returns {String[]}
	 */
	getCardTypes: function() {return ['VI', 'MC'];},
	/**
	 * 2016-08-25
	 * @override
	 * @see https://github.com/magento/magento2/blob/2.1.0/app/code/Magento/Checkout/view/frontend/web/js/view/payment/default.js#L127-L159
	 * @used-by https://github.com/magento/magento2/blob/2.1.0/lib/web/knockoutjs/knockout.js#L3863
	 * @param {this} _this
	*/
	placeOrder: function(_this) {
		if (this.validate()) {
			this.placeOrderInternal();
		}
	}
});});
