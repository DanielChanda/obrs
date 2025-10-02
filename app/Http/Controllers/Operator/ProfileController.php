<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $operator = Auth::user();
        return view('operator.profile.index', compact('operator'));
    }

    public function update(Request $request)
    {
        $operator = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|confirmed|min:8',
        ]);

        $operator->name = $request->name;
        $operator->phone = $request->phone;

        if ($request->filled('password')) {
            $operator->password = Hash::make($request->password);
        }

        $operator->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
