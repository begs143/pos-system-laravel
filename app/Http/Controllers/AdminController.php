<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function userRole(Request $request)
    {

        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");
            });
        }
        $users = $query->orderBy('id', 'desc')->paginate(10);

        return view('pages.user.user-role', compact('users'));
    }

    public function create()
    {
        return view('pages.user.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'nullable|email|max:255',
            'role' => 'required|in:admin,user',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            User::create([
                'name' => $validated['name'],
                'username' => $validated['username'],
                'email' => $validated['email'] ?? null,
                'role' => $validated['role'],
                'password' => Hash::make($validated['password']),
            ]);

            return redirect()
                ->route('admin.user-role')
                ->with('success', 'User created successfully.');

        } catch (\Exception $e) {
            // Log the error
            \Log::error('User creation failed: '.$e->getMessage());

            // Show friendly error
            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong while creating the user.');
        }
    }

    public function destroy($id)
    {
        try {

            $user = User::findOrFail($id);
            if ($user->id === auth()->id()) {
                return redirect()->back()->with('error', 'You cannot delete your own account.');
            }

            // Delete the user
            $user->delete();

            return redirect()->back()->with('success', 'User deleted successfully.');

        } catch (\Exception $e) {

            \Log::error('User deletion failed: '.$e->getMessage());

            // Redirect back with friendly error message
            return redirect()->back()->with('error', 'Something went wrong while deleting the user.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Find the user
            $user = User::findOrFail($id);

            // Validation
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|unique:users,email,'.$user->id,
                'username' => 'required|string|max:255|unique:users,username,'.$user->id,
                'role' => 'required|in:admin,user',
                'password' => 'nullable|string|min:8|confirmed',
            ]);

            // Update user data
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->role = $request->role;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return redirect()->route('admin.user-role')->with('success', 'User updated successfully.');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('User update failed: '.$e->getMessage());

            // Redirect back with input and friendly error message
            return redirect()->back()->withInput()->with('error', 'Something went wrong while updating the user.');
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('pages.user.edit', compact('user'));
    }
}
