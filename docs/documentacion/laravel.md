# Laravel

## Descripción

Laravel es un framework de desarrollo web de código abierto y basado en PHP. Fue creado por Taylor Otwell con el objetivo de facilitar y agilizar el proceso de desarrollo de aplicaciones web. Laravel utiliza una arquitectura MVC (Modelo-Vista-Controlador) que permite una separación clara de la lógica de negocio, la presentación de datos y la interacción con el usuario.

Entre las características destacadas de Laravel se encuentran su elegante sintaxis, su amplia gama de funciones y herramientas integradas, y su enfoque en la seguridad y la eficiencia. Laravel ofrece una gran cantidad de componentes y librerías predefinidas, como la gestión de bases de datos, el enrutamiento, la autenticación de usuarios, el caché y el manejo de sesiones, lo que facilita el desarrollo de aplicaciones robustas y escalables.

## Comandos útiles

Para desarrollar el proyecto con más eficiencia, creamos dos comandos diferentes que nos ayudaron a la hora de poner datos falsos a la base de datos. Mejor conocidos como seeders.

### fresh

Elimina los datos, la estructura del BBDD y ejecuta las migraciónes.
Despues rellena la BBDD con unos datos de relleno.

```sh
composer fresh
```

### fresh:start

Ejecuta el comando [fresh](#fresh) y inicia el servidor de desarrollo.

??? warning

    Los comando de 'composer' tienen un timeout de 300s por defecto.
    Para más información en como cambiar esta opción, entra en la pagina de [composer](https://getcomposer.org/doc/articles/scripts.md#managing-the-process-timeout).

```sh
composer fresh:start
```
