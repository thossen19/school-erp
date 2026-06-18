<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('users')->where('users.school_id', 1);

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->role) {
            $roleIds = DB::table('roles')->where('name', $role)->pluck('id');
            $userIds = DB::table('model_has_roles')->whereIn('role_id', $roleIds)->pluck('model_id');
            $query->whereIn('users.id', $userIds);
        }

        if ($dateFrom = $request->date_from) {
            $query->whereDate('users.created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->date_to) {
            $query->whereDate('users.created_at', '<=', $dateTo);
        }

        $users = $query->orderBy('users.name')->paginate(20)->appends($request->query());

        $roles = Role::all()->pluck('name');

        return view('users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all()->pluck('name');
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|exists:roles,name',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['school_id'] = 1;

        $userModel = \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'school_id' => $validated['school_id'],
        ]);

        $userModel->assignRole($validated['role']);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function edit(int $id)
    {
        $user = \App\Models\User::with('roles')->findOrFail($id);
        $roles = Role::all()->pluck('name');
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|string|exists:roles,name',
        ]);

        $update = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $update['password'] = Hash::make($validated['password']);
        }

        DB::table('users')->where('id', $id)->update($update);

        $userModel = \App\Models\User::find($id);
        $userModel->syncRoles([$validated['role']]);

        return redirect()->route('users.index')->with('success', 'User updated');
    }

    public function destroy(int $id)
    {
        if ($id === 1) {
            return redirect()->route('users.index')->with('error', 'Cannot delete super admin');
        }
        $user = \App\Models\User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted');
    }
}
