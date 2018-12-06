<?php

function view($name, $data) {
    if (!file_exists($name)) {
        return '';
    };
    ob_start();
    extract($data);
    require $name;
    return ob_get_clean();
};
