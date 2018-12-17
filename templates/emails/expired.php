<?php
$table_style = "
    width: 100%;
    border-collapse: collapse;
";

$cell_style = "
    padding: 4px 8px;
    border: 1px solid #d7dbe8;
";
?>

<div>
    Уважаемый(-ая), <b><?= htmlspecialchars($user); ?></b>.
</div>

<?php if (count($tasks) === 1): ?>
    У вас запланирована задача <?= htmlspecialchars($tasks[0]['task_name']); ?> на <i><?= htmlspecialchars($tasks[0]['deadline']); ?></i>
<?php else: ?>
    У вас запланированы задачи
    <table style="<?= $table_style; ?>">
        <tbody>
            <?php foreach ($tasks as $key => $task): ?>
                <tr>
                    <td style="<?= $cell_style; ?>"><?= htmlspecialchars($task['task_name']); ?></td>
                    <td style="<?= $cell_style; ?>"><?= htmlspecialchars($task['deadline']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
