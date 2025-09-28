<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
        $data = Admin::latest()->paginate(10);
        return view('pages.Admins', compact('data'));
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        Admin::create(['name_ar' => $request->input('name_ar'),
            'name_en' => $request->input('name_en'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))]);

        return redirect()->route('admin.Admins')->with('success', 'Admins created successfully.');
    }

    public function edit(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|max:15'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        $data = Admin::findOrFail($id);
        $data->update([
            'name_ar' => $request->input('name_ar'),
            'name_en' => $request->input('name_en'),
            'email' => $request->input('email')
        ] + ($request->input('password') ? ['password' => $request->input('password')] : []));

        return redirect()->route('admin.Admins')->with('success', 'Admins updated successfully.');
    }

    public function destroy($id)
    {
        $data = Admin::findOrFail($id);
        $data->delete();

        return redirect()->route('admin.Admins')->with('success', 'User deleted successfully.');
    }

    /**
     * Assign roles to an admin user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assignRole(Request $request, $id)
    {
        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name'
        ]);

        $admin = Admin::findOrFail($id);
        
        // Sync all selected roles (replace existing roles with the new selection)
        $admin->syncRoles($request->roles);

        return redirect()
            ->route('admin.Admins')
            ->with('success', 'Roles updated successfully.');
    }
}
