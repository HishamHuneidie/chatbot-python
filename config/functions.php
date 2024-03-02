<?php

use Core\JsonSerializableInterface;

function dump(mixed $item) {
    echo "<pre style='border:1px solid red; width:90%; margin 0 auto 10px auto; max-height:300px; overflow:auto; display:block; '>";
    if ( empty($item) ) {
        echo "";
    }
    if ( is_int($item) || is_string($item) ) {
        echo $item;
    }
    if ( is_array($item) ) {
        print_r($item);
    }
    if ( $item instanceof JsonSerializableInterface ) {
        print_r($item->toArray());
    }
    echo "</pre>";
}