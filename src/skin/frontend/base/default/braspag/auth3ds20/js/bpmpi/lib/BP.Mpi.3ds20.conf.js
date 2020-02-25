/**
 * @author      Webjump Core Team <dev@webjump.com>
 * @copyright   2019 Webjump (http://www.webjump.com.br)
 * @license     http://www.webjump.com.br  Copyright
 *
 * @link        http://www.webjump.com.br
 */

var BpmpiConf = Class.create();
BpmpiConf.prototype = {

  initialize() {
  },

  onReady: function () {
      // Evento indicando quando a inicialização do script terminou.
  },
  onSuccess: function (e) {
      // Cartão elegível para autenticação, e portador autenticou com sucesso.

      $j('.bpmpi_auth_cavv').val(e.Cavv);
      $j('.bpmpi_auth_xid').val(e.Xid);
      $j('.bpmpi_auth_eci').val(e.Eci);
      $j('.bpmpi_auth_version').val(e.Version);
      $j('.bpmpi_auth_reference_id').val(e.ReferenceId);
      $j('.bpmpi_auth_failure_type').val(0)
          .trigger('change');
  },
  onFailure: function (e) {
      // Cartão elegível para autenticação, porém o portador finalizou com falha.

      $j('.bpmpi_auth_xid').val(e.Xid);
      $j('.bpmpi_auth_eci').val(e.Eci);
      $j('.bpmpi_auth_version').val(e.Version);
      $j('.bpmpi_auth_reference_id').val(e.ReferenceId);
      $j('.bpmpi_auth_failure_type').val(1)
          .trigger('change');
  },
  onUnenrolled: function (e) {
      // Cartão não elegível para autenticação (não autenticável).
      $j('.bpmpi_auth_xid').val(e.Xid);
      $j('.bpmpi_auth_eci').val(e.Eci);
      $j('.bpmpi_auth_version').val(e.Version);
      $j('.bpmpi_auth_reference_id').val(e.ReferenceId);
      $j('.bpmpi_auth_failure_type').val(2)
          .trigger('change');
  },
  onDisabled: function () {
      // Loja não requer autenticação do portador (classe "bpmpi_auth" false -> autenticação desabilitada).
      $j('.bpmpi_auth_failure_type').val(3)
          .trigger('change');
  },
  onError: function (e) {
      // Erro no processo de autenticação.

      $j('.bpmpi_auth_xid').val(e.Xid);
      $j('.bpmpi_auth_eci').val(e.Eci);
      $j('.bpmpi_auth_version').val(e.Version);
      $j('.bpmpi_auth_reference_id').val(e.ReferenceId);
      $j('.bpmpi_auth_failure_type').val(4)
          .trigger('change');
  },
  onUnsupportedBrand: function (e) {
    // Bandeira não suportada para autenticação.
    $j('.bpmpi_auth_xid').val(e.Xid);
    $j('.bpmpi_auth_eci').val(e.Eci);
    $j('.bpmpi_auth_version').val(e.Version);
    $j('.bpmpi_auth_reference_id').val(e.ReferenceId);
    $j('.bpmpi_auth_failure_type').val(5)
      .trigger('change');
  },
  Environment: 'PRD',
  Debug: false
}