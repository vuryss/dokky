# DOKKY

*Empower APIs with Seamless Documentation Precision*

[![codecov](https://codecov.io/github/vuryss/dokky/branch/master/graph/badge.svg?token=XXj2PesW0g)](https://codecov.io/github/vuryss/dokky)
![CodeRabbit Pull Request Reviews](https://img.shields.io/coderabbit/prs/github/vuryss/dokky?utm_source=oss&utm_medium=github&utm_campaign=vuryss%2Fdokky&labelColor=171717&color=FF570A&link=https%3A%2F%2Fcoderabbit.ai&label=CodeRabbit+Reviews)

## Overview

Dokky is a powerful developer tool designed to streamline the process of generating OpenAPI 3.1 documentation for PHP projects.

It lets you parse PHP classes and their properties, automatically generating the necessary OpenAPI schema definitions.

## Purpose

To be used inside frameworks for automatic generation of API Documentation (similar to API Platform).

## Features

- Provides OpenAPI class abstractions for easy manipulation of OpenAPI structures via PHP objects.
- Provides attributes for decorating classes and properties indicating how they should be represented in OpenAPI.
- Supports Symfony serializer and validator attributes for seamless integration.
- Generates OpenAPI Schemas based on PHP Classes and their properties. Correctly parses PHP Types and type annotations.

## Example usage:

### Overwriting single property's schema

```php
class DataWithSchemaOverwrite
{
    #[Property(
        schema: new Schema(
            description: 'Some description',
            examples: ['test1', 'test2'],
            enum: ['test1', 'test2', 'test3']
        )
    )]
    public string $property;
}
```

### Array schema handling

Array type can usually be declared in several ways, here are the two supported cases:

1. List of elements of given type. Represented as JSON array type. Must be sequentially indexed array.

- `array<Type>`
- `array<int, Type>`
- `Type[]`
- `list<Type>`
- `iterable<Type>`

Example JSON structure:
```json
[
    {/*Type object*/},
    {/*Type object*/}
]
```

2. Associative array of given type. Represented as JSON object type. JSON objects always have string keys, even if
  they are integers in PHP.

- `array<string, Type>`

Example JSON structure:
```json
{
    "key1": {/*Type object*/},
    "key2": {/*Type object*/}
}
```

Even with non-sequential integer keys, still JSON object:
```json
{
    "100": {/*Type object*/},
    "200": {/*Type object*/}
}
```

### Considering nullable properties as not-required

Sometimes, to be consistent with serializers, which can skip null values, you might want to consider nullable properties
as not-required. In this case, you can use a configuration option to switch the required property behavior.

This can be done by passing a configuration object to the class schema generator like this:

```php
$configuration = new \Dokky\Configuration(
    considerNullablePropertiesAsNotRequired: true,
);
new Dokky\ClassSchemaGenerator\ClassSchemaGenerator(configuration: $configuration)
```


### Full documentation example
```php
$componentsRegistry = new Dokky\ComponentsRegistry();
$componentsGenerator = new Dokky\ComponentsGenerator(
    componentsRegistry: $componentsRegistry,
    classSchemaGenerator: new Dokky\ClassSchemaGenerator\ClassSchemaGenerator(
        componentsRegistry: $componentsRegistry,
    ),
);

$openApi = new Dokky\OpenApi\OpenApi(
    openapi: '3.1.0',
    info: new Dokky\OpenApi\Info(
        title: 'Test API',
        version: '1.0.0',
        description: 'Auto generated documentation for Test API',
    ),
    paths: [
        '/user/{id}' => new Dokky\OpenApi\PathItem(
            get: new Dokky\OpenApi\Operation(
                operationId: 'get_user',
                responses: [
                    '200' => new Dokky\OpenApi\Response(
                        description: 'User data',
                        content: [
                            'application/json' => new Dokky\OpenApi\MediaType(
                                schema: new Dokky\OpenApi\Schema(
                                    ref: $componentsRegistry->getSchemaReference(User::class),
                                ),
                            ),
                        ],
                    ),
                ],
            ),
        ),
    ],
    components: $componentsGenerator->generateComponents(),
);
```

### Using Named Schemas

In some cases, you might want to define a specific name for a schema in the components section, rather than relying on the auto-generated name based on the class. You can achieve this using the `getNamedSchemaReference` method:

```php
$componentsRegistry = new Dokky\ComponentsRegistry();

// Register a class with a specific schema name
$userSchemaRef = $componentsRegistry->getNamedSchemaReference(
    className: User::class,
    schemaName: 'DetailedUserOutput'
); // Returns '#/components/schemas/DetailedUserOutput'

// You can still use the auto-generated name if needed elsewhere
$basicUserRef = $componentsRegistry->getSchemaReference(User::class); // Returns '#/components/schemas/User' (or similar if name conflicts)

// Use the named reference in your OpenAPI definition
$openApi = new Dokky\OpenApi\OpenApi(
    // ... other properties
    paths: [
        '/user/{id}' => new Dokky\OpenApi\PathItem(
            get: new Dokky\OpenApi\Operation(
                responses: [
                    '200' => new Dokky\OpenApi\Response(
                        description: 'Detailed user data',
                        content: [
                            'application/json' => new Dokky\OpenApi\MediaType(
                                schema: new Dokky\OpenApi\Schema(ref: $userSchemaRef), // Use the named reference
                            ),
                        ],
                    ),
                ],
            ),
        ),
    ],
    // ... components generation will include both 'DetailedUserOutput' and 'User' schemas
);

```

### Retrieving full list of used classes

For debugging purposes, you might need the full list of classes used in the OpenAPI schema.
Also, if you like to apply some caching based on whether any of the classes changed, you can use this method to get the
list of classes used in the OpenAPI schema.

```php
$componentsRegistry = new Dokky\ComponentsRegistry();
$componentsGenerator = new Dokky\ComponentsGenerator(
    componentsRegistry: $componentsRegistry,
    classSchemaGenerator: new Dokky\ClassSchemaGenerator\ClassSchemaGenerator(
        componentsRegistry: $componentsRegistry,
    ),
);

$openApi = new Dokky\OpenApi\OpenApi(
    // ... other properties
    components: $componentsGenerator->generateComponents(),
);

$usedClasses = $componentsRegistry->getUniqueClassNames();
```
