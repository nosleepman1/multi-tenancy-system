<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase;
    use HasDomains;
    use HasUuids;

    public static function getCustomColumns(): array
    {
        return [
            'name',
            'email',
            'password',
            'login_token',
            'login_token_expires_at',
        ];
    }
}
