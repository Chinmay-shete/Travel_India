<?php
include_once("../../config/connection.php");

$apikey = getenv('RAZORPAY_KEY_ID') ?: "rzp_test_Pl81xvWKLN0yIB";
$key_secret = getenv('RAZORPAY_KEY_SECRET') ?: "";

$Total_Price = $_GET['price'];

use Razorpay\Api\Api;
$orderId = "";

if (!empty($key_secret)) {
    try {
        $api = new Api($apikey, $key_secret);
        $order = $api->order->create([
            'receipt'  => 'order_' . uniqid(),
            'amount'   => (int)($Total_Price * 100), // Amount in paise
            'currency' => 'INR'
        ]);
        $orderId = $order['id'];
    } catch (Exception $e) {
        error_log("Razorpay Order Creation Error: " . $e->getMessage());
    }
}
?>

<script src="https://code.jquery.com/jquery-3.5.0.js"></script>

<form action="pending.php?price=<?php echo $Total_Price; ?>" method="post">
   <?php echo csrf_field(); ?>
   <script
      src="https://checkout.razorpay.com/v1/checkout.js"
      data-key="<?php echo $apikey; ?>"
      data-amount="<?php echo $Total_Price * 100  ?>"
      data-currency="INR"
      data-order_id="<?php echo $orderId; ?>"
      data-header="<h1> Hello harsh..!</h1>"
      data-buttontext="Pay with Razorpay"
      data-name="The Real-Travel.com"
      data-description=" This is the demo payment..!"
      data-image="https://traidev.com/img/web-design-development.png"
      data-prefill.name="The Real-Travel.com"
      data-prefill.email="<?php echo $_SESSION['email'] ?? ''; ?>"
      data-theme.color="blue"></script>
</form>
<style>
   .razorpay-payment-button {
      display: none;
   }
</style>

<script type="text/javascript">
   $(document).ready(function() {
      $('.razorpay-payment-button').click();
   });
</script>
