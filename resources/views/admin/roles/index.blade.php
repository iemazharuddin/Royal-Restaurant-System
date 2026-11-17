
@extends('layouts.admin')

@section('title', 'Roles')
@section('page-heading', 'Roles Management')

@push('styles')
<style>
    .role-mini-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 13px 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        min-width: 0;
        box-shadow: 0 3px 12px rgba(17, 28, 24, .04);
    }

    .role-mini-icon {
        width: 36px;
        height: 36px;
        flex-shrink: 0;
        border-radius: 11px;
        display: grid;
        place-items: center;
        color: white;
        font-size: 13px;
    }

    .role-select {
        width: 100%;
        min-height: 39px;
        padding: 0 12px;
        border: 1px solid var(--border);
        border-radius: 11px;
        background: var(--surface);
        color: var(--text);
        font-size: 12px;
        outline: none;
    }

    .role-select:focus {
        border-color: var(--gold);
        box-shadow: 0 0 0 3px rgba(214,173,105,.16);
    }

    .permission-table th {
        padding: 12px 10px;
        color: var(--muted);
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .05em;
        white-space: nowrap;
    }

    .permission-table td {
        padding: 10px;
        border-top: 1px solid var(--border);
        font-size: 12px;
    }

    .permission-table tbody tr:hover {
        background: rgba(214,173,105,.055);
    }

    .permission-switch {
        appearance: none;
        -webkit-appearance: none;
        width: 34px;
        height: 19px;
        border-radius: 20px;
        background: #aab2ad;
        position: relative;
        cursor: pointer;
        transition: .2s ease;
        vertical-align: middle;
    }

    .permission-switch::before {
        content: '';
        position: absolute;
        width: 13px;
        height: 13px;
        left: 3px;
        top: 3px;
        background: white;
        border-radius: 50%;
        transition: .2s ease;
    }

    .permission-switch:checked {
        background: linear-gradient(135deg,#e6c783,#a17b41);
    }

    .permission-switch:checked::before {
        transform: translateX(15px);
    }

    .permission-switch:disabled {
        opacity: .4;
        cursor: not-allowed;
    }

    .role-submit-btn {
        min-height: 36px;
        padding: 0 17px;
        border-radius: 10px;
        color: #17251e;
        background: linear-gradient(135deg,#efd39a,#c49a55);
        font-size: 11px;
        font-weight: 800;
        box-shadow: 0 4px 12px rgba(161,123,65,.2);
        transition: .2s ease;
    }

    .role-submit-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(161,123,65,.28);
    }

    .role-submit-btn:disabled {
        opacity: .55;
        cursor: not-allowed;
        transform: none;
    }

    .role-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 200;
        width: min(340px, calc(100% - 32px));
        padding: 13px 15px;
        border-radius: 13px;
        background: var(--surface);
        color: var(--text);
        border: 1px solid var(--border);
        box-shadow: 0 14px 35px rgba(0,0,0,.17);
        display: flex;
        align-items: center;
        gap: 11px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-8px);
        transition: .22s ease;
    }

    .role-notification.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .role-notification-icon {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }

    .role-notification.success .role-notification-icon {
        color: #16a34a;
        background: rgba(34,197,94,.12);
    }

    .role-notification.error .role-notification-icon {
        color: #dc3545;
        background: rgba(239,68,68,.12);
    }

    @media(max-width: 640px) {
        .role-notification {
            top: 12px;
            right: 12px;
        }
    }
</style>
@endpush

@section('content')

@php
    $actions = ['create', 'view', 'show', 'edit', 'delete'];
    $permissionRows = [];

    foreach ($permissions as $permission) {
        $parts = explode('.', $permission->name);

        if (count($parts) < 2) {
            continue;
        }

        $action = strtolower(array_pop($parts));
        $resource = implode('.', $parts);

        if (in_array($action, $actions, true)) {
            $permissionRows[$resource][$action] = $permission;
        }
    }

    ksort($permissionRows);

    $totalRoles = $roles->count();
    $totalPermissions = $permissions->count();
@endphp

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
    <div>
        <p class="text-[10px] gold font-bold uppercase tracking-widest">
            Management
        </p>

        <h1 class="text-xl sm:text-2xl font-extrabold mt-1"
            style="font-family:'Playfair Display',serif;">
            Roles & Permissions
        </h1>

        <p class="text-[11px] muted mt-1">
            Manage roles and control access to system features.
        </p>
    </div>
