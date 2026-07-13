<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyFormField;
use Illuminate\Http\Request;

class CompanyFormFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  int  $company_id
     * @return \Illuminate\Http\Response
     */
    public function index($company_id)
    {
        $company = Company::findOrFail($company_id);
        $label = "Form Builder: " . $company->name;
        $fields = CompanyFormField::where('company_id', $company_id)
            ->orderBy('sort_order')
            ->get();

        return view('app.admin.fields', compact('label', 'company', 'fields'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $company_id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $company_id)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|string|in:text,number,combobox,textarea',
            'options' => 'nullable|string',
        ]);

        $maxOrder = CompanyFormField::where('company_id', $company_id)->max('sort_order') ?? 0;

        CompanyFormField::create([
            'company_id' => $company_id,
            'label' => $request->label,
            'type' => $request->type,
            'options' => $request->type === 'combobox' ? $request->options : null,
            'is_required' => $request->has('is_required') ? 1 : 0,
            'sort_order' => $maxOrder + 1,
        ]);

        session()->flash('message', 'Form Field Added Successfully');
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|string|in:text,number,combobox,textarea',
            'options' => 'nullable|string',
        ]);

        $field = CompanyFormField::findOrFail($id);

        $field->update([
            'label' => $request->label,
            'type' => $request->type,
            'options' => $request->type === 'combobox' ? $request->options : null,
            'is_required' => $request->has('is_required') ? 1 : 0,
        ]);

        session()->flash('message', 'Form Field Updated Successfully');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $field = CompanyFormField::findOrFail($id);
        $field->delete();

        return response()->json(['success' => true, 'message' => 'Field deleted successfully.']);
    }
}
