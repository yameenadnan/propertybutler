<script src="bower_components/jquery/dist/jquery.min.js"></script>
<?php
if ( !isset($_POST['Payment_ID']) ) {
    $payment_method = 'Select payment method';
    echo $payment_method;
    ?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <select id="Payment_ID" name="Payment_ID">
        <option value="">Select payment method</option>
        <option value="2">Visa MasterCard (Credit/Debit Card Payment)</option>
        <option value="3">FPX (Bank Fund Transfer) – B2C Personal Account</option>
        <option value="4">AMEX CreditCard</option>
        <option value="5">Alipay Spot QR</option>
        <option value="6">UPOP (Union Pay Online Payment)</option>
        <option value="7">UPI QR Code</option>
        <option value="8">Internet Banking</option>
        <option value="9">FPX (Bank Fund Transfer) – B2B Corporate Account</option>
        <option value="10">MerchanTrade</option>
        <option value="11">Axiata Boost</option>
        <option value="12">Maybank QR</option>
        <option value="13">DiGi VCash</option>
        <option value="14">WeChat Pay</option>
        <option value="15">Alipay Online</option>
        <option value="16">AirAsia BIG</option>
        <option value="17">Grab Pay</option>
        <option value="18">Internet Banking (MayBank)</option>
        <option value="19">Internet Banking (CIMB)</option>
        <option value="20">Internet Banking (RHB)</option>
        <option value="21">Internet Banking (Public Bank)</option>
        <option value="22">Internet Banking (Hong Leong Bank)</option>
        <option value="23">Internet Banking (AmBank)</option>
        <option value="24">Internet Banking (Bank Rakyat)</option>
        <option value="25">Internet Banking (Bank Muamalat)</option>
        <option value="26">Internet Banking (BSN)</option>
        <option value="27">Hong Leong Bank – Scan and Pay</option>
        <option value="28">Touch ’n Go</option>
        <option value="29">Diners Club</option>
    </select>
    </form>
    <script>
    $(document).ready(function (){
        $('#Payment_ID').on('change', function() {
            if ( $(this).val() == '')
                return;
            this.form.submit();
        });
    });
    </script>


<?php } elseif ( isset ($_POST['Payment_ID']) && $_POST['Payment_ID'] == 3 && !isset(  $_POST['Bank_Code'] ) ) {
    $bank_code = 'Select bank';
    echo $bank_code;
    ?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" id="Payment_ID" name="Payment_ID" value="3">
    <select id="Bank_Code" name="Bank_Code">
        <option value="">Select bank</option>
        <option value="ABB0233">Affin Bank Berhad</option>
        <option value="ABMB0212">Alliance Bank Malaysia Berhad</option>
        <option value="AMBB0209">AmBank Malaysia Berhad</option>
        <option value="BIMB0340">Bank Islam Malaysia Berhad</option>
        <option value="BKRM0602">Bank Kerjasama Rakyat Malaysia Berhad</option>
        <option value="BMMB0341">Bank Muamalat Malaysia Berhad</option>
        <option value="BSN0601">Bank Simpanan Nasional</option>
        <option value="BCBB0235">CIMB Bank Berhad</option>
        <option value="HLB0224">Hong Leong Bank Berhad</option>
        <option value="HSBC0223">HSBC Bank Malaysia Berhad</option>
        <option value="KFH0346">Kuwait Finance House (Malaysia) Berhad</option>
        <option value="MB2U0227">Malayan Banking Berhad (M2U)</option>
        <option value="MBB0228">Malayan Banking Berhad (M2E)</option>
        <option value="OCBC0229">OCBC Bank Malaysia Berhad</option>
        <option value="PBB0233">Public Bank Berhad</option>
        <option value="RHB0218">RHB Bank Berhad</option>
        <option value="SCB0216">Standard Chartered Bank</option>
        <option value="UOB0226">United Overseas Bank</option>
    </select>
    </form>

    <script>
        $(document).ready(function (){
            $('#Bank_Code').on('change', function() {
                if ( $(this).val() == '')
                    return;
                this.form.submit();
            });
        });
    </script>
<?php } elseif ( isset ($_POST['Payment_ID'])  ) {

    $ip = $_SERVER['REMOTE_ADDR'];
    $signature_original = 'eP5uCoA709' . 'MER00000001099' . 'RF1' . '52.40' . 'MYR';
    $signature = hash('sha512', $signature_original);

    if ( $_POST['Payment_ID'] == 3 ) {
        $bank_code = '<input type="text" name="Bank_Code" value="' . $_POST['Bank_Code'] . '">';
    } else {
        $bank_code = '';
    }
?>

<form method="post" action="https://devgateway.revpay.com.my/payment" accept-charset="UTF-8">
    <!--Revpay Merchant ID Provided by Revpay-->
    <input type="text" name="Revpay_Merchant_ID" value="MER00000001099">
    <!--our internal database id-->
    <input type="text" name="Reference_Number" value="RF1">
    <!--order total-->
    <input type="text" name="Amount" value="52.40">
    <!--Merchan key index-->
    <input type="text" name="Key_Index" value="1">
    <!--Currency-->
    <input type="text" name="Currency" value="MYR">
    <!--Signature Value = "Merchant Key" + "Revpay Merchant ID" + "Reference Number" + "Amount" + "Currency"-->
    <input type="text" name="Signature" value="<?php echo $signature;?>">
    <!--    Transaction description -->
    <input type="text" name="Transaction_Description" value="Order Number : Thomas Buy Power Bank">
    <!--    Return URL -->
    <input type="text" name="Return_URL" value="https://propertybutler.my/order_confirmation.php">
    <input type="text" name="Customer_IP" value="<?php echo $ip;?>">
    <input type="text" name="Customer_Name" value="John Doe">
    <input type="text" name="Customer_Email" value="johndoe@bigcommerce.com">
    <input type="text" name="Customer_Contact" value="0123456789">
    <input type="text" name="Payment_ID" value="<?php echo $_POST['Payment_ID']; ?>">
    <?php echo $bank_code; ?>
    <input value="Submit" type="submit">
</form>

<?php } ?>
