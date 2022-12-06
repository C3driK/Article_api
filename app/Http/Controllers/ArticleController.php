<?php

namespace App\Http\Controllers;

use App\Events\ArticleCreated;
use App\Events\ArticleUpdated;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    /**
     * Get the list of all active articles.
     */
    public function index()
    {
        $articles = Article::active()->withListFields()->paginate();

        return $this->okResponse('List of articles retrieved successfully.', $articles);
    }

    /**
     * Create a new article.
     */
    public function store(ArticleRequest $request): JsonResponse
    {
        $article = Article::create($request->validated());

        event(new ArticleCreated($article));

        return $this->createdResponse('Article created successfully.', $article);
    }

    /**
     * Update the specified article.
     */
    public function update(ArticleRequest $request, Article $article): JsonResponse
    {
        $article->update($request->validated());

        event(new ArticleUpdated($article));

        return $this->okResponse('Article updated successfully.', $article);
    }

    /**
     * Delete the specified article.
     */
    public function delete(Article $article): JsonResponse
    {
        $article->delete();

        return $this->noContentResponse();
    }
}
