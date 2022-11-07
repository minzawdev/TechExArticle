<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleSearchResource extends JsonResource
{
    public function toArray($request)
    {
        $articles = new ArticleResource($this);
        $articles = $articles->toArray($articles);

        unset($articles["id"]); // except id value, all of field can search on qry string

        return $articles;
    }
}
