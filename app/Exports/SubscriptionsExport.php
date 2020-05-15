<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class SubscriptionsExport implements FromArray
{
    private $items;
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function array(): array
    {
        return $this->items;
    }
}
