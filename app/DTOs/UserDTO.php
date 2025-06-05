<?php

namespace App\DTOs;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class UserDTO
{
    public function __construct(
        public readonly string $auth_user_id,
        public readonly string $role_id,
        public readonly ?string $nrp,
        public readonly string $email,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $validated = $request->validate([
            'auth_user_id' => 'required|uuid',
            'role_id' => 'sometimes|uuid|exists:roles,id',
            'nrp' => 'nullable|string|max:20',
            'email' => 'required|email:rfc,dns|max:255',
        ]);

        return new self(
            auth_user_id: $validated['auth_user_id'],
            role_id: $validated['role_id'] ?? Role::default()->first()->id,
            nrp: $validated['nrp'] ?? null,
            email: $validated['email'],
        );
    }

    public static function fromQueueMessage(array $data): self
    {
        $validated = Validator::make($data, [
            'auth_user_id' => 'required|uuid',
            'role_id' => 'sometimes|uuid|exists:roles,id',
            'email' => 'required|email:rfc|max:255',
        ])->validate();

        $nrp = null;
        $roleId = $validated['role_id'] ?? null;

        if (isset($data['email'])) {
            $emailParts = explode('@', $data['email']);
            $localPart = $emailParts[0] ?? '';
            $domain = $emailParts[1] ?? '';

            // Check for specific email addresses first
            $specificEmails = config('emaildomain.specific_roles', []);
            if (isset($specificEmails[$data['email']])) {
                $roleName = $specificEmails[$data['email']];
                $role = Role::where('name', $roleName)->first();
                if ($role) {
                    $roleId = $role->id;
                }
            }
            // If no specific email match, check domain-based rules
            elseif (!$roleId) {
                $domainRoles = config('emaildomain.domain_roles', []);
                if (isset($domainRoles[$domain])) {
                    $roleName = $domainRoles[$domain];
                    $role = Role::where('name', $roleName)->first();
                    if ($role) {
                        $roleId = $role->id;
                    }
                }
            }

            // Set NRP for student domain
            if ($domain === 'student.its.ac.id') {
                $nrp = $localPart;
            }
        }

        return new self(
            auth_user_id: $validated['auth_user_id'],
            role_id: $roleId ?? Role::default()->first()->id,
            nrp: $nrp,
            email: $validated['email'],
        );
    }

    public function toArray(): array
    {
        return [
            'auth_user_id' => $this->auth_user_id,
            'role_id' => $this->role_id,
            'nrp' => $this->nrp,
            'email' => $this->email,
        ];
    }
}
