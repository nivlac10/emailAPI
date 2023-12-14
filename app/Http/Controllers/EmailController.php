<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\Log; 
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    public function postEmail(Request $request)
    {
        // Custom error response format
        $errorResponse = function ($message, $code = 400) {
            return response()->json(['error' => $message], $code);
        };

        // Validate the request parameters
        $validator = Validator::make($request->all(), [
            'txId' => 'required',
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            return $errorResponse("Validation failed: " . $validator->errors()->first());
        }

        $validatedData = $validator->validated();

        try {
            // Fetch a random email
            $email = Email::inRandomOrder()->firstOrFail();

            // Store the log in the database
            Log::create([
                'tx_id' => $validatedData['txId'],
                'amount' => $validatedData['amount'],
                'email' => $email->email,
            ]);

            return response()->json(['email' => $email->email]);
        } catch (ModelNotFoundException $e) {
            return $errorResponse("No emails found in the database", 404);
        } catch (\Exception $e) {
            // Handle other possible exceptions
            return $errorResponse("An error occurred: " . $e->getMessage(), 500);
        }
    }
}
