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

Array type can usually be declared in several ways, however due to how symfony property info parses those, there are few
differences that you should be aware of:

- `array<Type>` - List of items of type `Type`, in terms of JSON this is the usual array type

Use this when you expect structure like this:
```json
[
    {/*Type object*/},
    {/*Type object*/}
]
```

- `array<string, Type>` - Associative array with string keys and values of type `Type`, in terms of JSON this is an object
  which accepts any string as a key and values of type `Type`

Use this when you expect structure like this:
```json
{
    "key1": {/*Type object*/},
    "key2": {/*Type object*/}
}
```

- `array<int, Type>` - Associative array with integer keys and values of type `Type`, in terms of JSON this is an object
  which accepts any integer as a key (non-sequential integer keys are objects in JSON terms) and values of type `Type`

Use this when you expect structure like this:
```json
{
    "200": {/*Type object*/},
    "400": {/*Type object*/}
}
```

- `Type[]` - Same as `array<int, Type>` due to how symfony property info parses this type. Please use only `array<Type>`
  for actual lists, cause this makes the schema expect an object with numeric keys instead of an array.

Use this when you expect structure like this:
```json
{
    "200": {/*Type object*/},
    "400": {/*Type object*/}
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
