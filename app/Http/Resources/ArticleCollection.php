<?php

namespace App\Http\Resources;

use App\Models\Article;
use App\Traits\CollectionTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArticleCollection extends ResourceCollection
{
    use CollectionTrait;

    public function toArray($request)
    {
        $this->collection->transform(function ($report) {
            return (new ArticleResource($report));
        });

        return [
            'data' => parent::toArray($request),
            'paginate' => $this->paginate()
        ];
    }
}
