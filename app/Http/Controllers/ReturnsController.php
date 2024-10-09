<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Returns;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnsController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $returns = Returns::all();
        return response()->json($returns);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'loan_id' => 'numeric|required',
            'return_date' => 'date|required'
        ]);

        $loanId = $request->input('loan_id');

        DB::transaction(function () use ($loanId, $request) {
            $loan = Loan::findOrFail($loanId);

            $return_date = new DateTime($request->return_date);
            $due_date = new DateTime($loan->due_date);
            
            $selisih = $return_date->diff($due_date);
            $hari = $selisih->invert ? $selisih->days : 0;

            if ($hari > 0) {
                $denda = $hari * 5000;
            } else {
                $denda = 0;
            }
            $returns = Returns::create([
                'loan_id' => $request->loan_id,
                'return_date' => $request->return_date,
                'fine' => $denda
            ]);

            return response()->json($returns, 201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Returns $returns)
    {
        return response()->json($returns);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Returns $returns)
    {
        $request->validate([
            'loan_id' => 'numeric|required',
            'return_date' => 'date|required',
            'fine' => 'numeric|required'
        ]);

        $returns->update([
            'loan_id' => $request->loan_id,
            'return_date' => $request->return_date,
            'fine' => $request->fine
        ]);

        return response()->json($returns);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Returns $returns)
    {
        $returns->delete();
        return response()->json(null, 204);
    }
}
