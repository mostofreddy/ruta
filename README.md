Ruta
====

Simple sistema de ruteo para PHP.

(inspirado y basado en [bistro/router](https://github.com/bistro/router))


Versión
-------

- __v0.2.0__ desarrollo

Licencia
-------

[MIT License](http://www.opensource.org/licenses/mit-license.php)

Instalación
-----------

### Requerimientos

- PHP 5.4.*

### Github

    cd /var/www
    git clone git@github.com:mostofreddy/ruta.git

### Composer

    "require": {
        "php": ">=5.4.0",
        "mostofreddy/ruta": "*",
    }

API
===

Agregar rutas
-------------

$router = new \ruta\Router();
$router->get('/usuarios/get/:id');
$router->post('/usuarios/:action/:id');
