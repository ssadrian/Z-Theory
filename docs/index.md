# Gamifi-k

---

Gamifi-k ha sido el proyecto que hemos tenido que hacer para el fin de ciclo de DAW. En esta página podréis encontrar todo lo necesario para comprender cómo instalarlo y cómo usarlo. Tanto siendo alumno como profesor.

---

## Descarga del proyecto

Para descargar el proyecto puedes acceder al GitHub y descargar el fichero [zip](https://github.com/ssadrian/Z-Theory/archive/refs/heads/main.zip).

---

## Guía de instalación

Para poder servir el proyecto se necesitara varios programas

- [ ] [Node JS](https://nodejs.org/en/download)
- [ ] [PHP v8.2](https://www.php.net/downloads) o mayor
- [ ] [Composer](https://getcomposer.org/download/)

---

### Comprobación de la instalación

Para comprobar que se instaló correctamente, puedes usar la consola del sistema y usar los siguientes comandos:

```ps1
$ node -v
v16.17.0

$ php -v
PHP 8.2.3 (cli) (built: Feb 14 2023 09:55:52) (ZTS Visual C++ 2019 x64)
Copyright (c) The PHP Group
Zend Engine v4.2.3, Copyright (c) Zend Technologies

$ composer --version
Composer version 2.4.1 2022-08-20 11:44:50
```

Reinicia la consola al finalizar toda la instalación.

---

## Set up

Una vez instaladas las dependencias necesarias, se requerirá ejecutar varios comandos para poder servir el proyecto y verlo en acción.

Dentro del proyecto encontrarás dos archivos diferentes. El primero que deberás ejecutar es
`setup.ps1`
Este comando lo que hará es migrar la base de datos, copiar el fichero .env y instalar las dependencias necesarias por parte de Angular.

El siguiente que deberás usar es `run.ps1` lo que hará que el proyecto se sirva y lo puedas ver en tu navegador desde la dirección: `localhost`.

???+ warning "Error '... cannot be loaded because running scripts is disabled on this system.'"

    Por defecto, PowerShell evitará ejecutar cualquier script para mitigar problemas de seguridad.
    En caso de recibir este error, con una consola PowerShell en modo administrador se puede desactivar esta
      función de seguridad.

    ```ps1
    Set-ExecutionPolicy -Scope CurrentUser -ExecutionPolicy Unrestricted
    ```

    Si luego quieres quitar esto, deberás colocar este otro comando en la terminal:

    ```ps1
    Set-ExecutionPolicy -Scope CurrentUser -ExecutionPolicy Default
    ```

---

Si no quieres usar ninguno de estos archivos también está disponible la opción de hacerlo manualmente.

=== "Angular (Frontend)"
    En el directorio Frontend, ejecuta el siguiente comando:

    ```
    npm install
    ```

=== "Laravel (Backend)"
    En el directorio Backend, ejecuta los siguientes comandos:

    ```
    composer install
    cp .env.example .env
    php artisan migrate
    php artisan serve
    ```

A partir de aquí podrás navegar por toda la web.
