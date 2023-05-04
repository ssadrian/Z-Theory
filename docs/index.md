# Gamifi-k

---

Gamifi-k ha sido el proyecto que hemos tenido que hacer para el fin de ciclo de DAW. En esta página podréis encontrar todo lo necesario para comprender cómo instalarlo y cómo usarlo. Tanto siendo alumno como profesor.

---

## **Guía de instalación**

Para poder servir el proyecto vamos a necesitar varias dependencias.

1. Tener instalado [NodeJS](https://nodejs.org/es/download)
2. Tener instalado [PHP 8.2 ](https://www.php.net/downloads) 
3. Tener instalado [Composer](https://getcomposer.org/download/)

--- 

Una vez instaladas las dependencias necesarias, se requerirá ejecutar varios comandos para poder servir el proyecto y verlo en acción. 

Dentro del proyecto encontrarás dos archivos diferentes. El primero que deberás ejecutar es
`setup.ps1`
Este comando lo que hará es migrar la base de datos, copiar el fichero .env y instalar las dependencias necesarias por parte de Angular.


El siguiente que deberás usar es `run.ps1` lo que hará que el proyecto se sirva y lo puedas ver en tu navegador desde la dirección: `localhost:80`. A partir de aquí podrás navegar por toda la web.

**IMPORTANTE ⚠️:** Si ves que te salta algún tipo de error de permisos deberás ejecutar el siguiente comando en la terminal: 

```shell Set-ExecutionPolicy -Scope CurrentUser -ExecutionPolicy Unrestricted``` 

Si luego quieres quitar esto, deberás colocar este otro comando en la terminal: 

```shell Set-ExecutionPolicy -Scope CurrentUser -ExecutionPolicy Default```

--- 

Si no quieres usar ninguno de estos archivos también está disponible la opción de hacerlo manualmente.

Para la parte de Angular (Frontend) deberás ir al directorio de Frontend y ejecutar el siguiente comando: `npm install`

Para la parte de Laravel (Backend) deberás ir al directorio de Backend y ejecutar los siguientes comandos: `composer install`, `php artisan migrate` y `php artisan serve`. 

A partir de aquí podrás navegar por toda la web.