</div>

<div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 mb-4">

    <div class="role-mini-card">
        <div class="min-w-0">
            <p class="text-[10px] muted font-bold uppercase">Total Roles</p>
            <h3 class="text-xl font-extrabold mt-1">{{ $totalRoles }}</h3>
        </div>
        <div class="role-mini-icon"
             style="background:linear-gradient(145deg,#efd39a,#a17b41)">
            <i class="fas fa-user-shield"></i>
        </div>
    </div>

    <div class="role-mini-card">
        <div class="min-w-0">
            <p class="text-[10px] muted font-bold uppercase">Permissions</p>
            <h3 class="text-xl font-extrabold mt-1">{{ $totalPermissions }}</h3>
        </div>
        <div class="role-mini-icon"
             style="background:linear-gradient(145deg,#60a5fa,#2563eb)">
            <i class="fas fa-key"></i>
        </div>
    </div>

    <div class="role-mini-card col-span-2 sm:col-span-1">
        <div class="min-w-0">
            <p class="text-[10px] muted font-bold uppercase">Modules</p>
            <h3 class="text-xl font-extrabold mt-1">{{ count($permissionRows) }}</h3>
        </div>
        <div class="role-mini-icon"
             style="background:linear-gradient(145deg,#5fe0b0,#17966e)">
            <i class="fas fa-layer-group"></i>
        </div>
    </div>

</div>

<div class="surface p-3 sm:p-4 mb-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h3 class="font-bold text-sm">Select Role</h3>
            <p class="text-[10px] muted mt-0.5">
                Choose a role to manage its permissions.
            </p>
        </div>

        <div class="w-full sm:w-64">
            <select id="roleSelect" class="role-select">
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="surface overflow-hidden">

    <div class="p-3 sm:p-4 border-b flex items-center justify-between gap-2"
         style="border-color:var(--border)">
        <div>
            <h3 class="font-bold text-sm">Permissions</h3>
            <p class="text-[10px] muted mt-0.5">
                Enable or disable each permission.
            </p>
        </div>

        <span class="text-[10px] gold font-bold">
            {{ count($permissionRows) }} Modules
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="permission-table admin-table w-full text-left min-w-[650px]">
            <thead>
                <tr>
                    <th class="px-4">Name</th>
                    <th class="text-center">Create</th>
                    <th class="text-center">View</th>
                    <th class="text-center">Show</th>
                    <th class="text-center">Edit</th>
                    <th class="text-center">Delete</th>
                </tr>
            </thead>

            <tbody>
                @forelse($permissionRows as $resource => $row)
                    <tr>
                        <td class="px-4">
                            <span class="font-bold capitalize">
                                {{ str_replace(['-', '_'], ' ', $resource) }}
                            </span>
                        </td>

                        @foreach($actions as $action)
                            <td class="text-center">
                                @if(isset($row[$action]))
                                    <input
                                        type="checkbox"
                                        class="permission-switch permission-toggle"
                                        data-permission="{{ $row[$action]->name }}"
                                        aria-label="{{ $resource }} {{ $action }}"
                                    >
                                @else
                                    <span class="muted">—</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 muted">
                            <i class="fas fa-key text-xl mb-2 block"></i>
                            No permissions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-3 sm:p-4 border-t flex items-center justify-between gap-3"
         style="border-color:var(--border)">
        <p id="roleProtectionText" class="text-[10px] muted">
            Changes will be saved for the selected role.
        </p>

        <button type="button" id="savePermissions" class="role-submit-btn">
            <i class="fas fa-save mr-1"></i>
            Submit
        </button>
    </div>

</div>

