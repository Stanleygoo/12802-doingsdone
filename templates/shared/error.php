<?=
    build_layout([
        'error_page' => true,
        'content' => "
            <div class='content__error'>
                <b>$status_code</b>
                <pre class='content__error-message'>$message</pre>
                <a class='button button--transparent' href='/'>Перейти на главную</a>
            </div>
        "
    ])
?>
