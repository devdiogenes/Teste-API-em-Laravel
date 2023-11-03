<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceOrders;
use App\Requests\ServiceOrderRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class ServiceOrdersController extends Controller
{
    public function create(Request $request){
        
        $data_validation = [
            "vehiclePlate" => [
                "size:7",
                "regex:/(^[A-Za-z0-9 ]+$)+/",
                "required"
            ],
            "entryDateTime" => [
                "date",
                "required"
            ],
            "exitDateTime" => [
                "date"
            ],
            "priceType" => [

            ],
            "price" => [
                "decimal:0,2",
                "required"
            ]
        ];
        
        foreach($request->all() as $key => $value){
            if(!in_array($key, array_keys($data_validation))){
                return ["Error" => "Field ".$key." is invalid."];
            }
        };

        $request -> validate($data_validation);
        return ServiceOrders::create([
            "vehiclePlate" => strtoupper($request->vehiclePlate),
            "entryDateTime" => $request->entryDateTime,
            "exitDateTime" => $request->exitDateTime,
            "priceType" => $request->priceType,
            "price" => $request->price,
            "userId" => Auth::id(),
        ]);
    }

    public function list(Request $request){
        $list_query = ServiceOrders::with('user');

        $allowed_filters = [
            "vehiclePlate" => ["=", "!=", "<>"],
            "entryDateTime" => [">", ">=", "<", "<="],
            "exitDateTime" => [">", ">=", "<", "<="],
            "priceType" => ["=", "!=", "<>"],
            "price" => ["=", ">", ">=", "<", "<="],
            "user_name" => ["=", "!=", "<>"],
            "user_email" => ["=", "!=", "<>"]
        ];

        $filters = $request->filters;
        if($filters){
            if(gettype($filters) != "array"){
                return ["Error" => "Filters field should be a array"];
            };
    
            foreach($filters as $filter){
                if(gettype($filter) != "array"){
                    return ["Error" => "All filters should have array format"];
                }
                $filter_name = $filter[0];
                if(sizeof($filter) != 3 || !in_array($filter_name, array_keys($allowed_filters))){
                    return ["Error" => "Invalid filter"];
                };
                $filter_operator = $filter[1];
                if(!in_array($filter_operator, $allowed_filters[$filter_name])){
                    return ["Error" => "Invalid operator for ".$filter_name." filter."];
                }
                if(in_array($filter_name, ['user_name', 'user_email'])){
                    $filter_name = str_replace("user_", "", $filter_name);
                    $list_query = $list_query->whereHas('user', function (Builder $query) use ($filter_name, $filter_operator, $filter) {
                        $query->where($filter_name, $filter_operator, $filter[2]);
                    });
                } else {
                    $list_query = $list_query->where($filter_name, $filter_operator, $filter[2]);
                }
            }
        }
        
        return $list_query->paginate(5);
    }
}