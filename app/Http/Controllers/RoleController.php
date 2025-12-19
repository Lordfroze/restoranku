<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // menampilkan role dari database
        $roles = Role::all();
        return view('admin.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // menampilkan halaman create role
        return view('admin.role.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // menyimpan role ke database
        $request->validate([
            'role_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        Role::create($request->all());
        return redirect()->route('roles.index')->with('success', 'Role created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // mengambil role berdasarkan id
        $role = Role::findOrFail($id);
        // menampilkan halaman edit role
        return view('admin.role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // memvalidasi request
        $request->validate([
            'role_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);
        // mengambil role berdasarkan id
        $role = Role::findOrFail($id);
        // mengupdate role ke database
        $role->update($request->all());
        // mengembalikan ke halaman index role dengan pesan sukses
        return redirect()->route('roles.index')->with('success', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // mengambil role berdasarkan id
        $role = Role::findOrFail($id);
        // menghapus role dari database
        $role->delete();
        // mengembalikan ke halaman index role dengan pesan sukses
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
    }
}
