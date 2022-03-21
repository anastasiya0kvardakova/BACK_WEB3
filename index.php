<?php
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // В суперглобальном массиве $_GET PHP хранит все параметры, переданные в текущем запросе через URL.
    if (!empty($_GET['save'])) {
        // Если есть параметр save, то выводим сообщение пользователю.
        print('Спасибо, результаты сохранены.');
    }
    // Включаем содержимое файла form.php.
    include('form.php');
    // Завершаем работу скрипта.
    exit();
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
// Проверяем ошибки.
$errors = FALSE;
if (empty($_POST['name'])) {
    print('Заполни имя.<br/>');
    $errors = TRUE;
}
if (empty($_POST['email'])) {
    print('Заполни email.<br/>');
    $errors = TRUE;
}
if (empty($_POST['date'])) {
    print('Выбери дату.<br/>');
    $errors = TRUE;
}
if (empty($_POST['gender'])) {
    print('Выбери пол.<br/>');
    $errors = TRUE;
}
if (empty($_POST['limbs'])) {
    print('Выбери количество конечностей.<br/>');
    $errors = TRUE;
}
if (empty($_POST['select'])) {
    print('Выбери суперспособнос(ть/ти).<br/>');
    $errors = TRUE;
}
if (empty($_POST['bio'])) {
    print('Расскажи о себе.<br/>');
    $errors = TRUE;
}
if (empty($_POST['policy'])) {
    print('Ознакомся с политикой конфиденциальности.<br/>');
    $errors = TRUE;
}

if ($errors) {
    // При наличии ошибок завершаем работу скрипта.
    exit();
}

// Сохранение в базу данных.
$name = $_POST['name'];
$email = $_POST['email'];
$date = $_POST['date'];
$gender = $_POST['gender'];
$limbs = (int)$_POST['limbs'];
$bio = $_POST['bio'];
$policy = $_POST['policy'];
$powers = implode(',', $_POST['select']);

$user = 'u47584';
$pass = '3864156';
$db = new PDO('mysql:host=localhost;dbname=u47584', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

// Подготовленный запрос. Не именованные метки.
try {
    $stmt = $db->prepare("INSERT INTO clients SET name = ?, email = ?, date = ?, gender = ?, limbs = ?, bio = ?, policy = ?");
    $stmt->execute(array($name, $email, $date, $gender, $limbs, $bio, $policy));
    $user_id = $db->lastInsertId();

    $superpowers = $db->prepare("INSERT INTO superpowers SET powers = ?, userID = ? ");
    $superpowers->execute(array($powers, $users_id));
} catch (PDOException $e) {
    print('Error : ' . $e->getMessage());
    exit();
}

//  stmt - это "дескриптор состояния".

// Делаем перенаправление.
// Если запись не сохраняется, но ошибок не видно, то можно закомментировать эту строку чтобы увидеть ошибку.
// Если ошибок при этом не видно, то необходимо настроить параметр display_errors для PHP.
header('Location: ?save=1');