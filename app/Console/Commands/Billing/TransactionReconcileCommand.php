<?php

declare(strict_types=1);

namespace App\Console\Commands\Billing;

use App\Concerns\Reconcile\Billing\ReconcilesTransactionRepositories;
use App\Contracts\Repositories\Repository;
use App\Enums\Models\Billing\Service;
use App\Models\BaseModel;
use App\Repositories\Eloquent\Billing\DigitalOceanTransactionRepository as DigitalOceanDestinationRepository;
use App\Repositories\Service\DigitalOcean\Billing\DigitalOceanTransactionRepository as DigitalOceanSourceRepository;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Class TransactionReconcileCommand.
 */
class TransactionReconcileCommand extends Command
{
    use ReconcilesTransactionRepositories;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reconcile:transaction {service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform set reconciliation between vendor billing API and transaction database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $key = $this->argument('service');
        $service = Service::coerce(Str::upper($key));

        if ($service === null) {
            Log::error("Invalid Service '{$key}'");
            $this->error("Invalid Service '{$key}'");

            return 1;
        }

        $sourceRepository = $this->getSourceRepository($service);
        if ($sourceRepository === null) {
            Log::error("No source repository implemented for Service '{$key}'");
            $this->error("No source repository implemented for Service '{$key}'");

            return 1;
        }

        $destinationRepository = $this->getDestinationRepository($service);
        if ($destinationRepository === null) {
            Log::error("No destination repository implemented for Service '{$key}'");
            $this->error("No destination repository implemented for Service '{$key}'");

            return 1;
        }

        $this->reconcileRepositories($sourceRepository, $destinationRepository);

        return 0;
    }

    /**
     * Print the result to console and log the results to the app log.
     *
     * @return void
     */
    protected function postReconciliationTask()
    {
        if ($this->hasResults()) {
            if ($this->hasChanges()) {
                Log::info("{$this->created} Transactions created, {$this->deleted} Transactions deleted, {$this->updated} Transactions updated");
                $this->info("{$this->created} Transactions created, {$this->deleted} Transactions deleted, {$this->updated} Transactions updated");
            }
            if ($this->hasFailures()) {
                Log::error("Failed to create {$this->createdFailed} Transactions, delete {$this->deletedFailed} Transactions, update {$this->updatedFailed} Transactions");
                $this->error("Failed to create {$this->createdFailed} Transactions, delete {$this->deletedFailed} Transactions, update {$this->updatedFailed} Transactions");
            }
        } else {
            Log::info('No Transactions created or deleted or updated');
            $this->info('No Transactions created or deleted or updated');
        }
    }

    /**
     * Handler for successful transaction creation.
     *
     * @param BaseModel $model
     * @return void
     */
    protected function handleCreated(BaseModel $model)
    {
        Log::info("Transaction '{$model->getName()}' created");
        $this->info("Transaction '{$model->getName()}' created");
    }

    /**
     * Handler for failed transaction creation.
     *
     * @param BaseModel $model
     * @return void
     */
    protected function handleFailedCreation(BaseModel $model)
    {
        Log::error("Transaction '{$model->getName()}' was not created");
        $this->error("Transaction '{$model->getName()}' was not created");
    }

    /**
     * Handler for successful transaction deletion.
     *
     * @param BaseModel $model
     * @return void
     */
    protected function handleDeleted(BaseModel $model)
    {
        Log::info("Transaction '{$model->getName()}' deleted");
        $this->info("Transaction '{$model->getName()}' deleted");
    }

    /**
     * Handler for failed transaction deletion.
     *
     * @param BaseModel $model
     * @return void
     */
    protected function handleFailedDeletion(BaseModel $model)
    {
        Log::error("Transaction '{$model->getName()}' was not deleted");
        $this->error("Transaction '{$model->getName()}' was not deleted");
    }

    /**
     * Handler for successful transaction update.
     *
     * @param BaseModel $model
     * @return void
     */
    protected function handleUpdated(BaseModel $model)
    {
        Log::info("Transaction '{$model->getName()}' updated");
        $this->info("Transaction '{$model->getName()}' updated");
    }

    /**
     * Handler for failed transaction update.
     *
     * @param BaseModel $model
     * @return void
     */
    protected function handleFailedUpdate(BaseModel $model)
    {
        Log::error("Transaction '{$model->getName()}' was not updated");
        $this->error("Transaction '{$model->getName()}' was not updated");
    }

    /**
     * Handler for exception.
     *
     * @param Exception $exception
     * @return void
     */
    protected function handleException(Exception $exception)
    {
        Log::error($exception->getMessage());
        $this->error($exception->getMessage());
    }

    /**
     * Get source repository for service.
     *
     * @param Service $service
     * @return Repository|null
     */
    protected function getSourceRepository(Service $service): ?Repository
    {
        return match ($service->value) {
            Service::DIGITALOCEAN => App::make(DigitalOceanSourceRepository::class),
            default => null,
        };
    }

    /**
     * Get destination repository for service.
     *
     * @param Service $service
     * @return Repository|null
     */
    protected function getDestinationRepository(Service $service): ?Repository
    {
        return match ($service->value) {
            Service::DIGITALOCEAN => App::make(DigitalOceanDestinationRepository::class),
            default => null,
        };
    }
}