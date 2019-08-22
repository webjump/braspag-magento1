<?php
/**
 * This class can Mage::getModel('cms/template_filter') to filter data in xml
 * before request.
 *
 * To use it before webservice request you should call
 *
 *         $this->client->__setVariables(array(
 *            'order' => $order,
 *        ));
 *
 * So you can use in request variables like {{var order.getIncrementId()}}, {{var order.getBillingAddress().getEmail()}}
 */
class Webjump_WSSoapClientMage extends Webjump_WSSoapClient
{
    protected $__variables;

    public function __setVariables($var)
    {
        $this->__variables = $var;
        return $this;
    }

    public function __getVariables()
    {
        return $this->__variables;
    }

    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $variables = $this->__getVariables();
        if ($variables) {
            /* @var $filter Mage_Cms_Model_Template_Filter */
            $filter = Mage::getModel('cms/template_filter');
            $filter->setVariables($variables);
            $request = $filter->filter($request);
        }

        return parent::__doRequest($request, $location, $action, $version, $one_way);
    }
}
