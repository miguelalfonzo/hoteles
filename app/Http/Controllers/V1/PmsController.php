<?php
namespace App\Http\Controllers\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Pms;


class PmsController extends Controller
{
   

  

    

    protected function today(Request $request)
    {
        


        $data = $request->only('hotel','type');

        $validator = Validator::make($data, [
            'hotel' => 'required|numeric',
            'type' => 'required|numeric',
           
            
        ]);
        
        if ($validator->fails()) {

            $middleRpta = $this->setRpta('warning','validator fails',$validator->messages());

            return response()->json($middleRpta, 400);
        }
      
    
        $list = Pms::today($request);

        $middleRpta = $this->setRpta('ok','success list',$list);

        return response()->json($middleRpta,Response::HTTP_OK);

    }
    
    
   protected function indicators(Request $request)
    {
        


        $data = $request->only('hotel');

        $validator = Validator::make($data, [
            'hotel' => 'required|numeric',
           
           
            
        ]);
        
        if ($validator->fails()) {

            $middleRpta = $this->setRpta('warning','validator fails',$validator->messages());

            return response()->json($middleRpta, 400);
        }
      
    
        $list = Pms::indicators($request);

        $middleRpta = $this->setRpta('ok','success list',$list);

        return response()->json($middleRpta,Response::HTTP_OK);

    }
   
    
}