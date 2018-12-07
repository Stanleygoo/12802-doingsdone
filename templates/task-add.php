<h2 class="content__main-heading">Добавление задачи</h2>

<form class="form" action="add.php" method="post" enctype="multipart/form-data">
    <div class="form__row">
        <label class="form__label" for="name">Название <sup>*</sup></label>

        <?php
            $error_classname = (isset($errors['name'])) ? "form__input--error" : "";
            $value = htmlspecialchars($task['name'] ?? "");
        ?>
        <input
            class="form__input <?= $error_classname; ?>"
            type="text"
            name="name"
            id="name"
            value="<?= $value; ?>"
            placeholder="Введите название"
        >

        <?php if(isset($errors['name'])): ?>
            <p class="form__message"><?= $errors['name']; ?></p>
        <?php endif ?>
    </div>

    <div class="form__row">
        <label class="form__label" for="project">Проект <sup>*</sup></label>

        <?php
            $error_classname = (isset($errors['project'])) ? "form__input--error" : "";
            $value = htmlspecialchars($task['project'] ?? "");
        ?>

        <select class="form__input form__input--select <?= $error_classname; ?>" name="project" id="project">
            <?php foreach($projects as $project): ?>
                <option
                    value="<?= htmlentities($project['id']); ?>"
                    <?php if($project['is_active']): ?>selected<?php endif ?>
                >
                    <?= htmlspecialchars($project['name']); ?>
                </option>
            <?php endforeach ?>
        </select>

        <?php if(isset($errors['project'])): ?>
            <p class="form__message"><?= $errors['project']; ?></p>
        <?php endif ?>
    </div>

    <div class="form__row">
        <label class="form__label" for="date">Дата выполнения</label>

        <?php
            $error_classname = (isset($errors['date'])) ? "form__input--error" : "";
            $value = htmlspecialchars($task['date'] ?? "");
        ?>

        <input
            class="form__input form__input--date <?= $error_classname; ?>"
            type="date"
            name="date"
            id="date"
            value="<?= $value; ?>"
            placeholder="Введите дату в формате ДД.ММ.ГГГГ"
        >
    </div>

    <div class="form__row">
        <label class="form__label" for="preview">Файл</label>

        <div class="form__input-file">
            <input class="visually-hidden" type="file" name="preview" id="preview" value="">

            <label class="button button--transparent" for="preview">
            <span>Выберите файл</span>
            </label>
        </div>
    </div>

    <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Добавить">
    </div>
</form>
