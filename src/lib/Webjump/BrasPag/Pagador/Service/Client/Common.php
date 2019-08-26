<?php
//
//class Webjump_BrasPag_Pagador_Service_Client_Common extends SoapClient
//{
//    /**
//     * doRequest() pre-processing method
//     *
//     * @var callable
//     */
//    protected $doRequestCallback;
//
//    /**
//     * Common Soap Client constructor
//     *
//     * @param callable $doRequestCallback
//     * @param string $wsdl
//     * @param array $options
//     */
//    public function __construct($doRequestCallback, $wsdl, $options)
//    {
//        $this->doRequestCallback = $doRequestCallback;
//        parent::__construct($wsdl, $options);
//    }
//
//    /**
//     * Performs SOAP request over HTTP.
//     * Overridden to implement different transport layers, perform additional
//     * XML processing or other purpose.
//     *
//     * @param  string $request
//     * @param  string $location
//     * @param  string $action
//     * @param  int    $version
//     * @param  int    $oneWay
//     * @return mixed
//     */
//    public function __doRequest($request, $location, $action, $version, $oneWay = null)
//    {
//        // ltrim is a workaround for https://bugs.php.net/bug.php?id=63780
//        if ($oneWay === null) {
//            return call_user_func($this->doRequestCallback, $this, ltrim($request), $location, $action, $version);
//        }
//
//        return call_user_func($this->doRequestCallback, $this, ltrim($request), $location, $action, $version, $oneWay);
//    }
//}