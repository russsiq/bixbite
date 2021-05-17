<?php

namespace App\Http\Controllers;

use App\Contracts\Actions\Comment\CreatesComment;
use App\Contracts\Responses\SuccessfulCommentCreateResponseContract as Response;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ArticleCommentController extends Controller
{
    /** @var Container */
    protected $container;

    /**
     * Create a new controller instance.
     *
     * @param  Container  $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatesComment  $creator
     * @param  Request  $request
     * @param  Article  $article
     * @return Responsable
     */
    public function __invoke(
        CreatesComment $creator,
        Request $request,
        Article $article
    ): Responsable {
        $request->merge([
            'author_ip' => $request->ip(),
        ]);

        /** @var Comment */
        $comment = $creator->create($article, $request->all());

        return $this->container->make(Response::class, [
            'comment' => $comment,
            'commentable' => $article,
        ]);
    }
}
