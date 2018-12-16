<h2 class="content__main-heading">Список задач</h2>

<form class="search-form" action="index.php" method="get">
    <?php $query_text = htmlspecialchars($search_query ?? ""); ?>

    <?php foreach ($_GET as $key => $value): ?>
        <?php if ($key !== 'query'): ?>
            <input
                type="hidden"
                name="<?= htmlspecialchars($key); ?>"
                value="<?= htmlspecialchars($value); ?>"
            />
        <?php endif; ?>
    <?php endforeach; ?>
    <input
        class="search-form__input"
        type="text"
        name="query"
        value="<?= $query_text; ?>"
        placeholder="Поиск по задачам"
    >

    <input class="search-form__submit" type="submit" name="" value="Искать">
</form>

<div class="tasks-controls">
    <nav class="tasks-switch">
        <?php foreach($filters as $filter): ?>
            <a
                href="<?= $filter['url']; ?>"
                class="
                    tasks-switch__item
                    <?php if($filter['is_active']): ?>tasks-switch__item--active<?php endif; ?>
                "
            >
                <?= $filter['name']; ?>
            </a>

        <?php endforeach; ?>
    </nav>

    <label class="checkbox">
        <input
            class="checkbox__input visually-hidden show_completed"
            type="checkbox"
            <?php if ($show_complete_tasks === 1):?>checked<?php endif; ?>
        />
        <span class="checkbox__text">Показывать выполненные</span>
    </label>
</div>

<?php if($has_no_results): ?>
    <p>Ничего не найдено по вашему запросу</p>
<?php else: ?>
    <table class="tasks">
        <?php foreach ($visible_tasks as $task): ?>
            <?= view(VIEWS_PATH . 'partials/task.php', ['task' => $task]); ?>
        <?php endforeach ?>
    </table>
<?php endif; ?>