<div id="roleNotification" class="role-notification" role="status" aria-live="polite">
    <div class="role-notification-icon" id="roleNotificationIcon">
        <i class="fas fa-check"></i>
    </div>
    <div class="min-w-0">
        <p id="roleNotificationTitle" class="font-bold text-xs">Success</p>
        <p id="roleNotificationMessage" class="text-[10px] muted mt-0.5">
            Permissions saved successfully.
        </p>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const roles = @json($roles->map(function ($role) {
        return [
            'id' => $role->id,
            'name' => $role->name,
            'permissions' => $role->permissions->pluck('name')->values()
        ];
    })->values());

    const roleSelect = document.getElementById('roleSelect');
    const saveButton = document.getElementById('savePermissions');
    const protectionText = document.getElementById('roleProtectionText');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

    const toggleUrlTemplate = @json(route('admin.roles.permissions.toggle', ['role' => '__ROLE__']));

    let notificationTimer;
    let isSaving = false;

    function notify(message, type = 'success') {
        const notification = document.getElementById('roleNotification');
        const icon = document.getElementById('roleNotificationIcon');

        notification.className = 'role-notification ' + type;

        icon.innerHTML = type === 'success'
            ? '<i class="fas fa-check"></i>'
            : '<i class="fas fa-exclamation-triangle"></i>';

        document.getElementById('roleNotificationTitle').textContent =
            type === 'success' ? 'Success' : 'Error';

        document.getElementById('roleNotificationMessage').textContent = message;

        notification.classList.add('show');

        clearTimeout(notificationTimer);

        notificationTimer = setTimeout(() => {
            notification.classList.remove('show');
        }, 5000);
    }

    function getSelectedRole() {
        return roles.find(role =>
            String(role.id) === String(roleSelect.value)
        );
    }

    function updateRolePermissions() {
        const role = getSelectedRole();
        const isSuperAdmin = role?.name?.toLowerCase() === 'super admin';

        document.querySelectorAll('.permission-toggle').forEach(toggle => {
            toggle.checked = role
                ? role.permissions.includes(toggle.dataset.permission)
                : false;

            toggle.disabled = isSaving || !role || isSuperAdmin;
        });

        saveButton.disabled = isSaving || !role || isSuperAdmin;

        protectionText.textContent = isSuperAdmin
            ? 'Super Admin permissions are protected.'
            : 'Changes will be saved for the selected role.';
    }

    roleSelect.addEventListener('change', updateRolePermissions);

    saveButton.addEventListener('click', async function () {

        const role = getSelectedRole();

        if (isSaving) return;

        if (!role || role.name.toLowerCase() === 'super admin') {
            notify('Super Admin permissions cannot be changed.', 'error');
            return;
        }

        if (!csrf) {
            notify('CSRF token missing. Check your admin layout.', 'error');
            return;
        }

        const toggles = [...document.querySelectorAll('.permission-toggle')];

        const changes = toggles
            .map(toggle => ({
                toggle: toggle,
                permission: toggle.dataset.permission,
                enabled: toggle.checked,
                wasEnabled: role.permissions.includes(toggle.dataset.permission)
            }))
            .filter(item => item.enabled !== item.wasEnabled);

        if (changes.length === 0) {
            notify('No permission changes to save.', 'success');
            return;
        }

        isSaving = true;
        updateRolePermissions();

        const originalText = saveButton.innerHTML;

        saveButton.innerHTML =
            '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...';

        const roleId = role.id;
        let savedCount = 0;

        try {

            for (const item of changes) {

                const url = toggleUrlTemplate.replace(
                    '__ROLE__',
                    encodeURIComponent(roleId)
                );

                const response = await fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        permission: item.permission,
                        enabled: item.enabled
                    })
                });

                let responseData = {};

                try {
                    responseData = await response.json();
                } catch (e) {
                    responseData = {};
                }

                if (!response.ok) {

                    let message = responseData.message ||
                        'Request failed. HTTP ' + response.status;

                    if (responseData.errors) {
                        message += ' ' + Object.values(responseData.errors)
                            .flat()
                            .join(' ');
                    }

                    throw new Error(
                        item.permission + ': ' + message
                    );
                }

                if (!responseData.success) {
                    throw new Error(
                        responseData.message ||
                        'Permission update failed: ' + item.permission
                    );
                }

                if (item.enabled) {
                    if (!role.permissions.includes(item.permission)) {
                        role.permissions.push(item.permission);
                    }
                } else {
                    role.permissions = role.permissions.filter(
                        permission => permission !== item.permission
                    );
                }

                savedCount++;
            }

            notify(
                savedCount + ' permission(s) updated successfully.',
                'success'
            );

        } catch (error) {

            console.error('Permission update error:', error);

            notify(
                error.message || 'Something went wrong.',
                'error'
            );

        } finally {

            isSaving = false;
            saveButton.innerHTML = originalText;
            updateRolePermissions();
        }
    });

    updateRolePermissions();
});
</script>
@endpush
