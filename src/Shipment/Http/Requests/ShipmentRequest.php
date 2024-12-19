<?php

declare(strict_types=1);

namespace MyParcelCom\Integration\Shipment\Http\Requests;

use Carbon\Carbon;
use MyParcelCom\Integration\Http\Requests\FormRequest;

class ShipmentRequest extends FormRequest
{
    public function startDate(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->input('filter.start_date') . ' 00:00:00');
    }

    public function endDate(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->input('filter.end_date') . ' 23:59:59');
    }

    /**
     * @return int|null
     */
    public function pageNumber(): ?int
    {
        return $this->input('page.number') ? (int) $this->input('page.number') : null;
    }

    /**
     * @return int|null
     */
    public function pageSize(): ?int
    {
        return $this->input('page.size') ? (int) $this->input('page.size') : null;
    }
}
