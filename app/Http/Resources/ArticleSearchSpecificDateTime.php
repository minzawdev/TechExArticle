<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleSearchSpecificDateTime extends JsonResource
{
    public function toArray($request)
    {
        $articles = new ArticleResource($this);
        $articles = $articles->toArray($articles);
        unset($articles["id"]); 
        unset($articles["title"]);
        unset($articles["body"]);
        return $articles;
    }
}
