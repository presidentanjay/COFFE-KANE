<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // Menampilkan daftar role dengan pagination
    public function index()
    {
        $roles = Role::paginate(10);  // Ganti all() ke paginate()
        return view('admin.roles.index', compact('roles'));
    }

    // Menampilkan form untuk membuat role baru
    public function create()
    {
        return view('admin.roles.create');
    }

    // Menyimpan role baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
        ]);

        Role::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    // Menampilkan form untuk mengedit role
    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    // Update role
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    // Hapus role
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully.');
    }
}
