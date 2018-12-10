<h2 class="content__main-heading">Вход на сайт</h2>

<form class="form" action="auth.php" method="post">
<div class="form__row">
    <label class="form__label" for="email">E-mail <sup>*</sup></label>

    <?php
        $error_classname = (isset($errors['email'])) ? "form__input--error" : "";
        $value = htmlspecialchars($form['email'] ?? "");
    ?>

    <input
        class="form__input <?= $error_classname; ?>"
        type="text"
        name="email"
        id="email"
        value="<?= $value; ?>"
        placeholder="Введите e-mail"
    >

    <?php if(isset($errors['email'])): ?>
        <p class="form__message"><?= $errors['email']; ?></p>
    <?php endif ?>
</div>

<div class="form__row">
    <label class="form__label" for="password">Пароль <sup>*</sup></label>

    <?php
        $error_classname = (isset($errors['password'])) ? "form__input--error" : "";
    ?>

    <input
        class="form__input <?= $error_classname; ?>"
        type="password"
        name="password"
        id="password"
        value=""
        placeholder="Введите пароль"
    >

    <?php if(isset($errors['password'])): ?>
        <p class="form__message"><?= $errors['password']; ?></p>
    <?php endif ?>
</div>

<div class="form__row form__row--controls">
    <?php if(isset($errors['verify'])): ?>
        <p class="error-message">Пользователя с такими данными не существует</p>
    <?php endif; ?>
    <input class="button" type="submit" name="" value="Войти">
</div>
</form>
