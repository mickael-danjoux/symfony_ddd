# Simplified approach of DDD concept with symfony

This project is a simplified approach of DDD structure.

## Structure

### Domain layer
- All domain rules
- Interfaces
- Domain models
- No communication with other layers
- No dependency injections
- No Framework dependencies

### Infrastructure
- All symfony framework
- Persisted entities
- ORM
- Can communicate with Application layer

### Application
- Controllers and views
- DTOs to manage input and output data in domain
- Can communicate with Domain and Infrastructure layer

## Installation

#### Composer
```shell
symfony composer install
```

#### DB
```shell
symfony doctrine:database:create
```

#### Fixtures
```shell
symfony doctrine:fixtures:load
```

#### Start server
```shell
symfony serve
```

## Tests

DDD is great for tests because all domain rules are not depending on framework.
```shell
symfony php bin/phpunit
```
