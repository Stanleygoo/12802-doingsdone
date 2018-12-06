<h2 class="content__side-heading">Проекты</h2>

<?php if(isset($projects)): ?>
    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach ($projects as $project): ?>
            <li class="
                main-navigation__list-item
                <?php if($project['is_active']): ?>main-navigation__list-item--active<?php endif ?>
            ">
                <a class="main-navigation__list-item-link" href="<?= $project['url'] ?>">
                    <?= htmlspecialchars($project['name']); ?>
                </a>
                <span class="main-navigation__list-item-count">
                    <?= $project['tasks_count']; ?>
                </span>
            </li>
            <?php endforeach ?>
        </ul>
    </nav>
<?php endif ?>

<a class="button button--transparent button--plus content__side-button"
href="pages/form-project.html" target="project_add">Добавить проект</a>
