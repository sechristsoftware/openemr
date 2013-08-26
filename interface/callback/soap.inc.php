<?php

$wsdl = "http://callfire.com/api/1.1/wsdl/callfire-service-http-soap12.wsdl";
$client = new SoapClient($wsdl, array(
    'soap_version' => SOAP_1_2,
    'login'        => $Username ,    
    'password'     => $Password ));



?>