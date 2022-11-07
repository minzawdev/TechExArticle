<?php

namespace App\Repositories;

use App\Http\Resources\ArticleCollection;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleSearchResource;
use App\Repositories\Interfaces\IArticleRepository;
use Carbon\Carbon;
use App\Models\Article;
use App\Traits\CommonTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ArticleRepository implements IArticleRepository
{
    use CommonTrait;

    public function getAll($data = null)
    {
        $paginate = intval(config('app.paginate'));

        $articles = Article::select([
            'id',
            'title',
            'body',
            'created_at',
            'updated_at'
        ])
            ->orderBy('updated_at', 'desc');

        if (isset($data['qry']) && $data['qry']) {

            $cacheKey = __METHOD__ . '-' . $data['qry'];

            if (!$data['cache']) Cache::forget($cacheKey);

            if (!Cache::has($cacheKey)) {
                $articles = Cache::remember($cacheKey, now()->addMinutes(1), function () use ($data, $articles) {
                    $articles = $articles->get();
                    if ($data['qry']) {
                        $articles->map(function ($article) {
                            $article = new ArticleSearchResource($article);
                            return $article->toArray($article);
                        })->reject(function ($article, $key) use ($articles, $data) {
                            if (!$this->searchArray($data['qry'], $article)) {
                                $articles->forget($key);
                            }
                        });
                    }
                    return $articles;
                });
            } else {
                $articles = Cache::get($cacheKey);
            }
            $articles = \App\Helpers\General\CollectionHelper::paginate($articles, $paginate);
        } else {
            $articles = $articles->paginate($paginate);
        }
        return (new ArticleCollection($articles));
    }

    public function create($data)
    {
        $result = Article::create($data);
        return new ArticleResource($result);
    }

    public function find($id)
    {
        $article = Article::findOrFail($id);
        return new ArticleResource($article);
    }

    public function findWithDateTime($key, $specific_datetime)
    {
        $qry_article = Article::where('title', $key);

        if ($specific_datetime)
            $qry_article->where('created_at', 'like', $specific_datetime)
                ->orWhere('updated_at', $specific_datetime);

        $articles = $qry_article->orderBy('updated_at', 'desc')->get();

        return ArticleResource::collection($articles);
    }
}
