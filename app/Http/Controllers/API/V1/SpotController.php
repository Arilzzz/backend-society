<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Society;
use App\Models\Spot;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SpotController extends Controller
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

        $spots = Spot::with('vaccines')->get();

        $data = $spots->map(function ($spot) {

            $vaccines = $spot->vaccines
                ->pluck('name')
                ->toArray();

            return [
                'id' => $spot->id,
                'name' => $spot->name,
                'address' => $spot->address,
                'serve' => $spot->serve,
                'capacity' => $spot->capacity,

                'available_vaccines' => [
                    'Sinovac' => in_array('Sinovac', $vaccines),
                    'AstraZeneca' => in_array('AstraZeneca', $vaccines),
                    'Moderna' => in_array('Moderna', $vaccines),
                    'Pfizer' => in_array('Pfizer', $vaccines),
                    'Sinnopharm' => in_array('Sinnopharm', $vaccines),
                ]
            ];
        });

        return response()->json([
            'spots' => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $token = $request->token;

        $society = Society::where('login_tokens', $token)->first();

        if (!$society) {
            return response()->json([
                'message' => 'Unauthorized user'
            ], 401);
        }

        $spot = Spot::find($id);

        if (!$spot) {
            return response()->json([
                'message' => 'Spot not found'
            ], 404);
        }

        // default hari ini
        $date = $request->date ?? now()->toDateString();

        // hitung vaccination sesuai tanggal
        $vaccinationsCount = $spot->vaccinations()
            ->whereDate('date', $date)
            ->count();

        return response()->json([
            'date' => Carbon::parse($date)->format('F d, Y'),

            'spot' => [
                'id' => $spot->id,
                'name' => $spot->name,
                'address' => $spot->address,
                'serve' => $spot->serve,
                'capacity' => $spot->capacity,
            ],

            'vaccinations_count' => $vaccinationsCount
        ], 200);
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
