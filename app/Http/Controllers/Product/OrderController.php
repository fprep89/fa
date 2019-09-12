<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Instamojo as Instamojo;
use App\Models\Product\Product;
use App\Models\Test\Test;
use App\Models\Product\Order;
use App\Models\Product\Coupon;
use App\User;
use App\Models\Product\Order as Obj;

class OrderController extends Controller
{
    /*
        Order Controller
    */

    public function __construct(){
        $this->app      =   'product';
        $this->module   =   'order';
    }

    /**
     * Checkout page.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkout($slug,Request $request){
        $product = Product::where('slug',$slug)->first();
        $test = Test::where('slug',$slug)->first();
        if($product){
              return view('appl.product.order.checkout')->with('product',$product);
        }
        if($test){
              return view('appl.product.order.checkout')->with('test',$test);
        }
    }

    /**
     * To handle instamojo return request
     *
     */
    public function instamojo_return(Request $request){
      $api = new Instamojo\Instamojo('test_43eb01abde88edc5f67120bc66b', 'test_0e4d7ecf73f435abd0236582e93','https://test.instamojo.com/api/1.1/');
      try {
            $id = $request->get('payment_request_id');

            if($id){
                $response = $api->paymentRequestStatus($id);
            }
            else
              echo "input the id";

          if($response['status']=='Completed')
          { 
            $order = Order::where('order_id',$id)->first();

            $order->payment_mode = $response['payments'][0]['instrument_type'];
            $order->bank_txn_id = $response['payments'][0]['payment_id'];
            $order->bank_name = $response['payments'][0]['billing_instrument'];
            $order->txn_id = $response['payments'][0]['payment_id'];
            if($response['status']=='Completed'){
              $order->status = 1;
            }
            else{
              $order->status = 2;
              
            }
            $order->save();
          }

        if ($response['status']=='Completed') {
          $order->payment_status = 'Successful';
          //Mail::to($user->email)->send(new OrderSuccess($user,$order));
        }
        
        return view('appl.product.order.checkout_success')->with('order',$order);
            
        }
        catch (Exception $e) {
            print('Error: ' . $e->getMessage());
        }


    }



