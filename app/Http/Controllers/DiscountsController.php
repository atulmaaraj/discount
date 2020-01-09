<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Discount;
use Illuminate\Support\Facades\Validator;


class DiscountsController extends Controller
{
    public function discounts(Request $request)
    {
        if ($request->isMethod('POST')) {
            // validation rules for email & mobile no.
            $validator = Validator::make($request->all(), [
                'email' => 'email',
                'mobile' => 'min:10|max:10'
            ]);
            if ($validator->fails()) {
                $response = array("msg" => "Bad Request", "error" => $validator->errors()->toArray(), "discount" => "");
                return response()->json($response, 400);
            }
            $count_total = Discount::sum('count');
            $occurrence_total = Discount::sum('occurrence');
            // validation for no discount value left
            if ($count_total != $occurrence_total) {
                // user save
                $user = new User();
                $user->name  = $request->input('name');
                $user->email = $request->input('email');
                $user->mobile = $request->input('mobile');
                $user->address = $request->input('address');
                if ($user->save()) {
                    $arr = [];
                    $discounts = Discount::all()->toArray();
                    foreach ($discounts as $discount) {
                        $count =  $discount['count'];
                        $occurrence = $discount['occurrence'];
                        if ($count != $occurrence) {
                            $length = $count - $occurrence;
                            for ($i = 1; $i <= $length; $i++) {
                                array_push($arr, $discount['discount']);
                            }
                        }
                        // suffle array for random discount value
                        shuffle($arr);
                    }
                    //for increasing probability of lowest discount value 3 times or else comment this code for random shuffle
                    if (count($arr) > 3) {
                        $n = 3;
                        $discount_value = $arr[0];
                        for ($i = 1; $i < $n; $i++) {
                            if ($discount_value > $arr[$i]) {
                                $discount_value = $arr[$i];
                            }
                        }
                    } else {
                        $discount_value = $arr[0];
                    }
                    // User::truncate();
                    $update_occurrence = Discount::where('discount', $discount_value)->first();
                    $update_occurrence->occurrence = $update_occurrence->occurrence + 1;
                    if ($update_occurrence->save()) {

                        $response = array("msg" => "SUCCESS", "error" => $discount_value, "discount" => $arr);
                        return response()->json($response, 200);
                    } else {
                        $response = array("msg" => "FAILED", "error" => "Whoops! Something went wrong. Plesase try again.", 'discount' => "");
                        return response()->json($response, 503);
                    }
                } else {
                    $response = array("msg" => "FAILED", "error" => "Whoops! Something went wrong. Plesase try again.", 'discount' => "");
                    return response()->json($response, 503);
                }
            } else {
                $response = array("msg" => "SUCCESS", "error" => "All discount values have already been given, no more discount values left.", 'discount' => "");
                return response()->json($response, 200);
            }
        } else {
            $response = array("msg" => "Welcome !");
            return response()->json($response, 200);
        }
    }
}
