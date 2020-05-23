<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

use Validator;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function action(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [     
                'value' => 'required|integer|min:0',
            ]);
           
            $messages = $validator->errors();
            
            if (!$messages->isEmpty())
            {
                return response()->json([
                    'error'=>'input-validation-error',
                    'message'=> $messages,
                    ], 400);
            }

            $inputValue = intval($request->value);
            $reservedValue = $inputValue-1;
            $outputValue = $inputValue+1;

            if (Redis::mSetNx(['used-numbers-'.$inputValue=>$inputValue,'used-numbers-'.$reservedValue=>$reservedValue]))
            {
                return response()->json([
                    'status'=>'success',
                    'answer'=> $outputValue,
                    ], 201);
            }
            else
            {
                Log::warning('Number processing denied for input value '.$inputValue);
                return response()->json([
                    'error'=>'already-exists',
                    'message'=> 'Can\'t process this numeric value. Please, try another value.',
                    ], 400);
            }
        }

        catch (\Exception $e)
        {
            Log::error('An unhandled exception occurred while processing the value '.$inputValue);
            return response()->json([
                'error'=>'server-internal-error',
                'message'=>"Unexpected server error",
                ], 500);

        }
    }
}