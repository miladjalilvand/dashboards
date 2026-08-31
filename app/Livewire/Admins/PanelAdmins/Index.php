<?php

namespace App\Livewire\Admins\PanelAdmins;

use App\Models\Admin;
use App\Models\Panel;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    */

    public array $admins = [];

    public array $permissions = [];

    public array $selectedPermissions = [];


    /*
    |--------------------------------------------------------------------------
    | Dialogs
    |--------------------------------------------------------------------------
    */

    public bool $showAdminDialog = false;

    public bool $showPermissionDialog = false;


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public ?int $editingId = null;

    public ?int $permissionAdminId = null;


    /*
    |--------------------------------------------------------------------------
    | Form
    |--------------------------------------------------------------------------
    */

    public string $name = '';

    public string $email = '';

    public string $mobile_number = '';

    public string $password = '';

    public bool $is_active = true;


    /*
    |--------------------------------------------------------------------------
    | Mount
    |--------------------------------------------------------------------------
    */

    public function mount()
    {
        $this->loadData();

        $this->permissions = Permission::query()
            ->orderBy('id')
            ->get()
            ->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name ?? '',
                    'caption' => $permission->caption ?? $permission->name ?? '',
                ];
            })
            ->values()
            ->toArray();
    }


    /*
    |--------------------------------------------------------------------------
    | Get Current Panel
    |--------------------------------------------------------------------------
    */

    private function getPanel()
    {
        return Auth::user()
            ->panels()
            ->where('dashboard_id', 1)
            ->firstOrFail();
    }


    /*
    |--------------------------------------------------------------------------
    | Load Admins
    |--------------------------------------------------------------------------
    */

    private function loadData()
    {
        $panel = $this->getPanel();

        $this->admins = Admin::query()
            ->where('panel_id', $panel->id)->where('role_id' , 2)
            ->with('user')
            ->latest()
            ->get()
            ->map(function ($admin) {

                return [
                    'id' => $admin->id,

                    'user_id' => $admin->user_id,

                    'name' => $admin->user?->name ?? '',

                    'email' => $admin->user?->email ?? '',

                    'mobile_number' => $admin->user?->mobile_number ?? '',

                    'is_active' => (bool)$admin->is_active,

                    'created_at' => $admin->created_at?->format('Y/m/d'),
                ];

            })
            ->values()
            ->toArray();
    }


    /*
    |--------------------------------------------------------------------------
    | Open Create Dialog
    |--------------------------------------------------------------------------
    */

    public function openCreateDialog()
    {
        $this->resetValidation();

        $this->reset([
            'editingId',
            'name',
            'email',
            'mobile_number',
            'password',
        ]);

        $this->is_active = true;

        $this->showAdminDialog = true;
    }


    /*
    |--------------------------------------------------------------------------
    | Open Edit Dialog
    |--------------------------------------------------------------------------
    */

    public function openEditDialog(int $id)
    {
        $this->resetValidation();

        $panel = $this->getPanel();

        $admin = Admin::query()
            ->where('panel_id', $panel->id)
            ->with('user')
            ->findOrFail($id);

        $this->editingId = $admin->id;

        $this->name = $admin->user?->name ?? '';

        $this->email = $admin->user?->email ?? '';

        $this->mobile_number = $admin->user?->mobile_number ?? '';

        $this->password = '';

        $this->is_active = (bool)$admin->is_active;

        $this->showAdminDialog = true;
    }


    /*
    |--------------------------------------------------------------------------
    | Close Admin Dialog
    |--------------------------------------------------------------------------
    */

    public function closeAdminDialog()
    {
        $this->showAdminDialog = false;

        $this->reset([
            'editingId',
            'name',
            'email',
            'mobile_number',
            'password',
            'is_active',
        ]);

        $this->resetValidation();
    }


    /*
    |--------------------------------------------------------------------------
    | Save Admin
    |--------------------------------------------------------------------------
    */

    public function saveAdmin()
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'mobile_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'is_active' => [
                'boolean',
            ],
        ];


        /*
        |--------------------------------------------------------------------------
        | Password
        |--------------------------------------------------------------------------
        */

        if ($this->editingId === null) {

            $rules['password'] = [
                'required',
                'string',
                'min:8',
            ];

        } else {

            $rules['password'] = [
                'nullable',
                'string',
                'min:8',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Email Validation
        |--------------------------------------------------------------------------
        */

        if ($this->editingId === null) {

            $rules['email'][] = 'unique:users,email';

        } else {

            $admin = Admin::query()
                ->where('panel_id', $this->getPanel()->id)
                ->with('user')
                ->findOrFail($this->editingId);

            $rules['email'][] =
                'unique:users,email,' . $admin->user_id;
        }


        $this->validate($rules);


        /*
        |--------------------------------------------------------------------------
        | Current Panel
        |--------------------------------------------------------------------------
        */

        $panel = $this->getPanel();


        DB::transaction(function () use ($panel) {

            /*
            |--------------------------------------------------------------------------
            | Create
            |--------------------------------------------------------------------------
            */

            if ($this->editingId === null) {

                $user = User::create([
                    'name' => $this->name,

                    'email' => $this->email,

                    'mobile_number' => $this->mobile_number ?: null,

                    'password' => Hash::make($this->password),

                    'role_id' => 2,

                    'type' => 'admin',
                ]);


                Admin::create([
                    'user_id' => $user->id,

                    'panel_id' => $panel->id,

                    'role_id' => 2,

                    'is_active' => $this->is_active ? 1 : 0,
                ]);

                Panel::create([
                    'user_id' => Auth::id(),
                    'website' => '',
                    'expired_date' => now(),
                    'dashboard_id' =>1,
                ]);
            } /*
            |--------------------------------------------------------------------------
            | Update
            |--------------------------------------------------------------------------
            */

            else {

                $admin = Admin::query()
                    ->where('panel_id', $panel->id)
                    ->with('user')
                    ->findOrFail($this->editingId);


                $user = $admin->user;


                $user->name = $this->name;

                $user->email = $this->email;

                $user->mobile_number =
                    $this->mobile_number ?: null;


                if ($this->password) {

                    $user->password =
                        Hash::make($this->password);
                }


                $user->save();


                $admin->is_active =
                    $this->is_active ? 1 : 0;

                $admin->save();
            }
        });


        $this->loadData();

        $this->closeAdminDialog();
    }


    /*
    |--------------------------------------------------------------------------
    | Toggle Active
    |--------------------------------------------------------------------------
    */

    public function toggleActive(int $id)
    {
        $panel = $this->getPanel();

        $admin = Admin::query()
            ->where('panel_id', $panel->id)
            ->findOrFail($id);

        $admin->is_active =
            $admin->is_active ? 0 : 1;

        $admin->save();

        $this->loadData();
    }


    /*
    |--------------------------------------------------------------------------
    | Open Permission Dialog
    |--------------------------------------------------------------------------
    */

    public function openPermissionDialog(int $id)
    {
        $this->resetValidation();

        $panel = $this->getPanel();

        $admin = Admin::query()
            ->where('panel_id', $panel->id)
            ->with('user')
            ->findOrFail($id);

        $this->permissionAdminId = $admin->id;


        /*
        |--------------------------------------------------------------------------
        | Current Permissions
        |--------------------------------------------------------------------------
        */

        $this->selectedPermissions = UserPermission::query()
            ->where('user_id', $admin->user_id)
            ->pluck('permission_id')
            ->map(fn($id) => (string)$id)
            ->toArray();


        $this->showPermissionDialog = true;
    }


    /*
    |--------------------------------------------------------------------------
    | Close Permission Dialog
    |--------------------------------------------------------------------------
    */

    public function closePermissionDialog()
    {
        $this->showPermissionDialog = false;

        $this->permissionAdminId = null;

        $this->selectedPermissions = [];
    }


    /*
    |--------------------------------------------------------------------------
    | Save Permissions
    |--------------------------------------------------------------------------
    */

    public function savePermissions()
    {
        $panel = $this->getPanel();

        $admin = Admin::query()
            ->where('panel_id', $panel->id)
            ->findOrFail($this->permissionAdminId);


        /*
        |--------------------------------------------------------------------------
        | Delete Old Permissions
        |--------------------------------------------------------------------------
        */

        UserPermission::query()
            ->where('user_id', $admin->user_id)
            ->delete();


        /*
        |--------------------------------------------------------------------------
        | Insert New Permissions
        |--------------------------------------------------------------------------
        */

        foreach ($this->selectedPermissions as $permissionId) {

            UserPermission::create([
                'user_id' => $admin->user_id,

                'permission_id' => (int)$permissionId,
            ]);
        }


        $this->closePermissionDialog();
    }


    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view(
            'livewire.admins.panel_admins'
        );
    }
}
