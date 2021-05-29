<?php

namespace App\Console\Commands\Billing;

use App\Concerns\Reconcile\Billing\ReconcilesBalance;
use App\Enums\Billing\Service;
use App\Models\BaseModel;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BalanceReconcileCommand extends Command
{
    use ReconcilesBalance;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reconcile:balance {service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform set reconciliation between vendor billing API and balance database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
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
                Log::info("{$this->created} Balances created, {$this->deleted} Balances deleted, {$this->updated} Balances updated");
                $this->info("{$this->created} Balances created, {$this->deleted} Balances deleted, {$this->updated} Balances updated");
            }
            if ($this->hasFailures()) {
                Log::error("Failed to create {$this->createdFailed} Balances, delete {$this->deletedFailed} Balances, update {$this->updatedFailed} Balances");
                $this->error("Failed to create {$this->createdFailed} Balances, delete {$this->deletedFailed} Balances, update {$this->updatedFailed} Balances");
            }
        } else {
            Log::info('No Balances created or deleted or updated');
            $this->info('No Balances created or deleted or updated');
        }
    }

    /**
     * Handler for successful balance creation.
     *
     * @param \App\Models\BaseModel $model
     * @return void
     */
    protected function handleCreated(BaseModel $model)
    {
        Log::info("Balance '{$model->getName()}' created");
        $this->info("Balance '{$model->getName()}' created");
    }

    /**
     * Handler for failed balance creation.
     *
     * @param \App\Models\BaseModel $model
     * @return void
     */
    protected function handleFailedCreation(BaseModel $model)
    {
        Log::error("Balance '{$model->getName()}' was not created");
        $this->error("Balance '{$model->getName()}' was not created");
    }

    /**
     * Handler for successful balance deletion.
     *
     * @param \App\Models\BaseModel $model
     * @return void
     */
    protected function handleDeleted(BaseModel $model)
    {
        Log::info("Balance '{$model->getName()}' deleted");
        $this->info("Balance '{$model->getName()}' deleted");
    }

    /**
     * Handler for failed balance deletion.
     *
     * @param \App\Models\BaseModel $model
     * @return void
     */
    protected function handleFailedDeletion(BaseModel $model)
    {
        Log::error("Balance '{$model->getName()}' was not deleted");
        $this->error("Balance '{$model->getName()}' was not deleted");
    }

    /**
     * Handler for successful balance update.
     *
     * @param \App\Models\BaseModel $model
     * @return void
     */
    protected function handleUpdated(BaseModel $model)
    {
        Log::info("Balance '{$model->getName()}' updated");
        $this->info("Balance '{$model->getName()}' updated");
    }

    /**
     * Handler for failed balance update.
     *
     * @param \App\Models\BaseModel $model
     * @return void
     */
    protected function handleFailedUpdate(BaseModel $model)
    {
        Log::error("Balance '{$model->getName()}' was not updated");
        $this->error("Balance '{$model->getName()}' was not updated");
    }

    /**
     * Handler for exception.
     *
     * @param Exception $exception
     * @return void
     */
    protected function handleException(Exception $exception)
    {
        Log::error($exception);
        $this->error($exception->getMessage());
    }

    /**
     * Get source repository for service.
     *
     * @param \App\Enums\Billing\Service $service
     * @return \App\Contracts\Repositories\Repository|null
     */
    protected function getSourceRepository(Service $service)
    {
        switch ($service->value) {
        case Service::DIGITALOCEAN:
            return App::make(\App\Repositories\Service\Billing\DigitalOceanBalanceRepository::class);
        }

        return null;
    }

    /**
     * Get destination repository for service.
     *
     * @param \App\Enums\Billing\Service $service
     * @return \App\Contracts\Repositories\Repository|null
     */
    protected function getDestinationRepository(Service $service)
    {
        switch ($service->value) {
        case Service::DIGITALOCEAN:
            return App::make(\App\Repositories\Eloquent\Billing\DigitalOceanBalanceRepository::class);
        }

        return null;
    }
}