<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConsultationCollection;
use App\Models\Consultation;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consultations = Consultation::all();
        return new ConsultationCollection($consultations);
    }


    /**
     * Display the specified resource.
     */
    public function show(Consultation $consultation)
    {
        $users = $consultation->users()->paginate();

        return response()->json(
            [
                $consultation->type,
                $users
            ]);
    }


}
