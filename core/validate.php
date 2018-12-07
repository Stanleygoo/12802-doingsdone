<?php

function validate($data, $schema) {
    $errors = [];

    foreach($schema as $key => $schema_item) {
        $is_required = $schema_item['required'] ?? false;

        if ((isset($data[$key]) && !empty($data[$key]) && isset($schema_item['validator']))) {
            if (!($schema_item['validator'])($data[$key])) {
                $errors[$key] = $schema_item['message'];
            }
        } else {
            if ($is_required) {
                if (isset($data[$key]) && empty($data[$key])) {
                    $errors[$key] = 'Это поле не должно быть пустым';
                }
            }
        }
    }

    return [
        'result' => count($errors) === 0,
        'errors' => $errors
    ];;
}
