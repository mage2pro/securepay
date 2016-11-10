// 2016-08-25
define ([
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
					amount: this.amountPF()
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
					,current: this.amountPF()
					,'new': this.formatP(newA)
					,result: label(approve)
				}
			);
		}
		return result;
	}),
	/**
	 * 2016-08-25
	 * https://mage2.pro/t/1989
	 * @returns {String[]}
	 */
	getCardTypes: function() {return ['VI', 'MC'];},
	/**
	 * 2016-08-26
	 * @return {Object}
	*/
	initialize: function() {
		this._super();
		// 2016-08-26
		// https://mage2.pro/t/1991
		/**
		 * 2016-08-26
		 * «[SecurePay] The test bank card» https://mage2.pro/t/1991
		 * Так как тестовая карта всего одна, то я не стал вводить опцию «prefill»,
		 * ведь всё равно тестировщик не может указать другую карту.
		 */
		if (this.isTest()) {
			this.creditCardNumber('4444333322221111');
			this.prefillWithAFutureData();
			this.creditCardVerificationNumber(123);
		}
		return this;
	},
	/**
	 * 2016-08-26
	 * @override
	 * @see mage2pro/core/Payment/view/frontend/web/mixin.js
	 * @used-by placeOrderInternal()
	 */
	onSuccess: function(json) {
		/** @type {Object} */
		var data = $.parseJSON(json);
		// @see \Dfe\SecurePay\Method::getConfigPaymentAction()
		redirectWithPost(data.uri, df.o.merge(data.params, {
			EPS_CCV: this.dfCardVerification()
			,EPS_EXPIRYYEAR: this.dfCardExpirationYear()
			,EPS_CARDNUMBER: this.dfCardNumber()
			,EPS_EXPIRYMONTH: this.dfCardExpirationMonth()
		}));
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
	}
});});
