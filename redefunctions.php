<?php

require_once('erede-sdk-php/erede/Classloader.php');

use erede\model\EnvironmentType;
use erede\model\RefundRequest;
use erede\model\TransactionRequest;
//use erede\model\TransactionKind;
//use erede\model\IataRequest;
//use erede\model\ThreeDSecureRequest;
//use erede\model\UrlRequest;
//use erede\model\UrlKind;
//use erede\model\AvsRequest;
//use erede\model\AddressRequest;
//use erede\model\ThreeDSecureOnFailure;

$aleatorio = rand(1,9999999999999999);

function authorization($amount,$reference,$cardnumber,$securitycode,$month,$year,$holdername,$installments){

	$acquirer = new Acquirer("10000833", "3fea60e051b447819bae3dc41e5a0243", EnvironmentType::HOMOLOG);
	
	//Autorização de crédito
	$request = new TransactionRequest();
	$request->setCapture("false");
	$request->setAmount($amount);
	$request->setReference($reference);
	$request->setCardNumber($cardnumber);
	$request->setSecurityCode($securitycode);
	$request->setExpirationMonth($month);
	$request->setExpirationYear($year);
	$request->setCardHolderName($holdername);
	$request->setInstallments($installments);

	$response = $acquirer->authorize($request);

	//print_r($response);

	$r = new ReflectionObject($response);
	$p = $r->getProperty('tid');
	$p->setAccessible(true); // <--- you set the property to public before you read the value

	$tid = $p->getValue($response);

	//echo $tid . "<br><br>";

	if($tid==""){
	
		return "error";
	
	}
	else{
	
		return $tid;
	
	}

}

function capture($tid,$amount){
	
	$acquirer = new Acquirer("10000833", "3fea60e051b447819bae3dc41e5a0243", EnvironmentType::HOMOLOG);
	
	//Captura
	//$tid = "10987654321";
	$request = new TransactionRequest();
	$request->setAmount(900);

	$response = $acquirer->capture($tid, $request);

	print_r($response);

}

function cancel($tid, $amount){
	
	$acquirer = new Acquirer("10000833", "3fea60e051b447819bae3dc41e5a0243", EnvironmentType::HOMOLOG);
	
	//Cancelamento
	//$tid = "10987654321";
	$request = new RefundRequest();
	$request->setAmount(900);

	$response = $acquirer->refund($tid, $request);

	print_r($response);

}

function consult($tid){
	
	$acquirer = new Acquirer("10000833", "3fea60e051b447819bae3dc41e5a0243", EnvironmentType::HOMOLOG);
	
	//Consulta
	//$tid = "10987654321";
	$query = new Query("10000833", "3fea60e051b447819bae3dc41e5a0243", EnvironmentType::HOMOLOG);
	
	$response = $query->getTransactionByTid($tid);

	print_r($response);
}

$tid = authorization(1000,$aleatorio,5448280000000007,123,1,20,"NOME PORTADOR",1);
echo $tid."<br><br>";
capture($tid,1000);
echo "<br><br>";
cancel($tid, 1000);
echo "<br><br>";
consult($tid);
?>