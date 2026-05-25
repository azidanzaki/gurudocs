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
        'nip',
        'name',
        'email',
        'password',
        'role',
        'default_password',
        'is_active',
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

    public function mapels()
    {
        return $this->belongsToMany(Mapel::class, 'guru_mapel');
    }

    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'guru_kelas');
    }

    public function waliKelas()
    {
        return $this->belongsToMany(Kelas::class, 'wali_kelas');
    }

    // Kelas for a specific mapel
    public function kelasForMapel(int $mapelId)
    {
        // Returns kelas IDs that are linked to both this teacher AND this mapel
        // via the guru_kelas pivot. You may need to adjust based on your pivot structure.
        return $this->kelas()->get();
    }

    public function repositories()
    {
        return $this->hasMany(Repository::class);
    }

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
            'is_active' => 'boolean',
        ];
    }
}
