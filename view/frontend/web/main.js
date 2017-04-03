// 2016-08-25
define([
	'df', 'Df_Core/my/redirectWithPost', 'Df_Payment/card', 'jquery'
], function(df, redirectWithPost, parent, $) {'use strict'; return parent.extend({
	/**
	 * 2016-08-26
	 * «3.6.2 Simulating Approved and Declined Transactions
	 * You can simulate approved and declined transactions by submitting alternative payment amounts.
	 * If the payment amount ends in 00, 08, 11 or 16,
	 * the transaction will be approved once card details are submitted.
	 * All other options will cause a declined transaction.»
	 * @override
	 * @see mage2pro/core/Payment/view/frontend/web/mixin.js
	 * @returns {String}
	 */
	debugMessage: df.c(function() {
		/** @type {String} */
		var forceResult = this.config('forceResult');
		/** @type {Boolean} */
		var approved = -1 !== ['00', '08', '11', '16'].indexOf(this.amountLast2());
		/** @type {Boolean} */
		var approve = 'approve' === forceResult;
		/** @type {Boolean} */
		var needAdjust = ('no' !== forceResult) && (approve !== approved);
		/**
		 * @param {Boolean} approved
		 * @returns {String}
		 */
		function label(approved) {return df.t(approved ? 'approved' : 'declined');}
		/** @type {String} */
		var result;
		if (!needAdjust) {
			result = df.t(
				'The transaction will be <b>{result}</b>, because the payment amount (<b>{amount}</b>) in the payment currency (<b>{currency}</b>) ends with «<b>{last2}</b>».'
				,{
					amount: this.amountPD()
					,currency: this.paymentCurrency().name
					,last2: this.amountLast2()
					,result: label(approved)
				}
			);
		}
		else {
			/** @type {Number} */
			var currentA = this.amountP();
			/** @type {Number} */
			var newA = approve ? Math.round(currentA) : currentA + 0.01;
			result = df.t(
				'The payment amount in the payment currency (<b>{currency}</b>) will be adjusted from <b>{current}</b> to <b>{new}</b> for the transaction to be <b>{result}</b>.'
				,{
					currency: this.paymentCurrency().name
					,current: this.amountPD()
					,'new': this.formatAmountForDisplay(newA)
					,result: label(approve)
				}
			);
		}
		return result;
	}),
	/**
	 * 2016-08-25
	 * Which bank card networks does SecurePay support? https://mage2.pro/t/1989
	 * 2017-02-05
	 * The bank card network codes: https://mage2.pro/t/2647
	 * @returns {String[]}
	 */
	getCardTypes: function() {return ['VI', 'MC'];},
	/**
	 * 2016-08-26
	 * «[SecurePay] The test bank card» https://mage2.pro/t/1991
	 * Так как тестовая карта всего одна, то я не стал вводить опцию «prefill»,
	 * ведь всё равно тестировщик не может указать другую карту.
	 * @override
	 * @see Df_Payment/card::initialize()
	 * https://github.com/mage2pro/core/blob/2.4.21/Payment/view/frontend/web/card.js#L77-L110
	 * @returns {Object}
	*/
	initialize: function() {
		this._super();
		if (this.isTest()) {
			this.creditCardNumber('4444333322221111');
			this.prefillWithAFutureData();
			this.creditCardVerificationNumber(123);
		}
		return this;
	},
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
	},
	/**
	 * 2017-03-21
	 * @override
	 * @see Df_Payment/mixin::postParams()
	 * @used-by Df_Payment/mixin::placeOrderInternal()
	 * @returns {Object}
	 */
	postParams: function() {return {
		EPS_CCV: this.creditCardVerificationNumber()
		,EPS_EXPIRYYEAR: this.creditCardExpYear()
		,EPS_CARDNUMBER: this.creditCardNumber()
		,EPS_EXPIRYMONTH: this.creditCardExpMonth()
	};}
});});
