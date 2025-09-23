<?php

declare(strict_types=1);

namespace Dokky\ClassSchemaGenerator;

use Dokky\ComponentsRegistry;
use Dokky\DokkyException;
use Dokky\OpenApi\Schema;
use Dokky\Undefined;
use Symfony\Component\TypeInfo\Type;
use Symfony\Component\TypeInfo\TypeIdentifier;

final readonly class TypeMapper
{
    public function __construct(
        private ComponentsRegistry $componentsRegistry,
    ) {
    }

    /**
     * @param array<string>|null $groups
     */
    public function typeToSchema(
        Type $type,
        ?array $groups = null,
    ): Schema {
        if ($type instanceof Type\BackedEnumType) {
            /** @var class-string $className */
            $className = $type->getClassName();

            return new Schema(ref: $this->componentsRegistry->getSchemaReference($className));
        }

        if ($type instanceof Type\BuiltinType) {
            return match ($type->getTypeIdentifier()) {
                TypeIdentifier::INT => new Schema(type: Schema\Type::INTEGER),
                TypeIdentifier::FLOAT => new Schema(type: Schema\Type::NUMBER, format: 'float'),
                TypeIdentifier::STRING => new Schema(type: Schema\Type::STRING),
                TypeIdentifier::BOOL => new Schema(type: Schema\Type::BOOLEAN),
                TypeIdentifier::TRUE => new Schema(type: Schema\Type::BOOLEAN, enum: [true]),
                TypeIdentifier::FALSE => new Schema(type: Schema\Type::BOOLEAN, enum: [false]),
                TypeIdentifier::NULL => new Schema(type: Schema\Type::NULL),
                TypeIdentifier::OBJECT => new Schema(type: Schema\Type::OBJECT),
                default => throw new DokkyException(sprintf('Unsupported type "%s"', $type)),
            };
        }

        if ($type instanceof Type\CollectionType) {
            if ($type->isList()) {
                return new Schema(
                    type: Schema\Type::ARRAY,
                    items: $this->typeToSchema($type->getCollectionValueType(), $groups),
                );
            }

            // Special case: Symfony Type doesn't distinguish between array<T> (implicit numeric keys) and
            // array<int|string, T> (explicit mixed keys) in docblocks. Since both cases typically represent
            // list-like structures in PHP, we treat int|string keys as lists to avoid manual docblock parsing.
            // Future enhancement: Add docblock parser for precise key type detection if needed.
            $keyType = $type->getCollectionKeyType();
            $keyTypeTreatedAsList = Type::union(Type::int(), Type::string());

            if (
                ($keyType instanceof Type\BuiltinType && TypeIdentifier::INT === $keyType->getTypeIdentifier())
                || (string) $keyType == (string) $keyTypeTreatedAsList
            ) {
                return new Schema(
                    type: Schema\Type::ARRAY,
                    items: $this->typeToSchema($type->getCollectionValueType(), $groups),
                );
            }

            if ($keyType instanceof Type\BuiltinType && TypeIdentifier::STRING === $keyType->getTypeIdentifier()) {
                return new Schema(
                    type: Schema\Type::OBJECT,
                    additionalProperties: $this->typeToSchema($type->getCollectionValueType(), $groups),
                );
            }

            throw new DokkyException('Associative arrays with non-string/int keys or multiple key types are not supported.');
        }

        if ($type instanceof Type\EnumType) {
            throw new DokkyException('EnumType not supported. Use BackedEnumType instead.');
        }

        if ($type instanceof Type\GenericType) {
            throw new DokkyException('GenericType not supported.');
        }

        if ($type instanceof Type\IntersectionType) {
            throw new DokkyException('IntersectionType not supported.');
        }

        if ($type instanceof Type\NullableType) {
            $wrappedSchema = $this->typeToSchema($type->getWrappedType(), $groups);

            if (Undefined::VALUE !== $wrappedSchema->anyOf) {
                /** @var array<Schema> $anyOf */
                $anyOf = $wrappedSchema->anyOf;
                $anyOf[] = new Schema(type: Schema\Type::NULL);

                $wrappedSchema->anyOf = $anyOf;

                return $wrappedSchema;
            }

            return new Schema(
                anyOf: [
                    $wrappedSchema,
                    new Schema(type: Schema\Type::NULL),
                ]
            );
        }

        if ($type instanceof Type\ObjectType) {
            /** @var class-string $className */
            $className = $type->getClassName();

            if (is_subclass_of($className, \DateTimeInterface::class) || \DateTimeInterface::class === $className) {
                return new Schema(type: Schema\Type::STRING, format: 'date-time');
            }

            return new Schema(ref: $this->componentsRegistry->getSchemaReference($className, $groups));
        }

        if ($type instanceof Type\TemplateType) {
            throw new DokkyException('TemplateType not supported.');
        }

        if ($type instanceof Type\UnionType) {
            return new Schema(
                anyOf: array_map(
                    fn ($t) => $this->typeToSchema($t, $groups),
                    $type->getTypes(),
                )
            );
        }

        throw new DokkyException(sprintf('Unsupported type "%s"', $type));
    }
}
