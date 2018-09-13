<?php
//$arr=$_REQUEST;
$arr=   array (
    'merchantCode' => 'M000TEST',
    'orderNo' => 'PcWx201706041124018280639',
    'amount' => '1',
    'successAmt' => '1',
    'payOrderNo' => 'WP20170604112401641242',
    'orderStatus' => '01',
    'signType' => 'RSA',
    'sign' => 'dU2eH1LDx8bk7BeHRy6mNIcyKAl9qYmybWEYkWPigPDlQMv+8ttNZGGaF37Bj90BABHjBfwp95XUYaSJn3O2jpLomkW8Llj14AUc32l3kkEqaW39fC88eufibnsfV6YjV6vWjbCqguJ9OTjwB7S23WgjwTbLweo/lDTqAN5y+KM=',
);
echo $arr."<br>";
$public_content='MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC0sJMGLD0UQUYObjsMHBGUYQEVEOCkBCNzzkYWSM0RYToK49hLpmxpNLbNcSMSUwOs6AfzDW9Tbpcotjg4JiphZqrBjG4Vj2acQPxBp06oJBYdvoCM42AFFLthHNDTmP+O7OYPrwiTTSYPlIUO8HyojhfQ6Dc9guiit7L98FWhmQIDAQAB';

$public_key=openssl_get_publickey($public_content);
$sign=base64_decode($arr['sign']);
echo $sign."<br>";
$original_str="merchantCode=".$arr['merchantCode']."&orderNo=".$arr['orderNo']."&amount=".$arr['amount']."&successAmt=".$arr['successAmt']."&payOrderNo=".$arr['payOrderNo']."&orderStatus=".$arr['orderStatus'];//Obtained data
echo $original_str."<br>";
$result= openssl_verify($original_str,$sign,$public_key,OPENSSL_ALGO_SHA1);
echo $result."<br>";
if($result){
    echo "Verify success";
}else{
    echo "Verify failure";
}