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

    public function typeToSchema(Type $type): Schema
    {
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
                    items: $this->typeToSchema($type->getCollectionValueType()),
                );
            }

            // A very special case - the Symfony Type does not let us know if the key is actually defined in the
            // docblock - like array<int|string, T> or just array<T>. To avoid manually go and parse the docblock, we
            // would treat a key of type int|string as a list. May be in the future we can add additional parsing if we
            // or someone else needs it.
            $keyType = $type->getCollectionKeyType();
            $keyTypeTreatedAsList = Type::union(Type::int(), Type::string());

            if (
                ($keyType instanceof Type\BuiltinType && TypeIdentifier::INT === $keyType->getTypeIdentifier())
                || (string) $keyType == (string) $keyTypeTreatedAsList
            ) {
                return new Schema(
                    type: Schema\Type::ARRAY,
                    items: $this->typeToSchema($type->getCollectionValueType()),
                );
            }

            if ($keyType instanceof Type\BuiltinType && TypeIdentifier::STRING === $keyType->getTypeIdentifier()) {
                return new Schema(
                    type: Schema\Type::OBJECT,
                    additionalProperties: $this->typeToSchema($type->getCollectionValueType()),
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
            $wrappedSchema = $this->typeToSchema($type->getWrappedType());

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

            if (in_array($className, [\DateTime::class, \DateTimeImmutable::class, \DateTimeInterface::class], true)) {
                return new Schema(type: Schema\Type::STRING, format: 'date-time');
            }

            return new Schema(ref: $this->componentsRegistry->getSchemaReference($className));
        }

        if ($type instanceof Type\TemplateType) {
            throw new DokkyException('TemplateType not supported.');
        }

        if ($type instanceof Type\UnionType) {
            return new Schema(
                anyOf: array_map(
                    fn ($t) => $this->typeToSchema($t),
                    $type->getTypes(),
                )
            );
        }

        throw new DokkyException(sprintf('Unsupported type "%s"', $type));
    }
}
