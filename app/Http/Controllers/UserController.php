<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $users  = User::with('level')
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%"))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users', 'search'));
    }

    public function create()
    {
        $levels = Level::whereIn('id', [2, 3])->get();
        return view('users.create', compact('levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_level' => 'required|in:2,3',
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ], [
            'id_level.in' => 'Level yang diperbolehkan hanya Operator atau Pimpinan.',
        ]);

        User::create([
            'id_level' => $request->id_level,
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $levels = Level::whereIn('id', [2, 3])
            ->when($user->id_level == 1, fn($q) => $q->orWhere('id', 1))
            ->get();

        return view('users.edit', compact('user', 'levels'));
    }

    public function update(Request $request, User $user)
    {
        $allowedLevels = [2, 3];
        if ($user->id_level == 1) {
            $allowedLevels[] = 1;
        }

        $request->validate([
            'id_level' => ['required', Rule::in($allowedLevels)],
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
        ], [
            'id_level.in' => 'Level yang diperbolehkan hanya Operator atau Pimpinan.',
        ]);

        $data = [
            'id_level' => $request->id_level,
            'name'     => $request->name,
            'email'    => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
