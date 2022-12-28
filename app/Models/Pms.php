<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;


class Pms extends Model
{
    use HasFactory;

   
   protected static function today($request){


   		
   		$hotel  = $request->hotel;
   		
   		$type  = $request->type;

    	$list  = DB::select('CALL sp_pms_get_list_bookings_today (?,?)', array($hotel,$type));

    	return $list;
    
    }

    protected static function indicators($request){


   		
   		$hotel  = $request->hotel;
   		
   		

    	$list  = DB::select('CALL sp_pms_get_indicators_dashboard (?)', array($hotel));

    	return $list;
    
    }

    
    
    
}
