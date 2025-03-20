<?php

declare(strict_types=1);

test(
    'Can generate spec with components',
    function (): void {
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
                                            ref: $componentsRegistry->getSchemaReference(
                                                Dokky\Tests\Datasets\Classes\SpecGeneration\User::class,
                                            ),
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

        $content = cleanObject($openApi);

        expect($content)
            ->toBeValidJsonSchema();
    }
);
