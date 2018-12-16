<?php

require_once('./bootstrap.php');

$email_config = require(ROOT_PATH . '/config/email.php');

$expired_tasks = getExpiredTasks();
if ($expired_tasks === false) {
    print(db_error());
    exit();
}

$data = array_reduce($expired_tasks, function($acc, $item) {
    $userid = $item['user_id'];

    $acc[$userid] = $acc[$userid] ?? [
        'user_name' => $item['user_name'],
        'user_email' => $item['user_email'],
        'tasks' => []
    ];

    array_push($acc[$userid]['tasks'], [
        'task_name' => $item['task_name'],
        'deadline' => $item['deadline']
    ]);

    return $acc;
}, []);

$transport = (new Swift_SmtpTransport($email_config['host'], $email_config['port']))
    ->setUsername($email_config['username'])
    ->setPassword($email_config['password']);

// $transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');

$mailer = new Swift_Mailer($transport);

$logger = new Swift_Plugins_Loggers_ArrayLogger();
$mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

foreach ($data as $item) {
    $message = new Swift_Message();
    $message->setSubject('Уведомление от сервиса «Дела в порядке»');
    $message->setFrom(['keks@phpdemo.ru' => 'DoingsDone Service']);
    $message->setTo([$item['user_email'] => $item['user_name']]);

    $msg_content = view(VIEWS_PATH . '/emails/expired.php', [
        'user' => $item['user_name'],
        'tasks' => $item['tasks']
    ]);
    $message->setBody($msg_content, 'text/html');

    $result = $mailer->send($message);

    if ($result) {
        print('Рассылка успешно отправлена');
    }
    else {
        print('Не удалось отправить рассылку: ' . $logger->dump());
    }
}
