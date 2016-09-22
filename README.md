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

Общие настройки проекта будут делаться в директории <code>app/</code>

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
Ищем строку <code>validation:</code> и заменяем ее на:
```
validation: { enabled: true, enable_annotations: false }
```

### 3. config/parameters.yml
В этом файле можно и нужно поменять дефолтные установки для подключения к БД MySQL.

### 4. config/routing.yml
Все, что есть в файле - комментим и вставляем:
```
fidonews:
    resource: "@FTNWBundle/Resources/config/routing.yml"
    prefix:   /
```

### 5. config/security.yml
В конец файла добавляем:
```
imports:
    - { resource: '@FTNWBundle/Resources/config/security.yml' }
```

### 6. Создаем базу и схему
Для этого выходим в корень проекта и выполняем две команды:
```
bin/console doctrine:database:create
```
```
bin/console doctrine:schema:create
```

### 7. Настройки проекта
Все настройки, которые касаются ftnw находятся в одном файле, для удобства можно сделать на него симлинк, чтобы не лазить глубоко по директориям:
```
ln -s vendor/igorgoroun/ftnw-bundle/Resources/config/parameters.yml parameters.yml
```
Теперь можем отредактировать настройки в файле и переходить к настройке серверной части ноды - FTNS.
**node_api_passwd - не используется*.

# FTNS 
Серверная часть ноды, работает (пока) в связке с ifmail/ifunpack/ifpack.
[Описание и настройка серверной части](https://github.com/igorgoroun/FTNS)
