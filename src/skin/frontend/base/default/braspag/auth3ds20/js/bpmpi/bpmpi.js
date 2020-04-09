/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2019 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

var Bpmpi = Class.create();
Bpmpi.prototype = {

  initialize: function() {
    this.bpmpiLib = new BpmpiLib();
    this.bpmpiRenderer = new BpmpiRenderer();
    this.bpmpiToken = '';
    this.isBpmpiEnabledCC = false;
    this.isBpmpiEnabledDC = false;
    this.isBpmpiMasterCardNotifyOnlyEnabledCC = false;
    this.isBpmpiMasterCardNotifyOnlyEnabledDC = false;
    this.isTestEnvironment = false;
    this.paymentType = '';
    this.saveOrderTriggered = false;
  },

  startTransaction: function(){
    var self = this;

    if (this.isBpmpiEnabled()) {
      
      bpmpi.getTokenData()
        .then(function(token){

          self.bpmpiToken = token;
          self.bpmpiRenderer.renderBpmpiData('bpmpi_auth', false, self.isBpmpiEnabled());
          self.bpmpiRenderer.renderBpmpiData('bpmpi_accesstoken', false, self.bpmpiToken);

          self.getAuthLoadData();

        }).catch(function(err){

          console.log(err);
          self.disableBpmpi();
      });
    }

    return;
  },

  getTokenData: function (){
    var self = this;

    if (!this.isBpmpiEnabled()) {
      return false;
    }

    return new Promise(function (resolve, reject){
      new Ajax.Request('/braspag-auth3ds20/mpi/auth',
        {
          method:'get',
          onSuccess: function(data) {

            resolve(data.responseJSON.token);

          },
          onFailure: function() {
            reject(new Error("Bpmpi getToken error, 'Bpmpi' will be disabled."));
          }
        })
    });
  },

  getAuthLoadData: function () {
    var self = this;

    new Ajax.Request('/braspag-auth3ds20/mpi/loadData',
      {
        method:'get',
        onSuccess: function(data) {

          var response = data.responseJSON;

          self.bpmpiRenderer.renderBpmpiData('bpmpi_totalamount', false, response.cart_total_amount);
          self.bpmpiRenderer.renderBpmpiData('bpmpi_currency', false, response.cart_currency);
          self.bpmpiRenderer.renderBpmpiData('bpmpi_ordernumber', false, response.cart_order_number);

          self.bpmpiLib.bpmpi_load();
        },
        onFailure: function() {
          console.log("Bpmpi get LoadData error, 'Bpmpi' will be disabled.");
          self.disableBpmpi();
        }
      });

    return;
  },

  getAuthenticateData: function () {
    self = this;

    return new Promise(function(resolve, reject){
      self.bpmpiLib.bpmpi_authentication()
        .then(function(){

          var returnData = {
            'bpmpiAuthFailureType' : $j('.bpmpi_auth_failure_type').val(),
            'bpmpiAuthCavv' : $j('.bpmpi_auth_cavv').val(),
            'bpmpiAuthVersion' : $j('.bpmpi_auth_version').val(),
            'bpmpiAuthXid' : $j('.bpmpi_auth_xid').val(),
            'bpmpiAuthEci' : $j('.bpmpi_auth_eci').val(),
            'bpmpiAuthVersion' : $j('.bpmpi_auth_version').val(),
            'bpmpiAuthReferenceId' : $j('.bpmpi_auth_reference_id').val()
          };

          console.log(returnData);

          resolve(returnData);
        }).catch(function (err) {
          reject(err);
        })
    });
  },

  isBpmpiEnabled: function() {
    return this.isBpmpiEnabledCC || this.isBpmpiEnabledDC;
  },

  disableBpmpi: function() {
    this.isBpmpiEnabledCC = false;
    this.isBpmpiEnabledDC = false;

    if (this.isTestEnvironment) {
      console.log("'Bpmpi' disabled.");
    }

    return;
  },

  placeOrder: function () {

    var self = this;

    $j('.bpmpi_auth_failure_type').change(function () {

      if (self.isBpmpiEnabled()) {

        if (self.paymentType == 'creditcard') {

          var paymentForm = $j("#" + payment.form + ' #braspag_creditcard_creditcard_type_div');
        } else if (self.paymentType == 'debitcard') {

          var paymentForm = $j("#" + payment.form + ' #braspag_debitcard_debitcard_type_div');
        } else {

          checkout.setLoadWaiting(false);
          review.save();
        }

        self.bpmpiRenderer.createInputHiddenElement(
          paymentForm, 'payment[payment_request][authentication_failure_type]', 'authentication_failure_type', ''
        );

        self.bpmpiRenderer.createInputHiddenElement(
          paymentForm, 'payment[payment_request][authentication_cavv]', 'authentication_cavv', ''
        );

        self.bpmpiRenderer.createInputHiddenElement(
          paymentForm, 'payment[payment_request][authentication_xid]', 'authentication_xid', ''
        );

        self.bpmpiRenderer.createInputHiddenElement(
          paymentForm, 'payment[payment_request][authentication_eci]', 'authentication_eci', ''
        );

        self.bpmpiRenderer.createInputHiddenElement(
          paymentForm, 'payment[payment_request][authentication_version]', 'authentication_version', ''
        );

        self.bpmpiRenderer.createInputHiddenElement(
          paymentForm, 'payment[payment_request][authentication_reference_id]', 'authentication_reference_id', ''
        );

        $j('.authentication_failure_type').val($j('.bpmpi_auth_failure_type').val());
        $j('.authentication_cavv').val($j('.bpmpi_auth_cavv').val());
        $j('.authentication_xid').val($j('.bpmpi_auth_xid').val());
        $j('.authentication_eci').val($j('.bpmpi_auth_eci').val());
        $j('.authentication_version').val($j('.bpmpi_auth_version').val());
        $j('.authentication_reference_id').val($j('.bpmpi_auth_reference_id').val());

        checkout.setLoadWaiting(false);
      }

      review.save();

    });

    var paymentMethod = $j('input[name="payment[method]"]:checked').val();

    if (paymentMethod == 'braspag_creditcard') {
      this.paymentType = 'creditcard';

    } else if (paymentMethod == 'braspag_debitcard') {
      this.paymentType = 'debitcard';

    } else {
      this.disableBpmpi();
      review.save();
      return;
    }

    if (self.saveOrderTriggered) {
      review.save();
      return;
    }

    self.saveOrderTriggered = true;

    checkout.setLoadWaiting('review');

    self.renderData()
      .then(function () {
        self.getAuthenticateData()
          .then(function(){

          }).catch(function (err) {

            self.disableBpmpi();

            if (self.isTestEnvironment) {
              console.log(err);
            }
            review.save();
          })
      }).catch(function (err) {

        self.disableBpmpi();

        if (self.isTestEnvironment) {
          console.log(err);
        }

        review.save();
      });
  },

  validateAuthenticate: function() {
    var self = this;

    if (self.paymentType == 'creditcard'){
      var providerCC = $j('#braspag_creditcard_creditcard_type').val();

      if (!this.isBpmpiEnabledCC) {
        return false;
      }

      if (providerCC.indexOf("Cielo") < 0 && !this.isTestEnvironment){
        return false;
      }
    }

    if (self.paymentType == 'debitcard') {
      var providerDC = $j('#braspag_debitcard_debitcard_type').val();

      if (!this.isBpmpiEnabledDC) {
          return false;
      }

      if (providerDC.indexOf("Cielo") < 0 && !this.isTestEnvironment){
        return false;
      }
    }

    return true;
  },

  renderData: function () {
    var self = this;

    let mpiValidation = this.validateAuthenticate();

    if (!mpiValidation) {
      self.disableBpmpi();
      checkout.setLoadWaiting(false);
      review.save();
    }

    this.bpmpiRenderer.renderBpmpiData('bpmpi_auth', false, mpiValidation);
    this.bpmpiRenderer.renderBpmpiData('bpmpi_transaction_mode', false, 'S');

    if (self.paymentType == 'creditcard') {
      this.renderCredicardData();
    }
    if (self.paymentType == 'debitcard') {
      this.renderDebitcardData();
    }

    return new Promise(function(resolve, reject){
      new Ajax.Request('/braspag-auth3ds20/mpi/authenticateData',
        {
          method:'get',
          params: { payment_type: self.paymentType},
          onSuccess: function(data) {

            var response = data.responseJSON;

            self.bpmpiRenderer.renderBpmpiData('bpmpi_merchant_url', false, response.bpmpi_merchant_url);
            self.bpmpiRenderer.renderBpmpiData('bpmpi_device_ipaddress', false, response.bpmpi_device_ipaddress);

            self.renderAddressData(response.addresses);
            self.renderCartData(response.cart);
            self.renderUserAccountData(response.user_account);
            self.renderMddData(response.mdd);

            resolve(true);
          },
          onFailure: function() {
            self.disableBpmpi();
            reject(false);
          }
        });
    });

    return;
  },

  renderCredicardData: function() {

    this.bpmpiRenderer.renderBpmpiData('bpmpi_paymentmethod', '', 'Credit');
    this.bpmpiRenderer.renderBpmpiData('bpmpi_auth_notifyonly', false, this.isBpmpiMasterCardNotifyOnlyEnabledCC);

    this.bpmpiRenderer.renderBpmpiData('bpmpi_cardnumber', false, $j('#braspag_creditcard_creditcard_number').val());
    this.bpmpiRenderer.renderBpmpiData('bpmpi_billto_contactname', false, $j('#braspag_creditcard_creditcard_owner').val());
    this.bpmpiRenderer.renderBpmpiData('bpmpi_cardexpirationmonth', false, $j('#braspag_creditcard_expiration').val());
    this.bpmpiRenderer.renderBpmpiData('bpmpi_cardexpirationyear', false, $j('#braspag_creditcard_expiration_yr').val());
    this.bpmpiRenderer.renderBpmpiData('bpmpi_installments', false, $j('#braspag_creditcard_installments').val());
  },

  renderDebitcardData: function() {

    this.bpmpiRenderer.renderBpmpiData('bpmpi_paymentmethod', '', 'Debit');
    this.bpmpiRenderer.renderBpmpiData('bpmpi_auth_notifyonly', false, this.isBpmpiMasterCardNotifyOnlyEnabledDC);

    this.bpmpiRenderer.renderBpmpiData('bpmpi_cardnumber', false, $j('#braspag_debitcard_debitcard_number').val());
    this.bpmpiRenderer.renderBpmpiData('bpmpi_billto_contactname', false, $j('#braspag_debitcard_debitcard_owner').val());
    this.bpmpiRenderer.renderBpmpiData('bpmpi_cardexpirationmonth', false, $j('#braspag_debitcard_expiration').val());
    this.bpmpiRenderer.renderBpmpiData('bpmpi_cardexpirationyear', false, $j('#braspag_debitcard_expiration_yr').val());
    this.bpmpiRenderer.renderBpmpiData('bpmpi_installments', false, 1);
  },
  
  renderAddressData: function (addresses) {

    var billingAddress = addresses.billingAddress;
    var shippingAddress = addresses.shippingAddress;

    if (billingAddress != '') {

      this.bpmpiRenderer.renderBpmpiData('bpmpi_billto_phonenumber', false, billingAddress.bpmpi_billto_phonenumber);
      this.bpmpiRenderer.renderBpmpiData('bpmpi_billto_customerid', false, billingAddress.bpmpi_billto_customerid);
      this.bpmpiRenderer.renderBpmpiData('bpmpi_billto_email', false, billingAddress.bpmpi_billto_email);
      this.bpmpiRenderer.renderBpmpiData('bpmpi_billto_street1', false, billingAddress.bpmpi_billto_street1);
      this.bpmpiRenderer.renderBpmpiData('bpmpi_billto_street2', false, billingAddress.bpmpi_billto_street2);
      this.bpmpiRenderer.renderBpmpiData('bpmpi_billto_city', false, billingAddress.bpmpi_billto_city);
      this.bpmpiRenderer.renderBpmpiData('bpmpi_billto_state', false, billingAddress.bpmpi_billto_state);
      this.bpmpiRenderer.renderBpmpiData('bpmpi_billto_zipcode', false, billingAddress.bpmpi_billto_zipcode
        .replace(/[^a-zA-Z 0-9]+/g, ''));
      this.bpmpiRenderer.renderBpmpiData('bpmpi_billto_country', false, billingAddress.bpmpi_billto_country);
    }

    if (shippingAddress != '') {
      this.bpmpiRenderer.renderBpmpiData('bpmpi_shipto_sameasbillto', false, shippingAddress.bpmpi_shipto_sameasbillto);
      this.bpmpiRenderer.renderBpmpiData('bpmpi_shipto_addressee', false, shippingAddress.bpmpi_shipto_addressee);
      this.bpmpiRenderer.renderBpmpiData('bpmpi_shipto_phonenumber', false, shippingAddress.bpmpi_shipto_phonenumber);
      this.bpmpiRenderer.renderBpmpiData('bpmpi_shipto_email', false, shippingAddress.bpmpi_shipto_email);
      this.bpmpiRenderer.renderBpmpiData('bpmpi_shipto_street1', false, shippingAddress.bpmpi_shipto_street1);
      this.bpmpiRenderer.renderBpmpiData('bpmpi_shipto_street2', false, shippingAddress.bpmpi_shipto_street2);
      this.bpmpiRenderer.renderBpmpiData('bpmpi_shipto_city', false, shippingAddress.bpmpi_shipto_city);
      this.bpmpiRenderer.renderBpmpiData('bpmpi_shipto_state', false, shippingAddress.bpmpi_shipto_state);
      this.bpmpiRenderer.renderBpmpiData('bpmpi_shipto_zipcode', false, shippingAddress.bpmpi_shipto_zipcode
        .replace(/[^a-zA-Z 0-9]+/g, ''));
      this.bpmpiRenderer.renderBpmpiData('bpmpi_shipto_country', false, shippingAddress.bpmpi_shipto_country);
    }
  },

  renderCartData: function (cart) {
    var self = this;
    var bpmpiDataCartItems = $j('#bpmpi_data_cart');

    $j.each(cart.items, function (k, i) {
      k++;
      self.bpmpiRenderer
        .createInputHiddenElement(bpmpiDataCartItems, '', 'bpmpi_cart_' + k + '_description', i.bpmpi_cart_description);
      self.bpmpiRenderer
        .createInputHiddenElement(bpmpiDataCartItems, '', 'bpmpi_cart_' + k + '_name', i.bpmpi_cart_name);
      self.bpmpiRenderer
        .createInputHiddenElement(bpmpiDataCartItems, '', 'bpmpi_cart_' + k + '_sku', i.bpmpi_cart_sku);
      self.bpmpiRenderer
        .createInputHiddenElement(bpmpiDataCartItems, '', 'bpmpi_cart_' + k + '_quantity', i.bpmpi_cart_quantity);
      self.bpmpiRenderer
        .createInputHiddenElement(bpmpiDataCartItems, '', 'bpmpi_cart_' + k + '_unitprice', i.bpmpi_cart_unitprice);
    });
  },

  renderUserAccountData: function (userAccount) {
    this.bpmpiRenderer
      .renderBpmpiData('bpmpi_useraccount_guest', false, userAccount.bpmpi_useraccount_guest);
    this.bpmpiRenderer
      .renderBpmpiData('bpmpi_useraccount_createddate', false, userAccount.bpmpi_useraccount_createddate);
    this.bpmpiRenderer
      .renderBpmpiData('bpmpi_useraccount_changeddate', false, userAccount.bpmpi_useraccount_changeddate);
    this.bpmpiRenderer
      .renderBpmpiData('bpmpi_useraccount_authenticationmethod', false, userAccount.bpmpi_useraccount_authenticationmethod);
    this.bpmpiRenderer
      .renderBpmpiData('bpmpi_useraccount_authenticationprotocol', false, userAccount.bpmpi_useraccount_authenticationprotocol);
  },

  renderMddData: function (mdd) {
    this.bpmpiRenderer.renderBpmpiData('bpmpi_mdd1', false, mdd.bpmpi_mdd1);
    this.bpmpiRenderer.renderBpmpiData('bpmpi_mdd2', false, mdd.bpmpi_mdd2);
    this.bpmpiRenderer.renderBpmpiData('bpmpi_mdd3', false, mdd.bpmpi_mdd3);
    this.bpmpiRenderer.renderBpmpiData('bpmpi_mdd4', false, mdd.bpmpi_mdd4);
    this.bpmpiRenderer.renderBpmpiData('bpmpi_mdd5', false, mdd.bpmpi_mdd5);
  }
}