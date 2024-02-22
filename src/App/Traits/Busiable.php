<?php

namespace The3labsTeam\NovaBusyResourceField\App\Traits;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Busiable
{
    // BUSY

    /**
     * Return the user who is busy with this resource
     */
    public function busyFrom(Admin $admin)
    {
        $this->busier()->syncWithoutDetaching([$admin->id => ['created_at' => now(), 'updated_at' => now()]]);
    }

    public function unbusy()
    {
        return $this->busier()->delete();
    }

    public function isBusy(): bool
    {
        return $this->busier()->count() > 0;
    }

    public function busyData(): array
    {
        return $this->busier()->first()->toArray();
    }

    public function isNotBusy(): bool
    {
        return !$this->isBusy();
    }

    public function scopeWhereBusy($query)
    {
        return $query->whereHas('busier');
    }

    public function scopeWhereNotBusy($query)
    {
        return $query->whereDoesntHave('busier');
    }

    //=== RELATIONSHIPS ===//
    public function busier(): MorphToMany
    {
        return $this->morphToMany(Admin::class, 'busiable')->withPivot('created_at', 'updated_at');
    }
}
