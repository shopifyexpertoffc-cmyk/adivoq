<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'causer_type',
        'causer_id',
        'subject_type',
        'subject_id',
        'tenant_id',
        'action',
        'description',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * Get the causer (who performed the action)
     */
    public function causer()
    {
        return $this->morphTo();
    }

    /**
     * Get the subject (what was affected)
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Log an activity.
     *
     * $subject and $causer should be Eloquent models.
     * If you pass a string/array, we won't try to store it in subject_id.
     */
    public static function log(
        string $action,
        ?string $description = null,
        $subject = null,
        $causer = null,
        array $properties = [],
        ?string $tenantId = null
    ): self {
        $subjectType = null;
        $subjectId = null;

        if ($subject instanceof Model) {
            $subjectType = get_class($subject);
            $subjectId = $subject->getKey();
        }

        $causerType = null;
        $causerId = null;

        if ($causer instanceof Model) {
            $causerType = get_class($causer);
            $causerId = $causer->getKey();
        }

        return static::create([
            'action'       => $action,
            'description'  => $description,
            'subject_type' => $subjectType,
            'subject_id'   => $subjectId,
            'causer_type'  => $causerType,
            'causer_id'    => $causerId,
            'tenant_id'    => $tenantId,
            'properties'   => $properties,
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
        ]);
    }
}