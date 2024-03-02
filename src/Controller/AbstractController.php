<?php

namespace Controller;

abstract class AbstractController {

    protected function render(string $template, $options): string
    {
        $globalPath = $_SERVER['DOCUMENT_ROOT'] ."/src/templates/";
        ob_start();
        foreach ( $options as $k => $v ) {
            ${$k} = $v;
        }
        require_once "{$globalPath}{$template}";
        $html = ob_get_clean();

        return $html;
    }
}