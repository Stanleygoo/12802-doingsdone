<tr class="
    tasks__item
    task
    <?php if($task['is_done']):?>task--completed<?php endif ?>
    <?php if($task['is_important']):?>task--important<?php endif ?>
">
    <td class="task__select">
        <label class="checkbox task__checkbox">
            <input
                class="checkbox__input visually-hidden task__checkbox"
                type="checkbox"
                <?php if($task['is_done']):?>checked<?php endif ?>
                value="<?= $task['id']; ?>"
            >
            <span class="checkbox__text">
                <?= strip_tags($task['name'], '<i><b><ul><ol><li><br><h1><h2><h3><h4><h5><h6><p>'); ?>
            </span>
        </label>
    </td>

    <td class="task__file">
        <?php if ($task['file']): ?>
            <a class="download-link" href="<?= '/uploads/' . $task['file'] ?>"
            ><?= $task['file']; ?></a>
        <?php endif ?>
    </td>

    <td class="task__date">
        <?= date_format(date_create($task['deadline']), 'd.m.Y H:i') ?>
    </td>
</tr>
