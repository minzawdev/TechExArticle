<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest;
use App\Http\Requests\CustomRequest;
use App\Repositories\Interfaces\IArticleRepository;
use App\Traits\CommonTrait;
use App\Model\Article;

class ArticleController extends Controller
{
    use CommonTrait;

    protected $articleRepository;

    public function __construct(IArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function index(CustomRequest $request)
    {
        $result = $this->articleRepository->getAll($request->all());
        return response()->json($result, 200);
    }

    public function show($id)
    {
        $id = $this->decrypt($id);
        $result = $this->articleRepository->find($id);
        return response()->json($result, 200);
    }

    public function store(ArticleRequest $request)
    {
        $request->validated();
        $result = $this->articleRepository->create($request->all());
        return response()->json($result, 201);
    }
    
    public function findWithDateTime($key, Request $request)
    {
        $specific_datetime = isset($request->timestamp) ? $request->timestamp : null;
        $result = $this->articleRepository->findWithDateTime($key, $specific_datetime);
        return response()->json($result, 200);
    }
}
