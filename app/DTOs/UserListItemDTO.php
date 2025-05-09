<?php

namespace App\DTOs;

class UserListItemDTO
{
    public string $id;
    public string $auth_user_id;
    public string $email;
    public ?string $nrp;
    public string $created_at;
    public ?string $role_name;

    public static function fromModel($user): self
    {
        $dto = new self();
        $dto->id = $user->id;
        $dto->auth_user_id = $user->auth_user_id;
        $dto->email = $user->email;
        $dto->nrp = $user->nrp;
        $dto->created_at = $user->created_at->format('Y-m-d H:i:s');
        $dto->role_name = $user->role->name ?? null;

        return $dto;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'auth_user_id' => $this->auth_user_id,
            'email' => $this->email,
            'nrp' => $this->nrp,
            'created_at' => $this->created_at,
            'role' => $this->role_name
        ];
    }
}
