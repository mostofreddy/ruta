Ruta
====

Simple sistema de ruteo para PHP.

(inspirado y basado en [bistro/router](https://github.com/bistro/router))

Versión
-------

- __1.0.1__

Licencia
-------

[MIT License](http://www.opensource.org/licenses/mit-license.php)

Instalación
-----------

### Requerimientos

- PHP 5.4.*

### Github

    cd /var/www
    git clone git@github.com:restty/ruta.git
    cd ruta
    composer install

### Composer

    "require": {
        "php": ">=5.4.0",
        "restty/ruta": "*",
    }

Roadmap & issues
----------------

[Roadmap & issues](https://github.com/restty/ruta/issues)

Changelog
---------

__1.0.1__

* Cambios en la configuración de composer

__1.0.0__

* Se elimina retro-compatibilidad
* Implementación de PSR-4
* Se cambia el namaspace *mostofreddy/ruta* a *resty/ruta*
* Se elimina features para crear subrutas

API
===

Bootstrap
---------

Al instanciar la clase Router se debe configurar el subdirectorio y el cache del objeto Route.

El primero es necesario para cuando el site que se esta creando se encuentra en un subdirectorio (ej: http://misitio.com/miproyecto).

El segundo es por un tema de rendimiento, por cada ruta seteada es necesaria una nueva instancia de Route, pero como en PHP es menos costoso clonar un objeto que instanciarlo se utilizará una cache.

    $router = new \resty\ruta\Router();
    $router->setSubDirectory('ruta') //optional
        ->cache(new \resty\ruta\Route());

Crear rutas
-----------

    // Closure como callback
    $router->get(
        '/',
        function ($params) {
            echo "root<br/>";
            echo "params: ".json_encode($params);
            echo "<br/>";
        }
    )->defaults(array("nombre" => "mostofreddy"));

    //método estático como callback
    $router->post(
        '/statics',
        array("Foo", 'statics')
    )

    //método concreto como callback
    $router->get(
        '/concrete',
        array(new \Foo(), 'concrete')
    ));

Machear rutas
-------------

    try {
        $uri = $_SERVER["REQUEST_URI"];
        $method = $_SERVER["REQUEST_METHOD"];
        echo $method." - ".$uri."<br/>";
        $route = $router->match(
            $method,
            $uri
        );
        if ($route !== false) {
            $callback = $route->getCallback();
            $callback($route->getParams());
        }
        echo "<br/>Done!";
    } catch (\Exception $e) {
        echo $e->getMessage();
    }

Patterns para rutas
-------------------

### parametros

    $router->get(
        '/post/:id',
        function ($id) {
            echo "id: $id<br/>";
        }
    );
    ....
    $route = $router->match($method, $uri);
    if ($route !== false) {
        $callback = $route->getCallback();
        $params = $route->getParams();
        $callback($params['id']);
    }

    $router->get(
        '/:controller/:action/:id',
        function ($params) {
            extract($params)
            echo "controller: $controller<br/>";
            echo "action: $action<br/>";
            echo "id: $id<br/>";
        }
    );
    ....
    $route = $router->match($method, $uri);
    if ($route !== false) {
        $callback = $route->getCallback();
        $callback($route->getParams());
    }

### Constraints

    $router->get(
        '/user|post:controller/get/\d:id',
        function ($params) {
            .....
        }
    );


### Wildcard

    $router->get(
        '/:controller/.*:action/\d:id',
        function ($params) {
            .....
        }
    );

Valores por defecto
-------------------

    $router->get(
        '/search/:q?',
        array(new \Foo(), 'search')
    )->defaults(array("q" => "nada q buscar"));
