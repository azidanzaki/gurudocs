<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerangkatGuru extends Model
{
    protected $fillable = [
        'user_id', 'mapel_id', 'kelas_id', 'perangkat_template_id',
        'tahun_ajaran', 'semester', 'status', 'submitted_at',
        'tahun', 'is_completed',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'is_completed' => 'boolean',
    ];

    public function guru()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function template()
    {
        return $this->belongsTo(PerangkatTemplate::class, 'perangkat_template_id');
    }

    public function filledSections()
    {
        return $this->hasMany(PerangkatGuruSection::class);
    }

    public function isDraft(): bool      { return $this->status === 'draft'; }
    public function isSubmitted(): bool  { return $this->status === 'submitted'; }
    public function isApproved(): bool   { return $this->status === 'approved'; }
    public function isRejected(): bool   { return $this->status === 'rejected'; }

    public function statusBadgeClass(): string
    {
        return match($this->status) {
            'submitted' => 'warning',
            'approved'  => 'success',
            'rejected'  => 'danger',
            default     => 'secondary',
        };
    }
}