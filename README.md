# Programming Challenge

[![Build Status](https://app.travis-ci.com/dundivet/refactored-challenge.svg?branch=main)](https://app.travis-ci.com/dundivet/refactored-challenge)

## Resumen

Como se sugiere en el enunciado del problema es posible crear una estructura siguiendo el modelo siguiente:

```plantuml
@startuml
package "ToDo Proyect" {
    class ToDoList {
        -title: string
        -description: text
        -due: datetime
        -completed: bool
    }

    class ToDoListItem {
        -task: string
        -description: text
    }

    class Tag {
        -name: string
    }

    class User {
        -username: string
    }

    ToDoListItem "*" *-- "1" ToDoList
    ToDoList "*" o--{ "*" Tag
    ToDoList "*" o-- "1" User
}
@enduml
```

![class model](./doc/imgs/class-model.png)

Sin embargo, para hacer un uso más práctico de los ToDo, se podría desarrollar una estructura en la que los propios ToDo fuesen sub-tareas de un ToDo padre y que al mismo tiempo tengan el mismo comportamiento de un ToDo independiente. Por lo que podrían adicionar sub-tareas, completarlos, etc. La propuesta quedaría de la siguiente forma:

```plantuml
@startuml
package "ToDo Proyect" {
    class ToDo {
        -title: string
        -description: text
        -due: datetime
        -completed: bool
    }

    class Tag {
        -name: string
    }

    class User {
        -username: string
    }

    ToDo "*" o-- "1" ToDo
    ToDo "*" o--{ "*" Tag
    ToDo "*" o-- "1" User
}
@enduml
```

![class model](./doc/imgs/class-model-refactored.png)

## Instrucciones de instalación

```shell
composer install --optimize-autoloaders

php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doc:fixtures:load --no-interaction --purge-with-truncate
```

## Usando Docker

```shell
docker compose up -d
docker compose exec web sh -c "composer install -o -q && yarn install && yarn build"
docker compose exec web sh -c "php bin/console doctrine:database:create && php bin/console doctrine:migrations:migrate --no-interaction && php bin/console doc:fixtures:load --no-interaction --purge-with-truncate"
```

## Credenciales de acceso

Como parte del entorno de pruebas desarrollado al generar los `Fixtures` se genera un usuario `Admin` específico para el entorno de desarrollo y pruebas:

```
username/email: admin@example
password: adminpass
```
