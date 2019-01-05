<?php

class Constants
{
    public static $passwordsDoNotMatch = 'Подтверждение пароля не проведено! Попробуйте еще раз!';
    public static $passwordsDoNotValid = 'Пароль содержит недопустимые символы! Допустимы только числа и буквы';
    public static $passwordsNotLength = 'Пароль не может быть короче 6 символов и длиннее 25 символов';
    public static $emailsDoNotMatch = 'Введенные значение email не совпадают, попробуйте еще раз!';
    public static $emailsDoNotValid = 'Введенное значение email не соответствует шаблону!';
    public static $emailTaken = 'Данный email уже используется!';
    public static $lastNameNotLength = 'Фамилия пользователя не может быть короче 2 символов и длиннее 25 символов';
    public static $firstNameNotLength = 'Имя пользователя не может быть короче 2 символов и длиннее 25 символов';
    public static $usernameNotLength = 'Логин не может быть короче 5 символов и длиннее 25 символов';
    public static $usernameTaken = 'Данный пользователь уже существует!';
    public static $loginFail = 'Некорректный логин!';
}