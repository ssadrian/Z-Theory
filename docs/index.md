# Gamifi-k

---

Gamifi-k ha sido el proyecto que hemos tenido que hacer para el fin de ciclo de DAW. En esta página podréis encontrar todo lo necesario para comprender cómo instalarlo y cómo usarlo. Tanto siendo alumno como profesor.

---

## Guía de instalación

Para poder servir el proyecto se necesitara varios programas

- [ ] [Node JS](https://nodejs.org/en/download)
- [ ] [PHP v8.2](https://www.php.net/downloads)
- [ ] [Composer](https://getcomposer.org/download/)

---

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
    php artisan migrate
    php artisan serve
    ```

A partir de aquí podrás navegar por toda la web.
