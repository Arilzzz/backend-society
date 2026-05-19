<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Society;
use App\Models\Vaccination;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VaccinationController extends Controller
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

        $vaccinations = Vaccination::with([
            'spot.regional',
            'vaccine',
            'vacinator'
        ])
            ->where('society_id', $society->id)
            ->get();

        $first = $vaccinations->where('dose', 1)->first();
        $second = $vaccinations->where('dose', 2)->first();

        return response()->json([
            'vaccinations' => [
                'first' => $first
                    ? $this->formatVaccination($first)
                    : null,

                'second' => $second
                    ? $this->formatVaccination($second)
                    : null
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

        // validation
        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d',
            'spot_id' => 'required|exists:spots,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 401);
        }

        // dd($society->consultation);
        // consultation check
        $consultation = $society->consultation;

        if (!$consultation || $consultation->status != 'accepted') {
            return response()->json([
                'message' =>
                'Your consultation must be accepted by doctor before'
            ], 401);
        }

        $vaccinations = Vaccination::where(
            'society_id',
            $society->id
        )->orderBy('dose')->get();

        // sudah 2x vaksin
        if ($vaccinations->count() >= 2) {
            return response()->json([
                'message' => 'Society has been 2x vaccinated'
            ], 401);
        }

        // tentukan dose
        $dose = $vaccinations->count() + 1;

        // cek jarak 30 hari untuk dose 2
        if ($dose == 2) {

            $firstVaccination = $vaccinations->first();

            $firstDate = Carbon::parse(
                $firstVaccination->date
            );

            $newDate = Carbon::parse($request->date);

            if ($firstDate->diffInDays($newDate) < 30) {

                return response()->json([
                    'message' =>
                    'Wait at least +30 days from 1st Vaccination'
                ], 401);
            }
        }

        // queue hari itu
        $queue = Vaccination::where('spot_id', $request->spot_id)
            ->whereDate('date', $request->date)
            ->count() + 1;

        dd($queue);
        Vaccination::create([
            'dose' => $dose,
            'date' => $request->date,
            'spot_id' => $request->spot_id,
            'society_id' => $society->id,
            'queue' => $queue,
        ]);

        return response()->json([
            'message' =>
            $dose == 1
                ? 'First vaccination registered successful'
                : 'Second vaccination registered successful'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    private function formatVaccination($vaccination)
    {
        return [
            'queue' => $vaccination->queue,
            'dose' => $vaccination->dose,
            'vaccination_date' => $vaccination->date,

            'spot' => [
                'id' => $vaccination->spot->id,
                'name' => $vaccination->spot->name,
                'address' => $vaccination->spot->address,
                'serve' => $vaccination->spot->serve,
                'capacity' => $vaccination->spot->capacity,

                'regional' => [
                    'id' =>
                    $vaccination->spot->regional->id,

                    'province' =>
                    $vaccination->spot->regional->province,

                    'district' =>
                    $vaccination->spot->regional->district,
                ]
            ],

            'status' => $vaccination->status,

            'vaccine' => $vaccination->vaccine
                ? [
                    'id' => $vaccination->vaccine->id,
                    'name' => $vaccination->vaccine->name,
                ]
                : null,

            'vaccinator' => $vaccination->vaccinator
                ? [
                    'id' => $vaccination->vaccinator->id,
                    'role' => $vaccination->vaccinator->role,
                    'name' => $vaccination->vaccinator->name,
                ]
                : null,
        ];
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
