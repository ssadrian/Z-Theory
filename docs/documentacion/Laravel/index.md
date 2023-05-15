# Laravel

Laravel es un framework de desarrollo web de código abierto y basado en PHP. Fue creado por Taylor Otwell con el objetivo de facilitar y agilizar el proceso de desarrollo de aplicaciones web. Laravel utiliza una arquitectura MVC (Modelo-Vista-Controlador) que permite una separación clara de la lógica de negocio, la presentación de datos y la interacción con el usuario.

Entre las características destacadas de Laravel se encuentran su elegante sintaxis, su amplia gama de funciones y herramientas integradas, y su enfoque en la seguridad y la eficiencia. Laravel ofrece una gran cantidad de componentes y librerías predefinidas, como la gestión de bases de datos, el enrutamiento, la autenticación de usuarios, el caché y el manejo de sesiones, lo que facilita el desarrollo de aplicaciones robustas y escalables.

## Comandos útiles

Para desarrollar el proyecto con más eficiencia, creamos dos comandos diferentes que nos ayudaron a la hora de poner datos falsos a la base de datos. Mejor conocidos como seeders.


### Composer fresh

Para poder migrar la base de datos y llenarla con datos del seeder se debe hacer:

```
composer fresh
```

### Fresh start

Otro comando útil es fresh start. Crea una migración de la base de datos, aplica los seeders y inicia el servidor

```
composer fresh:start
``` 