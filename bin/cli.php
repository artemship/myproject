<?php

require __DIR__ . '/../vendor/autoload.php';

use MyProject\Cli\AbstractCommand;
use MyProject\Exceptions\CliException;

try {
    unset($argv[0]);

    //Регистрируем функцию автозагрузки
//    spl_autoload_register(function (string $className) {
//       require_once __DIR__ . '/../src/' . $className . '.php';
//    });

    // Составляем полное имя класса, добавив нэймспейс
    $className = '\\MyProject\\Cli\\' . array_shift($argv);

    //почему-то не проходит проверка class_exists
    if (!class_exists($className)) {
        throw new CliException('Class "' . $className . '" not found');
    }

//    echo 'hi';
//    exit();

    $object = new ReflectionClass($className);
    if (!$object->isSubclassOf(AbstractCommand::class)) {
        throw new CliException('Class ' . $className . ' is not descendant of AbstractCommand class');
    }

    $params = [];
    foreach ($argv as $argument) {
        preg_match('/^-(.+)=(.+)$/', $argument, $matches);
        if (!empty($matches)) {
            $paramName = $matches[1];
            $paramValue = $matches[2];
            $params[$paramName] = $paramValue;
        }
    }

    // Создаём экземпляр класса, передав параметры и вызываем метод execute()
    $class = new $className($params);
    $class->execute();

} catch (CliException $e) {
    echo 'Error: ' . $e->getMessage();
}
