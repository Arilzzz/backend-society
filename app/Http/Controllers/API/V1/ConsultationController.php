<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Society;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $token = $request->token;

        $society = Society::where('login_tokens', $token)->first();

        if (!$society) {
            return response()->json([
                'message' => 'Unauthorized user'
            ], 401);
        }

        $consultation = Consultation::with('doctor')
            ->where('society_id', $society->id)
            ->first();

        if (!$consultation) {
            return response()->json([
                'consultation' => null
            ], 200);
        }

        return response()->json([
            'consultation' => [
                'id' => $consultation->id,
                'status' => $consultation->status,
                'disease_history' => $consultation->disease_history,
                'current_symptoms' => $consultation->current_symptoms,
                'doctor_notes' => $consultation->doctor_notes,

                'doctor' => $consultation->doctor ? [
                    'id' => $consultation->doctor->id,
                    'name' => $consultation->doctor->name,
                ] : null
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $token = $request->token;

        $society = Society::where('login_tokens', $token)->first();

        if (!$society) {
            return response()->json([
                'message' => 'Unauthorized user'
            ], 401);
        }

        // cek apakah sudah pernah konsultasi
        $exists = Consultation::where('society_id', $society->id)->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Society already has consultation request'
            ], 400);
        }

        Consultation::create([
            'society_id' => $society->id,
            'status' => 'pending',
            'disease_history' => $request->disease_history,
            'current_symptoms' => $request->current_symptoms,
        ]);

        return response()->json([
            'message' => 'Request consultation sent successful'
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
