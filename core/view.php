<?php

/**
 * Функция для рендеринга html-шаблона
 * @param string $name путь до файла шаблона
 * @param array $data данные для шаблона
 *
 * @return string отрендеренный шаблон
 */
function view($name, $data = []) {
    if (!file_exists($name)) {
        return '';
    };
    ob_start();
    extract($data);
    require $name;
    return ob_get_clean();
};
