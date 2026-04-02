<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAuditor(): bool
    {
        return in_array($this->role, ['admin', 'auditor']);
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin'   => 'Administrador',
            'auditor' => 'Auditor',
            default   => 'Usuário',
        };
    }

    public function getRoleColorAttribute(): string
    {
        return match($this->role) {
            'admin'   => 'danger',
            'auditor' => 'warning',
            default   => 'secondary',
        };
    }
}
