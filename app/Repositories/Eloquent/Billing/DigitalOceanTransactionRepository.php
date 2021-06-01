<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent\Billing;

use App\Enums\Billing\Service;
use App\Models\Billing\Transaction;
use App\Repositories\Eloquent\EloquentRepository;
use Illuminate\Support\Collection;

/**
 * Class DigitalOceanTransactionRepository
 * @package App\Repositories\Eloquent\Billing
 */
class DigitalOceanTransactionRepository extends EloquentRepository
{
    /**
     * Get all models from the repository.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return Transaction::where('service', Service::DIGITALOCEAN)->get();
    }
}
