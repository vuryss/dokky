<?php

declare(strict_types=1);

namespace Dokky;

use Dokky\ClassSchemaGenerator\ClassSchemaGenerator;
use Dokky\OpenApi\Components;

readonly class ComponentsGenerator
{
    public function __construct(
        private ComponentsRegistry $componentsRegistry,
        private ClassSchemaGenerator $classSchemaGenerator,
    ) {
    }

    public function generateComponents(): Components
    {
        $components = new Components();

        $schemas = [];
        $hasNewSchema = true;

        while ($hasNewSchema) {
            $hasNewSchema = false;

            foreach ($this->componentsRegistry->getSchemaComponents() as $schemaComponentMetadata) {
                if (array_key_exists($schemaComponentMetadata['schemaName'], $schemas)) {
                    continue;
                }

                $schemas[$schemaComponentMetadata['schemaName']] = $this->classSchemaGenerator->generate(
                    className: $schemaComponentMetadata['className'],
                    groups: $schemaComponentMetadata['groups'],
                );

                $hasNewSchema = true;
            }
        }

        if ([] !== $schemas) {
            $components->schemas = $schemas;
        }

        return $components;
    }
}
