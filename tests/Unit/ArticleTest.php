<?php

namespace Tests\Unit;

use App\Models\Article;
use Carbon\Carbon;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function testArticlesAreListedCorrectly()
    {
        Article::insert(
            [
                [
                    'title' => 'FirstArticle',
                    'body' => 'First Body',
                    'created_at' => Carbon::now()->format('Y-m-d H:m:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:m:s')
                ],
                [
                    'title' => 'SecondArticle',
                    'body' => 'Second Body',
                    'created_at' => Carbon::now()->format('Y-m-d H:m:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:m:s')
                ],
                [
                    'title' => 'ThirdArticle',
                    'body' => 'Third Body',
                    'created_at' => Carbon::now()->format('Y-m-d H:m:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:m:s')
                ]
            ]
        );

        $response = $this->getJson('/api/articles/all')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'body',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'paginate' => [
                    'current_page',
                    'last_page',
                    'row_per_page',
                    'total_items',
                    'from',
                    'to'
                ]
            ]);
    }

    public function testArticlesAreListedSearchQryStrCorrectly()
    {
        $article = Article::create([
            'title' => 'Lorem',
            'body' => 'Ipsum'
        ]);

        $searchQry = 'Lorem'; //all of fields of values can search

        $response = $this->getJson('/api/articles/all?qry=' . $searchQry)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'body',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'paginate' => [
                    'current_page',
                    'last_page',
                    'row_per_page',
                    'total_items',
                    'from',
                    'to'
                ]
            ]);
    }

    public function testArticlesAreListedSearchQryStrWithCacheCorrectly()
    {
        $article = Article::create([
            'title' => 'Lorem',
            'body' => 'Ipsum'
        ]);

        $headers = ['Authorization' => "", 'cache' => 1];
        $searchQry = 'Lorem'; //all of fields of values can search

        $response = $this->getJson('/api/articles/all?qry=' . $searchQry, $headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'body',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'paginate' => [
                    'current_page',
                    'last_page',
                    'row_per_page',
                    'total_items',
                    'from',
                    'to'
                ]
            ]);
    }

    public function testArticlesAreListedSearchQryStrWithoutCacheCorrectly()
    {
        $article = Article::create([
            'title' => 'Lorem',
            'body' => 'Ipsum'
        ]);

        $headers = ['Authorization' => "", 'cache' => 0];
        $searchQry = 'Lorem'; //all of fields of values can search

        $response = $this->getJson('/api/articles/all?qry=' . $searchQry, [], $headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'body',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'paginate' => [
                    'current_page',
                    'last_page',
                    'row_per_page',
                    'total_items',
                    'from',
                    'to'
                ]
            ]);
    }

    public function testArticlesAreListedPaginationCorrectly()
    {
        $article = Article::create([
            'title' => 'Lorem',
            'body' => 'Ipsum'
        ]);

        $nextPage = 2;

        $response = $this->getJson('/api/articles/all?page=' . $nextPage)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'body',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'paginate' => [
                    'current_page',
                    'last_page',
                    'row_per_page',
                    'total_items',
                    'from',
                    'to'
                ]
            ]);
    }

    public function testArticlesAreCreatedCorrectly()
    {
        $payload = [
            'title' => 'Lorem',
            'body' => 'Ipsum'
        ];

        $this->postJson('/api/articles', $payload)
            ->assertStatus(201)
            ->assertJson([
                'title' => 'Lorem', 'body' => 'Ipsum'
            ])
            ->assertJsonStructure([
                '*' => 'id', 'body', 'title', 'created_at', 'updated_at'
            ]);
    }

    public function testArticlesAreShowedCorrectly()
    {
        $article = Article::create([
            'title' => 'Lorem',
            'body' => 'Ipsum'
        ]);

        $id = encrypt($article->id);

        $response = $this->getJson('/api/articles/' . $id . '/show')
            ->assertStatus(200)
            ->assertJson([
                'title' => 'Lorem',
                'body' => 'Ipsum'
            ])
            ->assertJsonStructure([
                '*' => 'id', 'title', 'body', 'created_at', 'updated_at'
            ]);
    }

    public function testArticlesFindWithDateTimeCorrectly()
    {
        $article = Article::create([
            'title' => 'Lorem',
            'body' => 'Ipsum'
        ]);

        $key = $article->title;
        $specific_datetime = $article->created_at;

        $response = $this->getJson('/api/articles/' . $key . '?timestamp=' . $specific_datetime)
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'title', 'body', 'created_at', 'updated_at']
            ]);
    }

    public function testArticlesFindKeyCorrectly()
    {
        $article = Article::create([
            'title' => 'Lorem',
            'body' => 'Ipsum'
        ]);
        $key = $article->title;
        $response = $this->getJson('/api/articles/' . $key)
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'title', 'body', 'created_at', 'updated_at']
            ]);
    }

    public function testArticlesFindKeyAgainCorrectly()
    {
        $article = Article::create([
            'title' => 'Lorem',
            'body' => 'Ipsum'
        ]);
        $key = $article->title;
        $response = $this->getJson('/api/articles/' . $key)
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'title', 'body', 'created_at', 'updated_at']
            ]);
    }

    public function testRequiresArticlesCreate()
    {
        $this->postJson('/api/articles')
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "title" => [
                        "Article title is required."
                    ],
                    "body" => [
                        "Article body is required."
                    ]
                ]
            ]);
    }

    public function testMaxCharacterArticlesCreate()
    {
        $payload = [
            'title' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry
                    standard dummy text ever since the 1500s',
            'body' => 'It has survived not only five centuries, but also the leap into electronic typesetting,
             remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
             and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'
        ];

        $this->postJson('/api/articles', $payload)
            ->assertStatus(422)
            ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    "title" => [
                        "Article title is not greater than 100 characters."
                    ],
                    "body" => [
                        "Article body is not greater than 255 characters."
                    ]
                ]
            ]);
    }
}
