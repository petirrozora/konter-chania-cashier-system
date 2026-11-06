<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = ['name','username','password','role','is_active'];
    protected $hidden = ['password','remember_token'];
    protected $casts = ['is_active'=>'boolean'];
    public function isOwner(): bool { return $this->role === 'owner'; }
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isEmployee(): bool { return $this->role === 'employee'; }
    public function isStaff(): bool { return in_array($this->role, ['admin','employee'], true); }
}
