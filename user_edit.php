<?php
require_once 'includes/db.php';
require_once 'includes/secure.php';
require_once 'includes/validate.php';
try {
    if (isset($_POST['update_user'])) {

        // password was not edit
        if ($_POST['password'] == '') {

            // Validation - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
            $form_data = array(
                'full_name' => $_POST['full_name'],
                'email' => $_POST['email']
            );

            $form_data = clean($form_data); // clean() locate in validate.php

            // проверка на пустые значения
            if(empty($form_data['full_name']) OR empty($form_data['email'])) {
                exit('Заполните все значения.');
            }

            // валидация эл. почты
            $email_validate = filter_var($form_data['email'], FILTER_VALIDATE_EMAIL);

            // проверка длинны данных
            if (!check_length($form_data['full_name'], 2, 255)) {
                exit('Name long must be between 2 and 255 characters.');
            }
            if (!$email_validate) {
                exit('Enter correct e-mail.');
            }
            // End Validation - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

            // Checking an existing e-mail
            if ($_SESSION['user']['email'] != $form_data['email']) {
                $stmt = $pdo->prepare(SQL_EMAIL);
                $stmt->bindParam(':email', $form_data['email']);
                $stmt->execute();
                $user_count = $stmt->rowCount();
                if ($user_count > 0 ) {
                    exit('E-mail already exists.');
                }
            }

            // update DB
            $stmt = $pdo->prepare(SQL_UPDATE_USER);
            $stmt->bindParam(':full_name',  $form_data['full_name']);
            $stmt->bindParam(':email',  $form_data['email']);
            $stmt->bindParam(':login',      $_SESSION['user']['login']);
            $stmt->execute();
            header('Location: ./user.php?user=' . $_SESSION['user']['login']);

        // password was edit
        } else {

            // Validation - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
            $form_data = array(
                'full_name' => $_POST['full_name'],
                'email' => $_POST['email'],
                'password' => $_POST['password']
            );
            $form_data = clean($form_data); // clean() locate in validate.php

            // E-mail validation
            $email_validate = filter_var($form_data['email'], FILTER_VALIDATE_EMAIL);

            // Data length check
            if (!check_length($form_data['full_name'], 2, 255)) {
                exit('Name long must be between 2 and 255 characters.');
            }
            if (!check_length($form_data['password'], 2, 64)) {
                exit('Password long must be between 2 and 255 characters.');
            }
            if (!$email_validate) {
                exit('Enter correct e-mail.');
            }
            // End Validation - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

            // Checking an existing e-mail
            if ($_SESSION['user']['email'] != $form_data['email']) {
                $stmt = $pdo->prepare(SQL_EMAIL);
                $stmt->bindParam(':email', $form_data['email']);
                $stmt->execute();
                $user_count = $stmt->rowCount();
                if ($user_count > 0 ) {
                    exit('E-mail already exists.');
                }
            }

            if ($form_data['password'] == $_POST['password_confirm']) {
                // password hashing
                try {
                    $form_data['password'] = password_hash($form_data['password'], PASSWORD_DEFAULT);
                } catch (Exception $e) {
                    echo '== HASH ERROR: == ' . $e->getMessage();
                }
                $_SESSION['user']['password'] = $form_data['password']; // update password in session

                // update DB
                $stmt = $pdo->prepare(SQL_UPDATE_USER_EXTENDED);
                $stmt->bindParam(':full_name',  $form_data['full_name']);
                $stmt->bindParam(':email',  $form_data['email']);
                $stmt->bindParam(':password',   $form_data['password']);
                $stmt->bindParam(':login',      $_SESSION['user']['login']);
                $stmt->execute();
                header('Location: ./user.php?user=' . $_SESSION['user']['login']);
            } else {
                exit('Введённые пароли не совпали.');
            }
        }
    }
    $stmt = $pdo->prepare(SQL_GET_USER);
    $stmt->execute([':login' => $_SESSION['user']['login']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo '=== PDO EXCEPTION ===: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <?php include_once 'includes/statistics.html' ?>
    <title><?php echo($user['full_name']); ?></title>
    <?php include_once 'includes/menu.php' ?>
<div class="mdl-grid">
    <div class="mdl-cell mdl-cell--12-col">
        <h1><?php echo($user['full_name']); ?></h1>
    </div>
</div>

<div class="mdl-grid" id="buttons">
    <div class="mdl-cell mdl-cell--12-col">
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored"
                form="edit_user" type="submit" name="update_user">
            Сохранить
        </button>
        <a class="mdl-button mdl-js-button mdl-button--raised" href="user.php?user=<?php echo($_SESSION['user']['login'])?>">
            Отмена
        </a>
    </div>
</div>
<article class="mdl-grid main-content">
    <div class="mdl-cell mdl-cell--12-col">
        <form action="" method="post" id="edit_user">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" id="full_name"
                       name="full_name" value="<?php echo $user['full_name'] ?>">
                <label class="mdl-textfield__label" for="full_name">Name</label>
            </div>
            <br>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" id="email"
                       name="email" value="<?php echo $user['email'] ?>">
                <label class="mdl-textfield__label" for="email">E-mail</label>
            </div>
            <br>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" id="password"
                       name="password">
                <label class="mdl-textfield__label" for="password">Password</label>
            </div>
            <br>
            <br>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" id="password_confirm"
                       name="password_confirm">
                <label class="mdl-textfield__label" for="password_confirm">Password confirm</label>
            </div>
            <br>
        </form>
    </div>
</article>
<?php include_once 'includes/footer.html' ?>
