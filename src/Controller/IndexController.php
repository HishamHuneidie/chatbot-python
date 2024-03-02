<?php

namespace Controller;

use Core\Request;
use DateTime;

class IndexController extends AbstractController {

    /**
     * @Route["/"]
     */
    public function index(Request $request): string
    {
        return $this->render("index/index.php", [
            "uno" => 111,
            "request" => $request->getContent(),
        ]);
    }

    /**
     * @Route["/python"]
     */
    public function python(Request $request): string
    {
        return "Rengering python";
    }
}