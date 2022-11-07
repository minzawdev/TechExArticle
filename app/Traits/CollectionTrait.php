<?php

namespace App\Traits;

trait CollectionTrait
{
    public function paginate() {
        return [
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'row_per_page' => (int) $this->perPage(),
            'total_items' => $this->total(),
            'from' => $this->firstItem(),
            'to' => $this->lastItem(),
        ];
    }
}
