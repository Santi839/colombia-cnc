<?php
class HomeController extends Controller {
    public function index() {
        $postModel = new Post();
        $posts = $postModel->all(); // ahora vienen desde la BD
        $this->view('home/index', compact('posts'));
    }
}
