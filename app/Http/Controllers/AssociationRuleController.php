<?php

namespace App\Http\Controllers;

use App\Models\AssociationRule;
use Illuminate\Http\Request;

class AssociationRuleController extends Controller
{
    public function index()
    {
        $rules = AssociationRule::orderBy('confidence', 'desc')->paginate(10);
        return view('association_rules.index', compact('rules'));
    }

    public function update(Request $request, AssociationRule $associationRule)
    {
        $associationRule->update([
            'is_active' => $request->input('is_active'),
        ]);

        return redirect()->route('association-rules.index')->with('success', 'Status promo bundling berhasil diperbarui.');
    }

    public function activate($id)
    {
        $rule = AssociationRule::findOrFail($id);
        $rule->is_active = true;
        $rule->save();

        return redirect()->back()->with('success', 'Promo berhasil diaktifkan.');
    }

    public function deactivate($id)
    {
        $rule = AssociationRule::findOrFail($id);
        $rule->is_active = false;
        $rule->save();

        return redirect()->back()->with('success', 'Promo berhasil dinonaktifkan.');
    }

    public function updateDiscount(Request $request, $id)
    {
        $request->validate([
            'discount_percent' => 'required|numeric|min:0|max:100',
        ]);

        $rule = AssociationRule::findOrFail($id);
        $rule->discount_percent = $request->discount_percent;
        $rule->save();

        return redirect()->back()->with('success', 'Diskon berhasil diperbarui.');
    }
}
