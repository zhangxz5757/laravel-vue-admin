<?php

namespace App\Http\Middleware;


use Illuminate\Pagination\LengthAwarePaginator;

class Pagination extends LengthAwarePaginator
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        //自定义需要返回的数据
        return [
            'total' => (int) $this->total(),
            'list'  => $this->items->toArray(),
            'per_page' => (int) $this->perPage(),
            'current_page' => (int) $this->currentPage()
        ];
    }
}
