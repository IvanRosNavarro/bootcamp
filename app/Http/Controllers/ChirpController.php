<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\Request;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('chirps.index', [
            //with para precargar la relación chirps<>user
            'chirps' => Chirp::with('user')->latest()->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'min: 3', 'max: 255']
        ]);

        $request->user()->chirps()->create($validated);

//        Chirp::create([
//            'message' => $request->get('message'),
//            'user_id' => auth()->id()
//        ]);

//        session()->flash('status', __('Chirp created successfully!'));

        return to_route('chirps.index')
            ->with('status', __('Chirp created successfully!'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp)
    {
//        if (auth()->user()->isNot($chirp->user)) {
//            abort(403);
//        }

        $this->authorize('update', $chirp);

        return view('chirps.edit', [
            'chirp' => $chirp
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp)
    {
//        if (auth()->user()->isNot($chirp->user)) {
//            abort(403);
//        }

        $this->authorize('update', $chirp);

        $validated = $request->validate([
            'message' => ['required', 'min: 3', 'max: 255']
        ]);

        $chirp->update($validated);

        return to_route('chirps.index')
            ->with('status', __('Chirp updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp)
    {
        $this->authorize('delete', $chirp);

        $chirp->delete();

        return to_route('chirps.index')
            ->with('status', __('Chirp deleted successfully!'));
    }
}
