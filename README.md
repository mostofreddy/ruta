Ruta
====

Easy router package for any project

Version
-------

__1.1.0__

Features
--------


License
-------

This project is released under the [MIT License](http://www.opensource.org/licenses/mit-license.php)

Instalación
-----------

### Requirements

- PHP 5.4.*

### Github

    cd /var/www
    git clone https://github.com/rocket-code/ruta.git
    cd ruta
    composer install

### Composer

    "require": {
        "php": ">=5.4.0",
        "rocketcode/ruta": "*",
    }

Roadmap & issues
----------------

[Roadmap & issues](https://github.com/rocket-code/ruta/issues)

Changelog
---------

__1.1.0__

* Change namaspace to *\ruta*
* Add cache-system
* Add examples
* Travis-CI integration
* 

__1.0.1__

* Change composer configuration

__1.0.0__

* Se elimina retro-compatibilidad
* Implementación de PSR-4
* Se cambia el namaspace *mostofreddy/ruta* a *resty/ruta*
* Se elimina features para crear subrutas

Examples
========

[View examples](https://github.com/rocket-code/ruta/tree/master/example)

Docs
====

Bootstrap
---------

Start

    $routeCollection = new \ruta\RouteCollection();


Add base path

    \ruta\RouteBuilder::setSubdirectory('/fede/rocket/ruta/example/');

Creating Routes
---------------

    $routeCollection->append(
        \ruta\RouteBuilder::create()
            ->get('/', array("StaticClass", "callback"))
    )->append(
        \ruta\RouteBuilder::create()
            ->get('/work/\d+:id?', array(new ConcreteClass, "callback"))
            ->defaults(array('id' => 10))
    )->append(
        \ruta\RouteBuilder::create()
            ->get(
                '/users', function () {
                    echo "users!";
                }
            )
    );

Checking For Matches
--------------------

    try {
        $uri = $_SERVER["REQUEST_URI"];
        $method = $_SERVER["REQUEST_METHOD"];
        $route = $routeCollection->match(
            $method,
            $uri
        );
        $callback = $route->getCallback();
        $callback($route->getParams());
    } catch (\Exception $e) {
        echo $e->getMessage();
    }



Patterns
--------

### params

    $router->get(
        '/post/:id',
        function ($id) {
            echo "id: $id<br/>";
        }
    );

    $router->get(
        '/:controller/:action/:id',
        function ($params) {
            extract($params)
            echo "controller: $controller<br/>";
            echo "action: $action<br/>";
            echo "id: $id<br/>";
        }
    );

### Constraints

    $router->get(
        '/user|post:controller/get/\d+:id',
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

Defaults
--------

    $router->get(
        '/search/:q?',
        array(new \Foo(), 'search')
    )->defaults(array("q" => "empty search"));
