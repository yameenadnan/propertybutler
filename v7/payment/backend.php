<?php
// To received parameter from revPAY by use POST method
$merchant_id = $_POST['Revpay_Merchant_ID'];
$payment_id = $_POST['Payment_ID'];
$bank_code = $_POST['Bank_Code'];
$transaction_id = $_POST['Transaction_ID'];
$reference_number = $_POST['Reference_Number'];
$amount = $_POST['Amount'];
$currency = $_POST['Currency'];
$transaction_description = $_POST['Transaction_Description'];
$response_code = $_POST['Response_Code'];
$error_description = $_POST['Error_Description'];
$settlement_amount = $_POST['Settlement_Amount'];
$settlement_currency = $_POST['Settlement_Currency'];
$settlement_fx_rate = $_POST['Settlement_FX_Rate'];
$key_index = $_POST['Key_Index'];
$signature = $_POST['Signature'];
$request_datetime = $_POST['Request_Datetime'];
$response_datetime = $_POST['Response_Datetime'];
// Validation the response parameter from revPAY
if (
    !empty($merchant_id) &&
    !empty($payment_id) &&
    !empty($transaction_id) &&
    !empty($reference_number) &&
    !empty($amount) &&
    !empty($currency) &&
    !empty($transaction_description) &&
    !empty($response_code) &&
    !empty($key_index) &&
    !empty($signature) &&
    !empty($request_datetime) &&
    !empty($response_datetime)) {
// TODO <Add your programming code here>
// Return string OK if no missing parameter value found.
    echo 'OK';
} else {
// If found missing parameter, will not return to revPAY
    echo'Failed';
}
?>