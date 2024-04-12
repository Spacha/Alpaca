<?php

namespace App\Controllers;

use App\Framework\Exceptions\RoutingException as NotFound;
use App\Framework\Logs\ActivityLog as Log;
use App\Framework\Libs\{
    Validator as Validate,
    Auth\AuthMiddleware,
    Auth\Authenticator,
    Controller,
    Request,
    View
};

use App\Models\Blog;

class BlogController extends Controller
{
    protected $user;
    protected $requiresAuth = [
        'create', 'edit', 'add', 'update', 'updatePublicity', 'delete'
    ];

    const SNIPPETS = ['header', 'footer'];

    public function __construct()
    {
        parent::__construct(
            new Blog(),
            new AuthMiddleware($this->requiresAuth)
        );
    }

    public function afterMiddleware() : void
    {
        $this->user = Authenticator::user();
    }

    public function list()
    {
        //$posts = $this->model->list('id', 'title', ['content', 'SELECT LEFT', 50]);
        $posts = $this->model->list(Authenticator::loggedIn());

        return new View('blog.home', [
            'active'    => 'blog',
            'posts'     => $posts
        ], self::SNIPPETS);
    }

    public function view(Request $request, $postId)
    {
        Validate::integer($postId);

        $post = $this->model->view($postId);

        // post does not exist or is not public
        if (!$post || (!$post->is_public && !Authenticator::loggedIn()))
            throw new NotFound("Blog post id '{$postId}' not found");

        return new View('blog.view', ['active' => 'blog', 'post' => $post, 'categories' => []], self::SNIPPETS);
    }

    public function create()
    {
        return new View('blog.create', ['active' => 'blog', 'categories' => []], self::SNIPPETS);
    }

    public function edit(Request $request, $postId)
    {
        Validate::integer($postId);

        $post = $this->model->view($postId);

        if (!$post)
            throw new NotFound("Blog post id '{$postId}' not found");

        return new View('blog.edit', [
            'active'        => 'blog',
            'post'          => $post,
            'categories'    => []
        ], self::SNIPPETS);
    }

    public function add(Request $request)
    {
        $id = $this->model->add([
            'title'         => $request->data('title'),
            'content'       => $request->data('content'),
            'author_id'     => (int)$this->user['id'],
            'is_public'     => ($request->data('is_public') == '1') ? '1' : '0',
            'category_id'   => $request->data('category_id')
        ]);

        if ($id > 0) {
            Log::write("User [{$this->user['id']}] CREATED a blog post [{$id}].");
            redirect("/blog/{$id}");
        }

        redirect("/blog/create");
    }

    public function update(Request $request, $postId)
    {
        Validate::integer($postId);

        $success = $this->model->update($postId, [
            'title'         => $request->data('title'),
            'content'       => $request->data('content'),
            'is_public'     => ($request->data('is_public') == '1') ? '1' : '0',
            'category_id'   => $request->data('category_id')
        ]);

        if ($success) {
            Log::write("User [{$this->user['id']}] EDITED a blog post [{$postId}].");
        }

        redirect("/blog/{$postId}");
    }

    public function updatePublicity(Request $request, $postId)
    {
        Validate::integer($postId);
        $isPublic = $this->model->isPublic($postId);

        $this->model->update($postId, [
            'is_public' => ($isPublic == '1') ? '0' : '1'
        ]);

        redirect("/blog");
    }

    public function delete(Request $request, $postId)
    {
        Validate::integer($postId);
        $success = $this->model->delete($postId);

        if ($success)
            Log::write("User [{$this->user['id']}] DELETED a blog post [{$postId}].");

        redirect("/blog");
    }
}
