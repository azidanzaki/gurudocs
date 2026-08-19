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
        return $this->belongsToMany(Mapel::class, 'guru_mapel_kelas', 'user_id', 'mapel_id')->distinct();
    }

    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'guru_mapel_kelas', 'user_id', 'kelas_id')->distinct();
    }


    // Kelas for a specific mapel
    public function kelasForMapel(int $mapelId)
    {
        return $this->belongsToMany(Kelas::class, 'guru_mapel_kelas', 'user_id', 'kelas_id')
                    ->wherePivot('mapel_id', $mapelId)
                    ->get();
    }

    public function mengajar()
    {
        return \Illuminate\Support\Facades\DB::table('guru_mapel_kelas')->where('user_id', $this->id)->get();
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

    public function adminlte_image()
    {
        // Modern UI avatars generator
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random&color=fff';
    }

    public function adminlte_desc()
    {
        return 'Role: ' . ucfirst($this->role);
    }

    public function adminlte_profile_url()
    {
        return route('profile');
    }
}
