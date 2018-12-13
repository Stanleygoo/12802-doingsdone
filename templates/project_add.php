<h2 class="content__main-heading">Добавление проекта</h2>

<form class="form"  action="project_add.php" method="post">
    <div class="form__row">
        <label class="form__label" for="project_name">Название <sup>*</sup></label>

        <?php
            $error_classname = (isset($errors['name'])) ? "form__input--error" : "";
            $value = htmlspecialchars($form['name'] ?? "");
        ?>

        <input
            class="form__input <?= $error_classname; ?>"
            type="text"
            name="name"
            id="project_name"
            value="<?= $value; ?>"
            placeholder="Введите название проекта"
        >

        <?php if(isset($errors['name'])): ?>
            <p class="form__message"><?= $errors['name']; ?></p>
        <?php endif ?>
    </div>

    <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Добавить">
    </div>
</form>
