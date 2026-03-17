<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Region;
use App\Models\Department;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(protected ActivityLogService $activityLogService) {}

    public function index(Request $request)
    {
        $users = User::with(['region', 'department', 'roles'])
            ->when(
                $request->search,
                fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
                    ->orWhere('employee_id', 'like', "%{$request->search}%")
            )
            ->when($request->role, fn($q) => $q->role($request->role))
            ->when($request->region_id, fn($q) => $q->where('region_id', $request->region_id))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $roles   = Role::orderBy('name')->get();
        $regions = Region::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles', 'regions'));
    }

    public function create()
    {
        $roles   = Role::orderBy('name')->get();
        $regions = Region::orderBy('name')->get();

        return view('admin.users.create', compact('roles', 'regions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'unique:users,email'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
            'employee_id'   => ['nullable', 'string', 'unique:users,employee_id'],
            'phone'         => ['nullable', 'string'],
            'role'          => ['required', 'exists:roles,name'],
            'region_id'     => ['nullable', 'exists:regions,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
        ]);

        $user = User::create([
            'name'          => $data['name'],
            'email'         => $data['email'],
            'password'      => Hash::make($data['password']),
            'employee_id'   => $data['employee_id'] ?? null,
            'phone'         => $data['phone'] ?? null,
            'region_id'     => $data['region_id'] ?? null,
            'department_id' => $data['department_id'] ?? null,
        ]);

        $user->assignRole($data['role']);

        $this->activityLogService->log(
            action: 'created',
            subject: $user,
            description: "User {$user->name} ditambahkan oleh " . Auth::user()->name,
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User {$user->name} berhasil ditambahkan.");
    }

    public function show(User $user)
    {
        $user->load(['region', 'department', 'roles', 'driver']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles       = Role::orderBy('name')->get();
        $regions     = Region::orderBy('name')->get();
        $departments = Department::where('region_id', $user->region_id)->orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles', 'regions', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'unique:users,email,' . $user->id],
            'password'      => ['nullable', 'string', 'min:8', 'confirmed'],
            'employee_id'   => ['nullable', 'string', 'unique:users,employee_id,' . $user->id],
            'phone'         => ['nullable', 'string'],
            'role'          => ['required', 'exists:roles,name'],
            'region_id'     => ['nullable', 'exists:regions,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'is_active'     => ['boolean'],
        ]);

        $updateData = [
            'name'          => $data['name'],
            'email'         => $data['email'],
            'employee_id'   => $data['employee_id'] ?? null,
            'phone'         => $data['phone'] ?? null,
            'region_id'     => $data['region_id'] ?? null,
            'department_id' => $data['department_id'] ?? null,
            'is_active'     => $request->boolean('is_active'),
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);
        $user->syncRoles([$data['role']]);

        $this->activityLogService->log(
            action: 'updated',
            subject: $user,
            description: "User {$user->name} diupdate oleh " . Auth::user()->name,
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User {$user->name} berhasil diupdate.");
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $name = $user->name;
        $user->update(['is_active' => false]);

        $this->activityLogService->log(
            action: 'deactivated',
            subject: $user,
            description: "User {$name} dinonaktifkan oleh " . Auth::user()->name,
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User {$name} berhasil dinonaktifkan.");
    }

    // AJAX — ambil department berdasarkan region
    public function getDepartments(Request $request)
    {
        $departments = Department::where('region_id', $request->region_id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($departments);
    }
}
