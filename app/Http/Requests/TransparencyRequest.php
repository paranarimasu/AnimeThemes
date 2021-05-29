<?php

namespace App\Http\Requests;

use App\Enums\Filter\AllowedDateFormat;
use App\Models\Billing\Balance;
use App\Models\Billing\Transaction;
use App\Rules\Billing\TransparencyDateRule;
use Carbon\Carbon;
use DateTime;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class TransparencyRequest extends FormRequest
{
    /**
     * The list of valid transparency dates.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $validDates;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->validDates = $this->initializeValidDates();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => [
                'nullable',
                'date_format:Y-m',
                new TransparencyDateRule($this->validDates),
            ],
        ];
    }

    /**
     * Initialize list of year/month combinations for transparency filtering.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function initializeValidDates()
    {
        $balanceDates = Balance::distinct('date')->pluck('date');
        $transactionDates = Transaction::distinct('date')->pluck('date');

        $validDates = $balanceDates->concat($transactionDates);

        $validDates = $validDates->unique(function ($date) {
            return $date->format(AllowedDateFormat::WITH_MONTH);
        });

        return $validDates->sortDesc();
    }

    /**
     * Get the list of valid year/month combinations for transparency filtering.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getValidDates()
    {
        return $this->validDates;
    }

    /**
     * Get the validated year/month combination for the transparency filter.
     *
     * @return \Carbon\Carbon
     */
    public function getSelectedDate()
    {
        $validDate = Arr::get(
            $this->validated(),
            'date',
            Carbon::now()->format(AllowedDateFormat::WITH_MONTH)
        );

        return Carbon::instance(DateTime::createFromFormat('!'.AllowedDateFormat::WITH_MONTH, $validDate));
    }

    /**
     * Get Balances for selected month.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getBalances()
    {
        $date = $this->getSelectedDate();

        return Balance::whereBetween('date', [$date->copy()->startOfMonth(), $date->copy()->endOfMonth()])
            ->orderBy('usage', 'desc')
            ->get();
    }

    /**
     * Get Transactions for selected month.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTransactions()
    {
        $date = $this->getSelectedDate();

        return Transaction::whereBetween('date', [$date->copy()->startOfMonth(), $date->copy()->endOfMonth()])
            ->orderBy('date', 'desc')
            ->get();
    }
}