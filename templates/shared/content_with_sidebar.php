<?php if(isset($content['side'])): ?>
    <section class="content__side">
        <?= $content['side']; ?>
    </section>
<?php endif; ?>

<?php if(isset($content['main'])): ?>
    <main class="content__main">
        <?= $content['main']; ?>
    </main>
<?php endif; ?>
