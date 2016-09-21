# FTNW - fidonet online echomail/netmail reader for FTNS

Онлайн-система для регистрации поинтов, читалка/писалка для эх и нетмыла.

## Установка

Система написана, как компонент к Symfony, для тех кто с ним знаком думаю не будет сложностей в установке/настройке, но все же приведу последовательность по всем моментам:

### 1. Устанавливаем Symfony
Если у вас установлен Symfony-installer, то:
```
symfony new ftnw
```
Если нет:
```
composer create-project symfony/framework-standard-edition ftnw “3.1.*”
```
Инсталлер задаст несколько вопросов - хост DB, dbname, и т.д. - можно или указать сразу, либо сконфигурировать позже.
Инсталлятор создаст диру ftnw, внутри которой будут файлы фреймворка. 

### 2. Устанавливаем компонент FTNW
Заходим в созданную директорию
```
cd ftnw
```
Устанавливаем компонент
```
composer require igorgoroun/ftnw-bundle
```

### 3. Настройка web-сервера
Об этом можно подробно почитать [на сайте Symfony](http://symfony.com/doc/master/setup/web_server_configuration.html).


## Настройка FTNW
Компонент написан (пока что) так, чтобы использоваться как отдельное приложение, поэтому нужно настроить Symfony для правильной работы.

Все настройки будут делаться в директории <code>app/</code>

### 1. AppKernel.php
Включаем наш компонент (бандл):
```
$bundles = [
	—— *** ——
	new IgorGoroun\FTNWBundle\FTNWBundle(),
];
```

### 2. config/config.yml
Добавляем подключение параметров компонента:
```
imports:
	- { resource: '@FTNWBundle/Resources/config/parameters.yml' }
```



# FTNS