     /**
     * Redirect the user to the Payment Gateway.
     *
     * @return Response
     */
    public function order(Request $request)
    {
          if($request->type=='instamojo' && $request->txn_amount!=0){

          $api = new Instamojo\Instamojo('test_43eb01abde88edc5f67120bc66b', 'test_0e4d7ecf73f435abd0236582e93','https://test.instamojo.com/api/1.1/');
          try {
            

            if($request->txn_amount<10)
                abort('403','Transaction ammount cannot be < Rs.10');

            $user = \auth::user();
           

            if($request->get('product_id')){
              $product = Product::where('id',$request->get('product_id'))->first();
              $validity = $product->validity;
              $purpose = $product->name;
            }
            else
              $product = null;

            if($request->get('test_id')){
              $test = Test::where('id',$request->get('test_id'))->first();
              $validity = $test->validity;
              $purpose = $test->name;
            }
            else
              $test = null;




            
            
              
            $response = $api->paymentRequestCreate(array(
                  "buyer_name" => $user->name,
                  "purpose" => strip_tags($purpose),
                  "amount" =>  $request->txn_amount,
                  "send_email" => false,
                  "email" => $user->email,
                  "redirect_url" => "https://fa.packetprep.com/order_payment"
                ));

              //dd($response);
              $order = new Order();
              $order->order_id = $response['id'];

              $o_check = Order::where('order_id',$order->order_id)->first();
              while($o_check){
                $response = $api->paymentRequestCreate(array(
                  "buyer_name" => $user->name,
                  "purpose" => strip_tags($product->name),
                  "amount" =>  $request->txn_amount,
                  "send_email" => false,
                  "email" => $user->email,
                  "redirect_url" => "https://fa.packetprep.com/order_payment"
                  ));
                $order->order_id = $response->id;
                $o_check = Order::where('order_id',$order->order_id)->first();
                if(!$o_check)
                  break;
              }

              $order->user_id = $user->id;
              $order->txn_amount = $request->txn_amount;
              $valid_till = date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s") .' + '.($validity*31).' days'));
              $order->expiry = $valid_till;
              $order->status=0;
              $order->product_id = $request->get('product_id');
              $order->test_id = $request->get('test_id');

              
               //dd($order);
              $order->save();
              $order->payment_status = 'Pending';

              return redirect($response['longurl']);

          }
          catch (Exception $e) {
              print('Error: ' . $e->getMessage());
          }



          
        }else{
         $api = new Instamojo\Instamojo('test_43eb01abde88edc5f67120bc66b', 'test_0e4d7ecf73f435abd0236582e93','https://test.instamojo.com/api/1.1/');

          try {
            

            $user = \auth::user();
           

            if($request->get('product_id')){
              $product = Product::where('id',$request->get('product_id'))->first();
              $validity = $product->validity;
            }
            else
              $product = null;

            if($request->get('test_id')){
              $test = Test::where('id',$request->get('test_id'))->first();
              $validity = $test->validity;
            }
            else
              $test = null;

            $coupon = Coupon::where('code',strtoupper($request->get('coupon')))->first();


            

            if(!$coupon && $request->get('coupon')!='FREE'){
                $m = "Invalid Coupon Code";
                 return view('appl.product.order.checkout_coupon')->with('message',$m);

            }else{

              if($request->get('coupon')=='FREE'){
                  if($product)
                  if($product->price !=0){
                    $m = "You cannot access this course";
                    return view('appl.product.order.checkout_coupon')->with('message',$m);
                  } 

                  if($test)
                  if($test->price !=0){
                    $m = "You cannot access this course";
                    return view('appl.product.order.checkout_coupon')->with('message',$m);
                  } 

              }else{
                if($coupon->status==0){
                   $m = "Coupon Code Expired!";
                 return view('appl.product.order.checkout_coupon')->with('message',$m); 
                }
                
                if($product){
                    if(!$coupon->products()->where('id',$product->id)->first()){
                    $m = "Restricted Access - This Copoun cannot be used for this product";
                    return view('appl.product.order.checkout_coupon')->with('message',$m);
                  }
                }else{
                  if(!$coupon->tests()->where('id',$test->id)->first()){
                    $m = "Restricted Access - This Copoun cannot be used for this test";
                    return view('appl.product.order.checkout_coupon')->with('message',$m);
                }
                }
                

              }
            }

              //dd($response);
              $order = new Order();
              $order->order_id = 'ORD_'.substr(md5(mt_rand()), 0, 10);

              $o_check = Order::where('order_id',$order->order_id)->first();
              while($o_check){
                $order->order_id = 'ORD_'.substr(md5(mt_rand()), 0, 10);
                $o_check = Order::where('order_id',$order->order_id)->first();
                if(!$o_check)
                  break;
              }

              $order->user_id = $user->id;
              $order->txn_amount = 0;
              $order->status=1;
              $order->txn_id = '';
              if($request->get('coupon') == 'FREE')
                $order->payment_mode = 'FREE';
              else{
                $order->payment_mode = 'COUPON';
                //update coupon
                if(!$coupon->unlimited){
                  $coupon->status = 0;
                  $coupon->user_id = \auth::user()->id;
                  $coupon->save();
                }
                $order->txn_id = $coupon->code;
              }

              
              $order->product_id = $request->get('product_id');
              $order->test_id = $request->get('test_id');

              $valid_till = date('Y-m-d H:i:s', strtotime(date("Y-m-d H:i:s") .' + '.($validity*31).' days'));
              $order->expiry = $valid_till;
              
               //dd($order);
              $order->save();
              $order->payment_status = 'Successful';

              
             // Mail::to($user->email)->send(new OrderSuccess($user,$order));

              return view('appl.product.order.checkout_success')->with('order',$order);

          }
          catch (Exception $e) {
              print('Error: ' . $e->getMessage());
          }


        }
        
    }

  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Obj $obj,Request $request)
    {


        $this->authorize('view', $obj);

        $search = $request->search;
        $item = $request->item;
        
        if($request->get('coupon'))
        {
          $coupon = strtoupper($request->get('coupon'));
          $objs = $obj->where('txn_id',$coupon)
                    ->orderBy('created_at','desc')
                    ->paginate(config('global.no_of_records')); 
        }else{
            $objs = $obj->where('order_id','LIKE',"%{$item}%")
                    ->orderBy('created_at','desc')
                    ->paginate(config('global.no_of_records')); 
        }
          
        $view = $search ? 'list': 'index';

        return view('appl.'.$this->app.'.'.$this->module.'.'.$view)
                ->with('objs',$objs)
                ->with('obj',$obj)
                ->with('app',$this);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function myorders(Obj $obj,Request $request)
    {
        $search = $request->search;
        $item = $request->item;
        
        $objs = $obj->where('order_id','LIKE',"%{$item}%")
                    ->where('user_id',\auth::user()->id)
                    ->orderBy('created_at','desc')
                    ->paginate(config('global.no_of_records'));   
        $view = $search ? 'mylist': 'myorders';

        return view('appl.'.$this->app.'.'.$this->module.'.'.$view)
                ->with('objs',$objs)
                ->with('obj',$obj)
                ->with('app',$this);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function myordersview($order_id)
    {
        $obj = Obj::where('order_id',$order_id)->first();
        $this->authorize('view', $obj);
        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.myview')
                    ->with('obj',$obj)->with('app',$this);
        else
            abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $obj = Obj::where('id',$id)->first();
        $this->authorize('view', $obj);
        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.show')
                    ->with('obj',$obj)->with('app',$this);
        else
            abort(404);
    }
    
    
}
