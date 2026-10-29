<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdSlot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdSlotController extends Controller
{
    public function index(): View
    {
        return view('admin.ad-slots.index', ['slots' => AdSlot::orderBy('position')->orderBy('label')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        AdSlot::create($data);
        return back()->with('success', __('Ad slot created.'));
    }

    public function update(Request $request, AdSlot $adSlot): RedirectResponse
    {
        $data = $this->validateData($request, $adSlot);
        $adSlot->update($data);
        return back()->with('success', __('Ad slot updated.'));
    }

    public function destroy(AdSlot $adSlot): RedirectResponse
    {
        $adSlot->delete();
        return back()->with('success', __('Ad slot deleted.'));
    }

    private function validateData(Request $request, ?AdSlot $slot = null): array
    {
        $request->merge(['is_active' => $request->boolean('is_active')]);

        return $request->validate([
            'key' => ['required', 'alpha_dash', 'max:100', Rule::unique('ad_slots', 'key')->ignore($slot)],
            'label' => ['required', 'string', 'max:160'],
            'position' => ['required', 'string', 'max:160'],
            'type' => ['required', 'in:reserved,internal,adsense'],
            'is_active' => ['boolean'],
        ]);
    }
}
