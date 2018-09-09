<?php 
   class Payment extends CI_Controller {
   	public function __construct(){
			parent::__construct();

			$this->load->library('paystack', [
			'secret_key'=>'sk_test_fb7b79275720e84a8322a262aca49ce5e3fffd76', 
			'public_key'=>'pk_test_dd50b8ad80dff1c7ff97f0a43d89a46b035ec3aa']);

			 
			

		}





		public function make_payment() {
			$amount = 10 * 100;
			$email = 'doan.techware@gmail.com';
			$booking_details = '';
// Initiate payment processing
			$response_arr = $this->paystack->init($booking_id.uniqid(), $amount, $email,$booking_details, base_url('Payment/payment_callback'), TRUE);
//	var_dump($response_arr); exit;
			if($response_arr->status){
				redirect($response_arr->data->authorization_url);
			}
		}





			public function payment_callback() {
				$trxref = $this->input->get('trxref', TRUE);
				$ref = $this->input->get('reference', TRUE);
			//Check the reference from the paystack data
				if($trxref === $ref){
					$ver_info = $this->paystack->verifyTransaction($ref);
			//Check the payment status is success
					if($ver_info && ($ver_info->status == TRUE) && ($ver_info->data->status == "success")){
						$amount = $ver_info->data->amount / 100;
						$booking_id = $ver_info->data->metadata->booking_id;
						$transaction = array(
							'transaction_info'   => json_encode($ver_info),
							'payment_status'     => 1
						);
			//var_dump($transaction); var_dump($res); var_dump($rs);
						redirect(base_url("Payment/payment_success"));
					}
				}
				redirect(base_url("Payment/payment_failed"));
			}








		// If payment processed successfully
		public function payment_success() {
			echo "Payment processed successfully";
		}
		// If payment failed
		public function payment_failed() {
			echo "Payment failed";
		}






   } 
?>
