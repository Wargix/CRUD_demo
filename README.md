Этот проект является результатом самообучения программированию на PHP.


index.php — авторизация:
    данные:
        логин
        пароль
    функции:
        проверка наличия сессии и перенаправление на профиль
        валидация данных
        поиск совпадения в БД
        создание сессии (авторизация) и перенаправление на профиль
        
sing_up.php — регистрация:
    данные:
        имя
        логин
        пароль
        почта
    функции:
        валидация данных
        проверка дублей в БД
        добавление данных в БД
        
email_verify.php — активация аккаунта по коду
    данные:
        код
    функции:
        проверка кода
        изменение статуса аккаунта
        
users.php — 
user.php — 
edit_user.php — 
add_user.php — 

db.php — 
secure.php — 


Функции:

☑ Work with MySQL throw PDO;

☑ Registration and authentication:\
filtration: tags, slashes, special chars
validation: empty fields, email, length strings
checks: free login

☑ password hashing with salt;\
☑ form tag action protect ($_SERVER['PHP_SELF']);\
☑ sessions;\

☑ подтверждение регистрации по почте;\
☑ перенаправление на профиль после регистрации и отправки кода подтверждения
☐ Проверка активации аккаунта, при не активированном аккаунте вывод сообщения и ссылка на повторную отправку кода активации.
    вынести код с отправкой письма активации в secure, сделать функцией
    сделать ссылку на скрипт посылающий письо с кодом из БД и сообщением

☑ защита от просмотра чужого профиля через GET
☑ загружать профиль не по id , а по login

☐ валидация данных после редактирования профиля
☐ валидация данных при авторизации
☐ валидация данных при добавлении пользователя админом
☐ валидация данных при добавлении задачи

☐ восстановление пароля по почте;\

☐ капча;\
☑ редактирование профиля;\
☑ замена пароля;\

☑ создание и редактирование списка задач.\
☑ создать таблицу задач
☑ страница списка задач
☑ страница задачи
☑ добавление задачи
☑ редактирование задачи
☑ удаление задачи
☑ добавить защиту от просмотра чужих задач

добавление аватара в профиль

Административные функции:
☐ аутентификация администратора;\
☑ список пользователей;\
☑ управления профилями пользователей\


Везде, где нужно подключение к БД включаем db.php
На все страницы которые нужно защитить авторизацией добавляем secure.php 