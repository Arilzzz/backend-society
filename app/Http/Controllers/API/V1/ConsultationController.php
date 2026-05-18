<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $consultation = Consultation::with('doctor')
            ->where('society_id', $request->society->id)
            ->first();

        return response()->json([
            'consultation' => $consultation
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'disease_history' => 'required|string',
            'current_symptoms' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Unauthorized user'
            ], 401);
        }

        $consultation = Consultation::create(
            $request->only('disease_history', 'current_symptoms') +
                ['society_id' => $request->society->id]
        );

        return response()->json([
            'message' => 'Request consultation sent successful',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
