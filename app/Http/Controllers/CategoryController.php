<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $data = category::latest()->paginate(10);

        return view('pages.Categories', compact('data'));
    }
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        category::create(
            [
                'name' => $request->input('name')
            ]
        );

        return redirect()->route('admin.Categories')->with('success', 'Category created successfully.');
    }
    public function edit(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $data = category::findOrFail($id);
        $data->name = $request->input('name');
        $data->save();

        return redirect()->route('admin.Categories')->with('success', 'Category updated successfully.');
    }
    public function destroy($id)
    {
        $data = category::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.Categories')->with('success', 'Category deleted successfully.');
    }
}
