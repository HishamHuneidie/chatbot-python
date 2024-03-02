<?php

namespace Controller;

class IndexController extends AbstractController {

    /**
     * @Route["/"]
     */
    public function index(): string
    {
        return "Controlador de inicio";
    }

    /**
     * @Route["/python"]
     */
    public function python(): string
    {
        return $this->render("index/index.php", [
            "uno" => 111,
        ]);
    }
}