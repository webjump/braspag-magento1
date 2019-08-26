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
    this.bpmpiConf = new BpmpiConf();
    this.bpmpiLib = new BpmpiLib();
    this.bpmpiRenderer = new BpmpiRenderer();
    this.bpmpiToken = '';
    this.isTestEnvironment = true;
    this.isBpmpiEnabledCC = false;
    this.isBpmpiEnabledDC = false;
    this.isBpmpiMasterCardNotifyOnlyEnabledCC = false;
    this.isBpmpiMasterCardNotifyOnlyEnabledDC = false;
    this.mddsCC = {};
    this.mddsDC = {};
    this.cartProducts = {};
    this.userAccount = {};
    this.device = {};
  },

  startTransaction: function(){
    var self = this;

    if (this.isBpmpiEnabled()) {
      bpmpi.getBpmpiAuthToken()
        .then(function(token){

          self.bpmpiToken = token;
          self.bpmpiRenderer.renderBpmpiData('bpmpi_auth', false, self.isBpmpiEnabled());
          self.bpmpiRenderer.renderBpmpiData('bpmpi_accesstoken', false, self.bpmpiToken);
          self.bpmpiRenderer.renderBpmpiData('bpmpi_auth_notifyonly', false, self.isBpmpiMasterCardNotifyOnlyEnabled());

          self.bpmpiAuthLoad();

        }).catch(function(err){

          console.log(err);
          self.disableBpmpi();
      });
    }

    return;
  },

  getBpmpiAuthToken: function (){
    var self = this;

    if (!this.isBpmpiEnabled()) {
      self.bpmpiAuthLoad();
      return false;
    }

    return new Promise(function (resolve, reject){
      new Ajax.Request('/braspag_pagador/mpi/auth',
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


  bpmpiAuthLoad: function () {
    var self = this;

    new Ajax.Request('/braspag_pagador/mpi/loadData',
      {
        method:'get',
        onSuccess: function(data) {

          var response = data.responseJSON;

          self.bpmpiRenderer.renderBpmpiData('bpmpi_totalamount', false, response.cart_total_amount);
          self.bpmpiRenderer.renderBpmpiData('bpmpi_currency', false, response.cart_currency);
          self.bpmpiRenderer.renderBpmpiData('bpmpi_ordernumber', false, response.cart_order_number);

          // self.processBpmpiData();
          self.bpmpiLib.bpmpi_load();
        },
        onFailure: function() {
          console.log("Bpmpi get LoadData error, 'Bpmpi' will be disabled.");
          self.disableBpmpi();
        }
      });

    return;
  },

  bpmpiAuthenticate: function () {
    self = this;

    return new Promise(function(resolve, reject){
      authentication3ds20lib.bpmpi_authentication()
        .then(function(){

          var returnData = {
            'bpmpiAuthFailureType' : jQuery('.bpmpi_auth_failure_type').val(),
            'bpmpiAuthCavv' : jQuery('.bpmpi_auth_cavv').val(),
            'bpmpiAuthVersion' : jQuery('.bpmpi_auth_version').val(),
            'bpmpiAuthXid' : jQuery('.bpmpi_auth_xid').val(),
            'bpmpiAuthEci' : jQuery('.bpmpi_auth_eci').val(),
            'bpmpiAuthVersion' : jQuery('.bpmpi_auth_version').val(),
            'bpmpiAuthReferenceId' : jQuery('.bpmpi_auth_reference_id').val()
          };

          resolve(returnData);
        })
    });
  },

  processBpmpiData: function () {
    var self = this;

    this.bpmpiRenderer.renderBpmpiData('bpmpi_totalamount', false, (quote.totals().grand_total * 100));
    this.bpmpiRenderer.renderBpmpiData('bpmpi_currency', false, quote.totals().quote_currency_code);
    this.bpmpiRenderer.renderBpmpiData('bpmpi_ordernumber', false, quote.getQuoteId());
    this.bpmpiRenderer.renderBpmpiData('bpmpi_transaction_mode', false, 'S');
    this.bpmpiRenderer.renderBpmpiData('bpmpi_merchant_url', false, mageUrl.build('/'));

    var billAddressId = null;
    jQuery.each(window.checkoutConfig.customerData.addresses, function (k, i) {

      if (i.default_billing) {

        this.bpmpiRenderer.renderBpmpiData('bpmpi_billto_phonenumber', false, i.telephone);
        this.bpmpiRenderer.renderBpmpiData('bpmpi_billto_customerid', false, window.checkoutConfig.customerData.taxvat);
        this.bpmpiRenderer.renderBpmpiData('bpmpi_billto_email', false, window.checkoutConfig.customerData.email);
        this.bpmpiRenderer.renderBpmpiData('bpmpi_billto_street1', false, i.street[0] + ", " + i.street[1]);
        this.bpmpiRenderer.renderBpmpiData('bpmpi_billto_street2', false, i.street[2]);
        this.bpmpiRenderer.renderBpmpiData('bpmpi_billto_city', false, i.city);
        this.bpmpiRenderer.renderBpmpiData('bpmpi_billto_state', false, i.region.region_code);
        this.bpmpiRenderer.renderBpmpiData('bpmpi_billto_zipcode', false, i.postcode.replace(/[^a-zA-Z 0-9]+/g, ''));
        this.bpmpiRenderer.renderBpmpiData('bpmpi_billto_country', false, i.country_id);
        billAddressId = i.id;
      }

      if (i.default_shipping) {
        this.bpmpiRenderer.renderBpmpiData('bpmpi_shipto_sameasbillto', false, (billAddressId == i.id ? true : false));
        this.bpmpiRenderer.renderBpmpiData('bpmpi_shipto_addressee', false, i.firstname);
        this.bpmpiRenderer.renderBpmpiData('bpmpi_shipto_phonenumber', false, i.telephone);
        this.bpmpiRenderer.renderBpmpiData('bpmpi_shipto_email', false, window.checkoutConfig.customerData.email);
        this.bpmpiRenderer.renderBpmpiData('bpmpi_shipto_street1', false, i.street[0] + ", " + i.street[1]);
        this.bpmpiRenderer.renderBpmpiData('bpmpi_shipto_street2', false, i.street[2]);
        this.bpmpiRenderer.renderBpmpiData('bpmpi_shipto_city', false, i.city);
        this.bpmpiRenderer.renderBpmpiData('bpmpi_shipto_state', false, i.region.region_code);
        this.bpmpiRenderer.renderBpmpiData('bpmpi_shipto_zipcode', false, i.postcode);
        this.bpmpiRenderer.renderBpmpiData('bpmpi_shipto_country', false, i.country_id);
      }
    })

    var bpmpiDataCartItems = jQuery('#bpmpi_data_cart');

    jQuery.each(window.checkoutConfig.quoteItemData, function (k, i) {
      this.bpmpiRenderer.createInputHiddenElement(bpmpiDataCartItems, 'bpmpi_cart_description[]', 'bpmpi_cart_' + k + '_description', i.description);
      this.bpmpiRenderer.createInputHiddenElement(bpmpiDataCartItems, 'bpmpi_cart_name[]', 'bpmpi_cart_' + k + '_name', i.name);
      this.bpmpiRenderer.createInputHiddenElement(bpmpiDataCartItems, 'bpmpi_cart_sku[]', 'bpmpi_cart_' + k + '_sku', i.sku);
      this.bpmpiRenderer.createInputHiddenElement(bpmpiDataCartItems, 'bpmpi_cart_quantity[]', 'bpmpi_cart_' + k + '_quantity', i.qty);
      this.bpmpiRenderer.createInputHiddenElement(bpmpiDataCartItems, 'bpmpi_cart_unitprice[]', 'bpmpi_cart_' + k + '_unitprice', i.price * 100);
    });

    this.bpmpiRenderer.renderBpmpiData('bpmpi_useraccount_guest', false, !window.checkoutConfig.isCustomerLoggedIn);
    this.bpmpiRenderer.renderBpmpiData('bpmpi_useraccount_createddate', false, window.checkoutConfig.customerData.created_at);
    this.bpmpiRenderer.renderBpmpiData('bpmpi_useraccount_changeddate', false, window.checkoutConfig.customerData.updated_at);
    this.bpmpiRenderer.renderBpmpiData('bpmpi_useraccount_authenticationmethod', false, 2);
    this.bpmpiRenderer.renderBpmpiData('bpmpi_useraccount_authenticationprotocol', false, 'HTTP');

    this.bpmpiRenderer.renderBpmpiData('bpmpi_device_ipaddress', false, window.checkoutConfig.quoteData.remote_ip);



    var button = jQuery('.btn-checkout').down('button');
    button.writeAttribute('onclick', '');
    button.stopObserving('click');
    button.observe('click', function() {
      if ($(this.iframeId)) {
        if (this.validate()) {
          this.saveOnepageOrder();
        }
      } else {
        review.save();
      }
    }.bind(this));

    return;
  },

  isBpmpiEnabled: function() {
    return this.isBpmpiEnabledCC || this.isBpmpiEnabledDC;
  },

  isBpmpiMasterCardNotifyOnlyEnabled: function() {
    return this.isBpmpiMasterCardNotifyOnlyEnabledCC || this.isBpmpiMasterCardNotifyOnlyEnabledDC;
  },

  disableBpmpi: function() {
    this.isBpmpiEnabledCC = false;
    this.isBpmpiEnabledDC = false;

    return;
  },
}
