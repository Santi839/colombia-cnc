<?php
class PostController extends Controller {
    public function index() {
        $postModel = new Post();
        $cat = $_GET['cat'] ?? null;
        $posts = $postModel->all($cat);
        $this->view('posts/index', compact('posts'));
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postModel = new Post();
            $payload = [
                'title' => $_POST['title'] ?? '',
                'category' => $_POST['category'] ?? 'General',
                'type' => $_POST['type'] ?? 'article',
                'summary' => $_POST['summary'] ?? '',
                'content' => $_POST['content'] ?? '',
            ];
            $post = $postModel->create($payload);
            return $this->redirect('/posts/show?id='.$post['id']);
        }
        $this->view('posts/create');
    }

    public function show() {
        $id = $_GET['id'] ?? '';
        $postModel = new Post();
        $post = $postModel->find($id);
        if (!$post) {
            http_response_code(404);
            echo "Artículo no encontrado";
            return;
        }
        $this->view('posts/show', compact('post'));
    }
}
