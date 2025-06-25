<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Проверяем метод запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Метод не разрешен']);
    exit;
}

// Получаем данные из POST запроса
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Если данные не в JSON формате, получаем из $_POST
if (!$data) {
    $data = $_POST;
}

// Валидация данных
$name = isset($data['name']) ? trim($data['name']) : '';
$phone = isset($data['phone']) ? trim($data['phone']) : '';
$email = isset($data['email']) ? trim($data['email']) : '';
$message = isset($data['message']) ? trim($data['message']) : '';

// Проверка обязательных полей
if (empty($name) || empty($phone) || empty($email)) {
    echo json_encode([
        'success' => false, 
        'message' => 'Пожалуйста, заполните все обязательные поля'
    ]);
    exit;
}

// Валидация email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false, 
        'message' => 'Некорректный email адрес'
    ]);
    exit;
}

// Настройки email
$to = 'solnischho@solnischho.ru'; // Замените на ваш email
$subject = 'Новая заявка на экскурсию - Детский сад "Солнышко"';

// Формируем тело письма
$email_body = "
<html>
<head>
    <meta charset='UTF-8'>
    <title>Новая заявка на экскурсию</title>
</head>
<body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
    <div style='max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>
        <h2 style='color: #4A90E2; text-align: center; margin-bottom: 30px;'>
            🌞 Новая заявка на экскурсию
        </h2>
        
        <div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;'>
            <h3 style='color: #333; margin-top: 0;'>Информация о заявителе:</h3>
            
            <p><strong>Имя:</strong> " . htmlspecialchars($name) . "</p>
            <p><strong>Телефон:</strong> " . htmlspecialchars($phone) . "</p>
            <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
            
            " . (!empty($message) ? "<p><strong>Сообщение:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>" : "") . "
        </div>
        
        <div style='background: #e3f2fd; padding: 15px; border-radius: 8px; border-left: 4px solid #4A90E2;'>
            <p style='margin: 0; font-size: 14px; color: #666;'>
                <strong>Дата подачи заявки:</strong> " . date('d.m.Y H:i:s') . "<br>
                <strong>IP адрес:</strong> " . $_SERVER['REMOTE_ADDR'] . "
            </p>
        </div>
        
        <div style='text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;'>
            <p style='color: #666; font-size: 12px; margin: 0;'>
                Это письмо отправлено автоматически с сайта детского сада \"Солнышко\"
            </p>
        </div>
    </div>
</body>
</html>
";

// Заголовки для email
$headers = array(
    'MIME-Version: 1.0',
    'Content-type: text/html; charset=utf-8',
    'From: noreply@solnyshko-ds.ru',
    'Reply-To: ' . $email,
    'X-Mailer: PHP/' . phpversion()
);

// Отправляем email
$mail_sent = mail($to, $subject, $email_body, implode("\r\n", $headers));

if ($mail_sent) {
    // Логируем успешную отправку (опционально)
    $log_entry = date('Y-m-d H:i:s') . " - Заявка от: $name ($email, $phone)\n";
    file_put_contents('form_submissions.log', $log_entry, FILE_APPEND | LOCK_EX);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Заявка успешно отправлена! Мы свяжемся с вами в ближайшее время.'
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Ошибка при отправке заявки. Пожалуйста, попробуйте позже или свяжитесь с нами по телефону.'
    ]);
}
?>