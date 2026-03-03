<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin()
    {
        return $this->role === 'admin';


    }
       public function isInventory()
    {
        return $this->role === 'inventory';
    }
    public function isCashier(): bool
{
    return $this->role === 'cashier';
}
   public function roleRoute(string $name)
    {
   switch ($this->role) {
        case 'admin':
            // Admin always uses admin.* routes
            return route('admin.' . $name);

        case 'cashier':
            // Cashier uses user.* routes (dashboard, sale-orders, etc.)
            return route('user.' . $name);

        case 'inventory':
            // Inventory shares admin routes for inventory/category/units/purchase
            // so we also use admin.* here
            return route('admin.' . $name);

        default:
            abort(403, 'No route mapping for this role.');
    }
}
}
