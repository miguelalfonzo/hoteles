<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;


class Booking extends Model
{
    use HasFactory;

   
   protected static function search($request){


   		
   		$hotel    = $request->hotel;
   		$fromDate = Carbon::parse($request->fromDate)->format('Y-m-d');
   		$toDate   = Carbon::parse($request->toDate)->format('Y-m-d');
   		


    	$list  = DB::select('CALL sp_web_availability_typeroom (?,?,?)', array($hotel,$fromDate,$toDate));

    	return $list;
    
    }


    protected static function create($request){

    	$hotel 			= $request->hotel ;
        $agent 			= $request->agent ;
        $bookingStatus 	= $request->bookingStatus ;
        $country 		= $request->country ;
        $guestFirstName = trim($request->guestFirstName) ;
        $guestLastName 	= trim($request->guestLastName) ;
        $guestEmail 	= trim($request->guestEmail) ;
        $guestPhone 	= trim($request->guestPhone) ;
        $dateArrival 	= Carbon::parse($request->dateArrival)->format('Y-m-d');
        $arrivalTime 	= $request->arrivalTime ;
        $adults 		= $request->adults ;
        $kids 			= $request->kids ;
        $dateFrom 		= Carbon::parse($request->dateFrom)->format('Y-m-d');
        $dateTo 		= Carbon::parse($request->dateTo)->format('Y-m-d');
        $coupon 		= $request->coupon ;
        $specialRequest = $request->specialRequest ;
        $origen 		= $request->origen ;
        $paymentStatus  = $request->paymentStatus  ;
        $paymentType 	= $request->paymentType  ;
        $applyIgv 		= $request->applyIgv ;
        $amount 		= $request->amount ;
        $quantity 		= $request->quantity ;
        $cardType 		= $request->cardType ;
        $cardNumber 	= $request->cardNumber ;
        $cvv 			= $request->cvv ;
        $cardExpiration = $request->cardExpiration ;
        $codeReference 	= $request->codeReference ;
        $description 	= $request->description ;
        $roomsTypeCount = $request->roomsTypeCount ;


        $booking  = DB::select('CALL sp_web_insert_booking (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', array(
        $hotel,$agent,$bookingStatus,$country ,$guestFirstName ,$guestLastName ,$guestEmail,$guestPhone,$dateArrival,$arrivalTime,$adults ,$kids,$dateFrom,$dateTo,$coupon,$specialRequest,$origen ,$paymentStatus,$paymentType ,$applyIgv ,$amount ,$quantity ,$cardType ,$cardNumber ,$cvv ,$cardExpiration,$codeReference ,$description ,$roomsTypeCount ));

    	return $booking;

    }

    protected static function validateBeforeInsertBooking($request){

    	$roomsTypeCount = trim($request->roomsTypeCount);
   		$dateFrom = Carbon::parse($request->dateFrom)->format('Y-m-d');
   		$dateTo   = Carbon::parse($request->dateTo)->format('Y-m-d');
   		$hotel 	= $request->hotel ;
   		$agent 	= $request->agent ;

   		$bookingStatus 	= $request->bookingStatus ;
   		$paymentStatus 	= $request->paymentStatus ;
   		$paymentType 	= $request->paymentType ;
   		$origen 		= $request->origen ;
   		

    	$rpta  = DB::select('CALL sp_web_before_insert_booking (?,?,?,?,?,?,?,?,?)', array($roomsTypeCount,$dateFrom,$dateTo,$hotel,$agent,$bookingStatus,$paymentStatus,$paymentType,$origen));

    	return $rpta;


    }
    
}
