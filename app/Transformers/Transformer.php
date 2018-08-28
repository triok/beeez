<?php

namespace App\Transformers;

use Carbon\Carbon;

abstract class Transformer
{
    /**
     * @param $items
     * @return mixed
     */
    public function transformCollection($items)
    {
        if (is_array($items)) {
            return array_map([$this, 'transform'], $items);
        }

        $data = [];

        foreach ($items as $item) {
            $data['data'][] = $this->transform($item);
        }

        return $data;
    }

    public abstract function transform($item);
}