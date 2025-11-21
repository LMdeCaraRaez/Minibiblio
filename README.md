# Minibiblio

Esto es una aplicación para la gestión de los libros, editoriales, usuarios y autores de una biblioteca

La aplicacioón contiene migraciones y fixtures ya preparados para su puesta en funcionamiento y cuenta con autenticacioón mediante roles y voters

### Requisitos para la instalación

- Tener al menos php 8.0 instalado
- Tener Git para clonar el repositorio
- Tener instalado Symfony
- Tener instalado Composer
- Tener OpenSSL instalado si utilizas windows
- Mysql para la base de datos

### Cómo iniciar el proyecto

- Clonar el repositorio y abrir una terminal en él 
- Ejecutar `composer install` para instalar las dependencias del proyecto
- Crear un archivo .env.local y añadir tu configuración de la BBDD con este formato:
`DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8&charset=utf8mb4"`
- Crear la base de datos con `php bin/console doctrine:database:create`
- Ejecutar las migraciones de la base de datos con `php bin/console doctrine:migrations:migrate`
- Cargar los datos de prueba con: `php bin/console doctrine:fixtures:load`
- Ejecutar el proyecto usando `symfony serve`
