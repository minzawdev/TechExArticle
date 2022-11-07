<?php

namespace Tests\Unit;
use App\Models\Article;
use Carbon\Carbon;
use Tests\TestCase;
class ArticleTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
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

        $headers = ['Authorization' => ""];

        $response = $this->json('GET', '/api/articles/all', [], $headers)
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

        $headers = ['Authorization' => ""];
        $searchQry = 'Lorem'; //all of fields of values can search

        $response = $this->json('GET', '/api/articles/all?qry=' . $searchQry, [], $headers)
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

        $headers = ['Authorization' => "", 'cache' => true];
        $searchQry = 'Lorem'; //all of fields of values can search

        $response = $this->json('GET', '/api/articles/all?qry=' . $searchQry, [], $headers)
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

        $headers = ['Authorization' => "", 'cache' => false];
        $searchQry = 'Lorem'; //all of fields of values can search

        $response = $this->json('GET', '/api/articles/all?qry=' . $searchQry, [], $headers)
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

        $headers = ['Authorization' => ""];
        $nextPagination = 2;

        $response = $this->json('GET', '/api/articles/all?page=' . $nextPagination, [], $headers)
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
        $headers = ['Authorization' => ""];
        $payload = [
            'title' => 'Lorem',
            'body' => 'Ipsum'
        ];

        $this->json('POST', '/api/articles', $payload, $headers)
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

        $headers = ['Authorization' => ""];

        $id = encrypt($article->id);

        $response = $this->json('GET', '/api/articles/' . $id . '/show', [], $headers)
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

        $headers = ['Authorization' => ""];
        $key = $article->title;
        $specific_datetime = $article->created_at;

        $response = $this->json('GET', '/api/articles/' . $key . '?timestamp=' . $specific_datetime, [], $headers)
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

        $headers = ['Authorization' => ""];
        $key = $article->title;
        $response = $this->json('GET', '/api/articles/' . $key, [], $headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'title', 'body', 'created_at', 'updated_at']
            ]);
    }

    public function testRequiresArticlesCreate()
    {
        $this->json('POST', '/api/articles')
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
        $headers = ['Authorization' => ""];

        $payload = [
            'title' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry
                    standard dummy text ever since the 1500s',
            'body' => 'It has survived not only five centuries, but also the leap into electronic typesetting,
             remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
             and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'
        ];

        $this->json('POST', '/api/articles', $payload, $headers)
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
