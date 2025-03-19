<?php

declare(strict_types=1);

test(
    'Full specification test',
    function (): void {
        $openApi = new Dokky\OpenApi\OpenApi(
            openapi: '3.1.0',
            info: new Dokky\OpenApi\Info(
                title: 'Test API',
                version: '1.0.0',
                description: 'This is a test API',
                termsOfService: 'https://example.com/terms',
                contact: new Dokky\OpenApi\Info\Contact(
                    name: 'John Doe',
                    url: 'https://example.com/contact',
                    email: 'sample@mail.com',
                ),
                license: new Dokky\OpenApi\Info\License(
                    name: 'MIT',
                    identifier: 'MIT',
                    url: 'https://example.com/license',
                ),
            ),
            jsonSchemaDialect: 'https://spec.openapis.org/oas/3.1/dialect/base',
            servers: [
                new Dokky\OpenApi\Server(
                    url: 'https://api.example.com/v1',
                    description: 'Production server',
                    variables: [
                        new Dokky\OpenApi\Server\ServerVariable(
                            default: 'v1',
                            enum: ['v1', 'v2'],
                            description: 'API version',
                        ),
                    ],
                ),
            ],
            paths: [
                '/user/{userId}' => new Dokky\OpenApi\PathItem(
                    summary: 'Get user information',
                    description: 'Retrieve user details',
                    get: new Dokky\OpenApi\Operation(
                        summary: 'Get user by ID',
                        description: 'Returns user information',
                        operationId: 'getUser',
                        parameters: [
                            new Dokky\OpenApi\Parameter(
                                name: 'userId',
                                in: Dokky\OpenApi\In::PATH,
                                description: 'ID of the user to retrieve',
                                required: true,
                                schema: new Dokky\OpenApi\Schema(
                                    type: Dokky\OpenApi\Schema\Type::STRING,
                                ),
                            ),
                        ],
                        responses: [
                            '200' => new Dokky\OpenApi\Response(
                                description: 'User found',
                                headers: [
                                    'X-Request-Id' => new Dokky\OpenApi\Header(
                                        description: 'Custom header',
                                        required: true,
                                        schema: new Dokky\OpenApi\Schema(
                                            type: Dokky\OpenApi\Schema\Type::STRING,
                                        ),
                                        example: new Dokky\OpenApi\Example(
                                            value: '12345',
                                        ),
                                    ),
                                ],
                                content: [
                                    'application/json' => new Dokky\OpenApi\MediaType(
                                        schema: new Dokky\OpenApi\Schema(
                                            type: Dokky\OpenApi\Schema\Type::OBJECT,
                                            properties: [
                                                'id' => new Dokky\OpenApi\Schema(
                                                    type: Dokky\OpenApi\Schema\Type::STRING,
                                                ),
                                                'name' => new Dokky\OpenApi\Schema(
                                                    type: Dokky\OpenApi\Schema\Type::STRING,
                                                ),
                                            ],
                                        ),
                                        encoding: [
                                            'id' => new Dokky\OpenApi\Encoding(
                                                contentType: 'text/plain',
                                            ),
                                        ],
                                    ),
                                ],
                                links: [
                                    'UserDetails' => new Dokky\OpenApi\Link(
                                        operationRef: '#/paths/user/{userId}/get',
                                        parameters: [
                                            'userId' => '$response.body#/id',
                                        ],
                                        description: 'Link to user details',
                                    ),
                                ],
                            ),
                            '401' => new Dokky\OpenApi\Reference(
                                ref: '#/components/responses/Unauthorized',
                            ),
                            '404' => new Dokky\OpenApi\Response(
                                description: 'User not found',
                            ),
                        ],
                    ),
                    post: new Dokky\OpenApi\Operation(
                        summary: 'Create a new user',
                        description: 'Creates a new user in the system',
                        operationId: 'createUser',
                        requestBody: new Dokky\OpenApi\RequestBody(
                            content: [
                                'application/json' => new Dokky\OpenApi\MediaType(
                                    schema: new Dokky\OpenApi\Schema(
                                        type: Dokky\OpenApi\Schema\Type::OBJECT,
                                        properties: [
                                            'name' => new Dokky\OpenApi\Schema(
                                                type: Dokky\OpenApi\Schema\Type::STRING,
                                            ),
                                        ],
                                    ),
                                ),
                            ],
                            required: true,
                        ),
                        responses: [
                            '201' => new Dokky\OpenApi\Response(
                                description: 'User created successfully',
                            ),
                            '400' => new Dokky\OpenApi\Response(
                                description: 'Invalid input data',
                            ),
                        ],
                    ),
                ),
            ],
            webhooks: [],
            components: new Dokky\OpenApi\Components(
                securitySchemes: [
                    new Dokky\OpenApi\SecurityScheme(
                        type: Dokky\OpenApi\SecurityScheme\Type::HTTP,
                        name: 'apiKey',
                        description: 'Bearer token authentication',
                    ),
                ],
            ),
            security: [
                ['apiKey' => []],
            ],
            tags: [
                new Dokky\OpenApi\Tag(
                    name: 'user',
                    description: 'User related operations',
                    externalDocs: new Dokky\OpenApi\ExternalDocumentation(
                        url: 'https://example.com/user-docs',
                        description: 'Find more info here',
                    ),
                ),
            ],
            externalDocs: new Dokky\OpenApi\ExternalDocumentation(
                url: 'https://example.com/docs',
                description: 'Find more info here',
            ),
        );

        expect($openApi)
            ->not->toBeEmpty();
    }
);
