<?php

namespace MyProject\Controllers;

use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Exceptions\UserActivationException;
use MyProject\Models\Users\User;
use MyProject\Models\Users\UserActivationService;
use MyProject\Services\EmailSender;
use MyProject\Services\UsersAuthService;

class UsersController extends AbstractController
{

    public function logout()
    {
        UsersAuthService::deleteCookie();
        header('Location: /');
    }

    public function login()
    {
        if (!empty($_POST)) {
            try {
                $user = User::login($_POST);
                UsersAuthService::saveCookie($user);
                header('Location: /');
                exit();
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/login.php', ['error' => $e->getMessage()]);
                return;
            }
        }
        $this->view->renderHtml('users/login.php');
    }

    public function signUp()
    {
        if (!empty($_POST)) {
            try {
                $user = User::signUp($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/signUp.php', ['error' => $e->getMessage()]);
                return;
            }

            if ($user instanceof User) {
                $code = UserActivationService::createActivationCode($user);

                EmailSender::send($user, 'Активация', 'userActivation.php', [
                    'userId' => $user->getId(),
                    'code' => $code
                ]);

                $this->view->renderHtml('users/signUpSuccessful.php');
                return;
            }
        }

        $this->view->renderHtml('users/signUp.php');
    }

    public function activate(int $userId, string $activationCode): void
    {
        $error = null;
        $user = User::getById($userId);

        if ($user === null) {
            $error = 'Пользователь не найден';
        } elseif ($user->isConfirmed()) {
            $error = 'Пользователь уже активирован';
        } elseif (!UserActivationService::checkActivationCode($user, $activationCode)) {
            $error = 'Неверный код активации';
        } else {
            $user->activate();
            UserActivationService::deleteActivationCode($user);
        }

        $this->view->renderHtml('users/activation.php', ['error' => $error]);
    }

//    public function activate(int $userId, string $activationCode): void
//    {
//        $user = User::getById($userId);
//        if ($user === null) {
//            throw new UserActivationException('Пользователь не найден');
//        }
//        if ($user->isConfirmed()) {
//            throw new UserActivationException('Пользователь уже активирован');
//        }
//        $isCodeValid = UserActivationService::checkActivationCode($user, $activationCode);
//        if (!$isCodeValid) {
//            throw new UserActivationException('Неверный код активации');
//        }
//
//        $user->activate();
//        UserActivationService::deleteActivationCode($user);
//        $this->view->renderHtml('users/activation.php');
//    }

}