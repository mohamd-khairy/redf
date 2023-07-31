<?php

namespace App\Exports;

use App\Models\Event;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EventExport implements FromCollection, WithChunkReading, WithHeadings
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->data);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function headings(): array
    {
        if ($this->data->first()) {
            return array_keys($this->data->first()->toArray());
        }
        return [];
    }
}
