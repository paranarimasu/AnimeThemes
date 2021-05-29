<?php

namespace App\Repositories\Service\Billing;

use App\Contracts\Repositories\Repository;
use App\Enums\Billing\Service;
use App\Enums\Filter\AllowedDateFormat;
use App\Models\Billing\Transaction;
use DateTime;
use DateTimeInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class DigitalOceanTransactionRepository implements Repository
{
    /**
     * Get all models from the repository.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        // Do not proceed if we do not have authorization to the DO API
        $doBearerToken = Config::get('services.do.token');
        if ($doBearerToken === null) {
            Log::error('DO_BEARER_TOKEN must be configured in your env file.');

            return Collection::make();
        }

        $sourceTransactions = [];

        try {
            $client = new Client();

            $nextBillingHistory = 'https://api.digitalocean.com/v2/customers/my/billing_history?per_page=200';
            while (! empty($nextBillingHistory)) {
                // Try not to upset DO
                sleep(rand(5, 15));

                $response = $client->get($nextBillingHistory, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer '.$doBearerToken,
                    ],
                ]);

                $billingHistoryJson = json_decode($response->getBody()->getContents(), true);

                $billingHistory = $billingHistoryJson['billing_history'];
                foreach ($billingHistory as $sourceTransaction) {
                    $sourceTransactions[] = Transaction::make([
                        'date' => DateTime::createFromFormat('!'.DateTimeInterface::RFC3339, $sourceTransaction['date'])->format(AllowedDateFormat::WITH_DAY),
                        'service' => Service::DIGITALOCEAN,
                        'description' => $sourceTransaction['description'],
                        'amount' => $sourceTransaction['amount'],
                        'external_id' => Arr::get($sourceTransaction, 'invoice_id', null),
                    ]);
                }

                $nextBillingHistory = Arr::get($billingHistoryJson, 'links.pages.next', null);
            }
        } catch (ClientException $e) {
            Log::info($e->getMessage());

            return Collection::make();
        } catch (ServerException $e) {
            Log::info($e->getMessage());

            return Collection::make();
        }

        return Collection::make($sourceTransactions);
    }

    /**
     * Save model to the repository.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function save(Model $model)
    {
        // Billing API is not writable
        return false;
    }

    /**
     * Delete model from the repository.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return bool
     */
    public function delete(Model $model)
    {
        // Billing API is not writable
        return false;
    }

    /**
     * Update model in the repository.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $attributes
     * @return bool
     */
    public function update(Model $model, array $attributes)
    {
        // Billing API is not writable
        return false;
    }
}