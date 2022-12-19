<?php
namespace App\Http\Controllers\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Booking;
use DB;


class BookingController extends Controller
{
   

  




    protected function search(Request $request)
    {
        


        $data = $request->only('hotel','fromDate', 'toDate');

        $validator = Validator::make($data, [
            'hotel' => 'required|numeric',
            'fromDate' => 'required|date|after_or_equal:today',
            'toDate' => 'required|date|after:fromDate',
            
        ]);
        
        if ($validator->fails()) {

            $middleRpta = $this->setRpta('error','validator fails',$validator->messages());

            return response()->json($middleRpta, 400);
        }
      
    
        $booking = Booking::search($request);

        $middleRpta = $this->setRpta('ok','success list',$booking);

        return response()->json($middleRpta,Response::HTTP_OK);

    }


    protected function validateBeforeInsertBooking($request){


        

        $resultValidate = Booking::validateBeforeInsertBooking($request);

        

        if(isset($resultValidate[0]->STATE)){

            return $this->setRpta($resultValidate[0]->STATE,$resultValidate[0]->MESSAGE,[]);
        }


        return $this->setRpta('error','the request can not be processed',[]);
    }



    protected function create(Request $request)
    {
        
        try {
            
            DB::beginTransaction();


            $data = $request->only('hotel','agent', 'bookingStatus', 'country', 'guestFirstName','guestLastName','guestEmail', 'guestPhone', 'dateArrival','arrivalTime', 'adults','kids','dateFrom', 'dateTo', 'coupon', 'specialRequest','origen','paymentStatus', 'paymentType', 'applyIgv', 'amount','quantity','cardType', 'cardNumber', 'cvv', 'cardExpiration', 'codeReference', 'description', 'roomsTypeCount');

            $validator = Validator::make($data, [
                'hotel' => 'required|numeric',
                'agent' => 'required|numeric',
                'bookingStatus' => 'required|numeric',
                'country' => 'required|string|max:3',
                'guestFirstName' => 'required|string|max:100',
                'guestLastName' => 'required|string|max:100',
                'guestEmail' => 'required|string|email|max:100',
                'guestPhone' => 'string|max:20',
                'adults' => 'required|numeric',
                'kids' => 'required|numeric',
                'dateFrom' => 'required|date|after_or_equal:today',
                'dateTo' => 'required|date|after:dateFrom',
                'dateArrival' => 'required|date|after_or_equal:dateFrom|before_or_equal:dateTo',
                'arrivalTime' => 'nullable|string|max:10',
                'coupon'=> 'nullable|string|max:250',
                'specialRequest' => 'nullable|string|max:250',
                'origen' => 'required|numeric',
                'paymentStatus' => 'required|numeric',
                'paymentType' => 'required|numeric',
                'applyIgv' => 'required|numeric',
                'amount' => 'required|numeric',
                'quantity' => 'required|numeric',
                'cardType' => 'required|numeric',
                'cardNumber' => 'required|string|max:30',
                'cvv' => 'required|string|max:4',
                'cardExpiration' => 'required|string|max:5',
                'codeReference' => 'nullable|string|max:250',
                'description' => 'nullable|string|max:250',
                'roomsTypeCount' =>'required|string'
                
            ]);
        
            if ($validator->fails()) {


                return response()->json($this->setRpta('error','validator fails',$validator->messages()), 400);
            }
        


            $middleRpta = $this->validateBeforeInsertBooking($request);



            if($middleRpta["status"] == "ok"){


                $resultId = Booking::create($request);

                
               

                if(isset($resultId[0]->ID) && is_int($resultId[0]->ID)){

                

                    DB::commit();

                    return response()->json($this->setRpta('ok','reservation created successfully : '.$resultId[0]->ID,[]),201);

                }

             
                DB::rollBack();
           
                return response()->json($this->setRpta('error','could not generate reservation for id',[]),400);



            }

            DB::rollBack();

           return response()->json($middleRpta,400);


        } catch (\Exception $e) {
             

             DB::rollBack();

           
             return response()->json($this->setRpta('error','transact : '.$e->getMessage(),[]), 400);
        }


        

    }
    
    
   
   
    
}