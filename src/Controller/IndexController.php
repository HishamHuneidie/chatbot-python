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
        ]);
    }

    /**
     * @Route["/python"]
     */
    public function python(): string
    {
        // $time = date("Y-m-d H:i:s", 183792992980);
        // $date = new DateTime($time);
        // dump($date->getTimestamp());
        return "Rengering python";
    }
}