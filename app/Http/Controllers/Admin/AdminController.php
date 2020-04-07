<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Test\Test as Obj;
use App\Models\Test\Attempt;
use App\Models\Test\Writing;
use App\Models\Admin\Admin;
use App\Models\Admin\Form;
use App\Models\Product\Coupon;

use App\Mail\contactmessage;
use App\Mail\ErrorReport;

use Illuminate\Support\Facades\Mail;
use App\User;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Obj $obj)
    {
        $this->authorize('view', $obj);
        $data['users'] = User::orderBy('id','desc')->get();
        /* writing data */
        $test_ids = Obj::whereIn('type_id',[3])->pluck('id')->toArray();
       
        $data['writing']= Attempt::whereIn('test_id',$test_ids)->whereNull('answer')->orderBy('created_at','desc')->get();

        $attempts = Attempt::orderBy('created_at','desc')->get();

       
        
        $data['new'] = User::where('admin','0')->orderBy('lastlogin_at','desc')->limit(5)->get();

        
        $data['form'] = Form::orderBy('id','desc')->limit(5)->get();

        $latest = [];$count=0;
        foreach($attempts as $a){
            if(!in_array($a->test_id, $test_ids))
            if(!isset($latest[$a->test_id.$a->user_id])){
              
                  $latest[$a->test_id.$a->user_id]['user']= $a->user;
                  $latest[$a->test_id.$a->user_id]['test'] = $a->test;
                  $latest[$a->test_id.$a->user_id]['attempt'] = $a;

                $count++;
                
            }
        }

       // dd($count);
         
        $data['latest'] = $latest;
        $data['attempt_total'] = $count; 
  
        $data['coupon'] = Coupon::where('code','FA5Y9')->first();
        return view('appl.admin.admin.index')->with('data',$data);
        
    }

    public function analytics(Obj $obj){
        $this->authorize('view', $obj);
        $admin = new Admin;
        $data['user'] = $admin->userAnalytics();
        $data['order'] = $admin->orderAnalytics(); 
        $data['group_count'] = $admin->groupCount();
        $data['test_count'] = $admin->testCount();
        $data['product_count'] = $admin->productCount();
        $data['coupon_count'] = $admin->couponCount();
        return view('appl.admin.admin.analytics')->with('data',$data);
    }


    public function contact(Request $r){
        
        Mail::to(config('mail.report'))->send(new contactmessage($r));
        return view('appl.admin.admin.contactmessage');
    }

    
    public function notify(Request $r){
        
        Mail::to(config('mail.report'))->send(new  ErrorReport($r));
        echo "Successfully reported to administrator.";
    }


   
}
