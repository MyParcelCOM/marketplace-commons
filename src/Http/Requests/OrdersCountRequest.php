<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Http\Requests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class OrdersCountRequest extends FormRequest
{
    private const DATE_FORMAT = 'Y-m-d';

    public function dateFrom(): Carbon
    {
        $from = $this->query('date_from');
        if (!$from) {
            return Carbon::now()->subDay();
        }

        $this->validateDateFormat($from);

        return $this->toCarbon($from);
    }

    public function dateTo(): Carbon
    {
        $to = $this->query('date_to');
        if (!$to) {
            return Carbon::now();
        }

        $this->validateDateFormat($to);

        return $this->toCarbon($to);
    }

    private function validateDateFormat(string $date): void
    {
        Validator::validate(
            ['date' => $date],
            ['date' => 'date_format:' . self::DATE_FORMAT],
        );
    }

    private function toCarbon(string $date): Carbon
    {
        return Carbon::createFromFormat(self::DATE_FORMAT, $date)->startOfDay();
    }
}
