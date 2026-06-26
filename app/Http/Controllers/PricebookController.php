<?php

namespace App\Http\Controllers;

use App\Models\PricebookItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PricebookController extends Controller
{
    /**
     * View the corporate pricebook and active product lines.
     */
    public function index()
    {
        $items = PricebookItem::where('company_id', Auth::user()->company_id)
            ->orderBy('category', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        return view('pricebook.index', compact('items'));
    }

    /**
     * Store new pricing arrays with profit markups.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'category'          => 'required|string|max:255',
            'unit_type'         => 'required|string|in:flat_rate,sqft,linear_ft,hourly',
            'base_unit_cost'    => 'required|numeric|min:0',
            'markup_percentage' => 'required|numeric|min:0',
            'description'       => 'nullable|string|max:1000',
        ]);

        PricebookItem::create([
            'company_id'        => Auth::user()->company_id,
            'name'              => $validated['name'],
            'category'          => $validated['category'],
            'unit_type'         => $validated['unit_type'],
            'base_unit_cost'    => $validated['base_unit_cost'],
            'markup_percentage' => $validated['markup_percentage'],
            'description'       => $validated['description'],
        ]);

        return redirect()->route('pricebook.index')->with('status', '⚡ New catalog item successfully written to your pricebook records.');
    }

    /**
     * Remove a structural pricing model row from the company partition.
     */
    public function destroy($id)
    {
        $item = PricebookItem::where('company_id', Auth::user()->company_id)->findOrFail($id);
        $item->delete();

        return redirect()->route('pricebook.index')->with('status', '🗑️ Pricebook item pulled cleanly from your service lines.');
    }
}
