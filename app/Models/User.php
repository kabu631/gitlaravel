<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'is_admin'];
    protected $hidden   = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_admin'          => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin === true || $this->roles()->exists();
    }

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user')->withTimestamps();
    }

    public function isSuperAdmin(): bool
    {
        return $this->is_admin === true || $this->hasRole('super_admin');
    }

    public function hasRole(string|array $roles): bool
    {
        $roleList = is_array($roles) ? $roles : func_get_args();

        return $this->roles->contains(function ($role) use ($roleList) {
            return in_array($role->slug, $roleList, true) || in_array($role->name, $roleList, true);
        });
    }

    public function getAllowedSystemModuleIds(): \Illuminate\Support\Collection
    {
        if ($this->isSuperAdmin()) {
            return SystemModule::pluck('id');
        }

        return $this->roles()
            ->with('systemModules:id')
            ->get()
            ->pluck('systemModules')
            ->flatten()
            ->pluck('id')
            ->unique()
            ->values();
    }

    public function hasPermissionToModule(int|string|SystemModule $module): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $allowedIds = $this->getAllowedSystemModuleIds();

        if ($module instanceof SystemModule) {
            return $allowedIds->contains($module->id);
        }

        if (is_numeric($module)) {
            return $allowedIds->contains((int) $module);
        }

        // Match by code or route
        $targetModule = SystemModule::where('code', $module)->first();
        if ($targetModule) {
            return $allowedIds->contains($targetModule->id);
        }

        return false;
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function wishlist()
    {
        return $this->belongsToMany(Gadget::class, 'wishlists');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function comments()
    {
        return $this->hasMany(UserComment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
