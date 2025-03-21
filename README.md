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
