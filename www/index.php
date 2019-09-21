<?php

require __DIR__ . '/../vendor/autoload.php';

use MyProject\Exceptions\DbException;
use MyProject\Exceptions\ForbiddenException;
use MyProject\Exceptions\NotFoundException;
use MyProject\Exceptions\UnauthorizedException;
use MyProject\Exceptions\UserActivationException;
use MyProject\Services\UsersAuthService;
use MyProject\View\View;

//mail('avs.artem@bk.ru', 'Тема письма', 'Текст письма', 'From: sk.akm777@gmail.com');
//return;

//$con = 1;
//$result = ($con) ? true : false;
//var_dump($result);
//return;

try {
//    spl_autoload_register(function (string $className) {
//        require_once __DIR__ . '/../src/' . $className . '.php';
//    });

    $route = $_GET['route'] ?? '';
    $routes = require __DIR__ . '/../src/routes.php';

    $isRouteFound = false;
    foreach ($routes as $pattern => $controllerAndAction) {
        preg_match($pattern, $route, $matches);
        if (!empty($matches)) {
            $isRouteFound = true;
            break;
        }
    }

    if (!$isRouteFound) {
        throw new NotFoundException();
    }

    unset($matches[0]);

    $controllerName = $controllerAndAction[0];
    $actionName = $controllerAndAction[1];

    $controller = new $controllerName();
    $controller->$actionName(...$matches);

//$pattern = '~^hello/(.*)$~';
//preg_match($pattern, $route, $matches);
//if (!empty($matches)) {
//    $controller = new \MyProject\Controllers\MainController();
//    $controller->sayHello($matches[1]);
//    return;
//}
//
//$pattern = '~^$~';
//preg_match($pattern, $route, $matches);
//if (!empty($matches)) {
//    $controller = new \MyProject\Controllers\MainController();
//    $controller->main();
//    return;
//}
//
//echo 'Страница не найдена';

//$author = new \MyProject\Models\Users\User('Иван');
//$article = new \MyProject\Models\Articles\Article('Заголовок', 'Текст', $author);
//var_dump($article);
} catch (DbException $e) {
    $view = new View(__DIR__ . '/../templates/errors');
    $view->renderHtml('500.php', ['error' => $e->getMessage()], 500);
} catch (UnauthorizedException $e) {
    $view = new View(__DIR__ . '/../templates/errors');
    $view->renderHtml('401.php', ['error' => $e->getMessage()], 401);
} catch (NotFoundException $e) {
    $view = new View(__DIR__ . '/../templates/errors');
    $view->renderHtml('404.php', [
        'error' => $e->getMessage(),
        'user' => UsersAuthService::getUserByToken()
    ], 404);
} catch (ForbiddenException $e) {
    $view = new View(__DIR__ . '/../templates/errors');
    $view->renderHtml('403.php', [
        'error' => $e->getMessage(),
        'user' => UsersAuthService::getUserByToken()
    ], 403);
}

//catch (UserActivationException $e) {
//    $view = new View(__DIR__ . '/../templates/users');
//    $view->renderHtml('activation.php', ['error' => $e->getMessage()]);
//}



