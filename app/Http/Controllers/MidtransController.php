<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Shipping;
use App\User;
use Illuminate\Support\Str;
use Helper;
use DB;
use \Midtrans\Notification;
use \Midtrans\Config;
use Auth;

class MidtransController extends Controller
{
    public $request;
    public $order;
    public $shipping;

    public function shipping ()
      {
        $shipping = new Shipping();
      
      }

    public function midtrans (Request $request)    
  {   
      // Set your Merchant Server Key
      \Midtrans\Config::$serverKey = 'SB-Mid-server-Ld2nTlIbG3eAX62-aG1Y1OoF';
      // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
      \Midtrans\Config::$isProduction = false;
      // Set sanitization on (default)
      \Midtrans\Config::$isSanitized = true;
      // Set 3DS transaction for credit card to true
      \Midtrans\Config::$is3ds = true;
      

      

      $payloads = Order::orderBy('id', 'desc')->get();
      $order = new Order();
      $payloads=$request->user();
            $payloads['order_number']='ORD-'.strtoupper(Str::random(10));
            $payloads['user_id']=Auth::user()->id;
            $shipping=Shipping::pluck('price');
            $payloads['shipping_id']=$shipping;
            // return session('coupon')['value'];
            $payloads['sub_total']=Helper::totalCartPrice();
            $payloads['quantity']=Helper::cartCount();
            // dd($payloads);
            if(session('coupon')){
            $payloads['coupon']=session('coupon')['value'];
        }
        if($request->user()){
            if(session('coupon')){
                $payloads['total_amount']=Helper::totalCartPrice()+$shipping[0]-session('coupon')['value'];
            }
            else{
                $payloads['total_amount']=Helper::totalCartPrice()+$shipping[0];
            }
        }
        else{
            if(session('coupon')){
                $payloads['total_amount']=Helper::totalCartPrice()-session('coupon')['value'];
            }
            else{
                $payloads['total_amount']=Helper::totalCartPrice();
            }
        } 
        // dd($payloads);
        
      if(empty($payloads))
      $payloads = Order::create([
                'first_name' => $payloads->first_name,
                'email' => $payloads->email,
                'amount' => floatval($this->request->total_amount),
            ]);
        $payloads = [
          'transaction_details' => [
              'order_id' => $payloads->order_number,
              'gross_amount' => floatval($payloads->total_amount),
          ],
          'customer_details' => [
              'first_name' => 'Dear Buyer',
              'last_name' => Auth::user()->name,
              'email' => Auth::user()->email,
              'phone' => $payloads->phone,
          ],
      ];
         
      // dd($payloads);
      $snapToken = \Midtrans\Snap::getSnapToken($payloads);
      
            
      $payloads = json_encode($payloads);
      
         return view('frontend.pages.checkout',['snapToken' => $snapToken]);
//dd($snapToken);
  }

  /**
     * Midtrans notification handler.
     *
     * @param Request $request
     * 
     * @return void
     */
    public function notificationHandler(Request $id)
    {
        $notif = new \Midtrans\Notification();
 
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $payloads = $notif->order_number;
        $fraud = $notif->fraud_status;
         
        if ($transaction == 'capture') {
          // For credit card transaction, we need to check whether transaction is challenge by FDS or not
          if ($type == 'credit_card'){
            if($fraud == 'challenge'){
              // TODO set payment status in merchant's database to 'Challenge by FDS'
              // TODO merchant should decide whether this transaction is authorized or not in MAP
              echo "Transaction order_id: " . $order_id ." is challenged by FDS";
              }
              else {
              // TODO set payment status in merchant's database to 'Success'
              echo "Transaction order_id: " . $order_id ." successfully captured using " . $type;
              }
            }
          }
        else if ($transaction == 'settlement'){
          // TODO set payment status in merchant's database to 'Settlement'
          echo "Transaction order_id: " . $order_id ." successfully transfered using " . $type;
          }
          else if($transaction == 'pending'){
          // TODO set payment status in merchant's database to 'Pending'
          echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
          }
          else if ($transaction == 'deny') {
          // TODO set payment status in merchant's database to 'Denied'
          echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
          }
          else if ($transaction == 'expire') {
          // TODO set payment status in merchant's database to 'expire'
          echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";
          }
          else if ($transaction == 'cancel') {
          // TODO set payment status in merchant's database to 'Denied'
          echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
        }

        

        return;
    }
}