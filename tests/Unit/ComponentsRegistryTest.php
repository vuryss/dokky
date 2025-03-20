<?php

declare(strict_types=1);

test(
    'Can generate references to components without groups',
    function (): void {
        $componentsRegistry = new Dokky\ComponentsRegistry();
        $className = Dokky\Tests\Datasets\Classes\DataWithGroups::class;
        $className2 = Dokky\Tests\Datasets\Classes\Basic::class;

        expect($componentsRegistry->getSchemaReference($className))
            ->toBe('#/components/schemas/DataWithGroups')
            ->and($componentsRegistry->getSchemaReference($className2))
            ->toBe('#/components/schemas/Basic');
    }
);

test(
    'Can generate references to components with groups',
    function (): void {
        $componentsRegistry = new Dokky\ComponentsRegistry();
        $className = Dokky\Tests\Datasets\Classes\DataWithGroups::class;

        expect($componentsRegistry->getSchemaReference($className))
            ->toBe('#/components/schemas/DataWithGroups')
            ->and($componentsRegistry->getSchemaReference($className, ['group1']))
            ->toBe('#/components/schemas/DataWithGroups2')
            ->and($componentsRegistry->getSchemaReference($className, ['group2']))
            ->toBe('#/components/schemas/DataWithGroups3')
            ->and($componentsRegistry->getSchemaReference($className, ['group1', 'group2']))
            ->toBe('#/components/schemas/DataWithGroups4')
            ->and($componentsRegistry->getSchemaReference($className, ['group2', 'group1']))
            ->toBe('#/components/schemas/DataWithGroups4')
            ->and($componentsRegistry->getSchemaReference($className))
            ->toBe('#/components/schemas/DataWithGroups')
            ->and($componentsRegistry->getSchemaReference($className, ['group1']))
            ->toBe('#/components/schemas/DataWithGroups2')
        ;
    }
);

test(
    'Cannot generate reference to invalid class',
    function (): void {
        $componentsRegistry = new Dokky\ComponentsRegistry();
        $className = 'InvalidClassName';

        expect(fn () => $componentsRegistry->getSchemaReference($className))
            ->toThrow(
                Dokky\DokkyException::class,
                'Cannot get reference for class "InvalidClassName" - class does not exist'
            )
        ;
    }
);
