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
            ->toBe('#/components/schemas/Basic')
            ->and($componentsRegistry->getUniqueClassNames())
            ->toBe([
                Dokky\Tests\Datasets\Classes\DataWithGroups::class,
                Dokky\Tests\Datasets\Classes\Basic::class,
            ])
        ;
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
                'Class "InvalidClassName" not found. Make sure the class exists and is able to be autoloaded.'
            )
        ;
    }
);

test(
    'Correct groups are returned when iterating same schema with different groups',
    function (): void {
        $componentsRegistry = new Dokky\ComponentsRegistry();
        $className = Dokky\Tests\Datasets\Classes\DataWithGroups::class;

        expect($componentsRegistry->getSchemaReference($className, ['group1']))
            ->toBe('#/components/schemas/DataWithGroups')
            ->and($componentsRegistry->getSchemaComponents())
            ->toHaveCount(1)
            ->toContain([
                'className' => $className,
                'groups' => ['group1'],
                'schemaName' => 'DataWithGroups',
            ])

            ->and($componentsRegistry->getSchemaReference($className, ['group2']))
            ->toBe('#/components/schemas/DataWithGroups2')
            ->and($componentsRegistry->getSchemaComponents())
            ->toHaveCount(2)
            ->toContain([
                'className' => $className,
                'groups' => ['group2'],
                'schemaName' => 'DataWithGroups2',
            ])

            ->and($componentsRegistry->getSchemaReference($className, ['group1', 'group2']))
            ->toBe('#/components/schemas/DataWithGroups3')
            ->and($componentsRegistry->getSchemaComponents())
            ->toHaveCount(3)
            ->toContain([
                'className' => $className,
                'groups' => ['group1', 'group2'],
                'schemaName' => 'DataWithGroups3',
            ])

            ->and($componentsRegistry->getSchemaReference($className, ['group2', 'group1']))
            ->toBe('#/components/schemas/DataWithGroups3')
            ->and($componentsRegistry->getSchemaComponents())
            ->toHaveCount(3)
            ->toContain([
                'className' => $className,
                'groups' => ['group1', 'group2'],
                'schemaName' => 'DataWithGroups3',
            ])

            ->and($componentsRegistry->getSchemaReference($className))
            ->toBe('#/components/schemas/DataWithGroups4')
            ->and($componentsRegistry->getSchemaComponents())
            ->toHaveCount(4)
            ->toContain([
                'className' => $className,
                'groups' => null,
                'schemaName' => 'DataWithGroups4',
            ])

            ->and($componentsRegistry->getUniqueClassNames())
            ->toBe([Dokky\Tests\Datasets\Classes\DataWithGroups::class])
        ;
    }
);

test(
    'Can generate references to components with a custom name',
    function (): void {
        $componentsRegistry = new Dokky\ComponentsRegistry();
        $className = Dokky\Tests\Datasets\Classes\Basic::class;
        $customName = 'MyCustomBasicSchema';

        expect($componentsRegistry->getNamedSchemaReference($className, $customName))
            ->toBe('#/components/schemas/MyCustomBasicSchema')
            ->and($componentsRegistry->getSchemaComponents())
            ->toHaveCount(1)
            ->toContain([
                'className' => $className,
                'groups' => null,
                'schemaName' => $customName,
            ])
            ->and($componentsRegistry->getNamedSchemaReference($className, $customName))
            ->toBe('#/components/schemas/MyCustomBasicSchema')
            ->and($componentsRegistry->getSchemaComponents())
            ->toHaveCount(1);

        // Call with a *different* class but the *same* custom name - should throw exception
        $anotherClassName = Dokky\Tests\Datasets\Classes\DataWithArrays::class;
        expect(fn () => $componentsRegistry->getNamedSchemaReference($anotherClassName, $customName))
            ->toThrow(Dokky\DokkyException::class, sprintf(
                'The schema name "%s" is already used for class "%s" with groups ""',
                $customName,
                $className
            ));

        // Call with the *same* class, *same* custom name, but *different* groups - should throw exception
        // Call the auto-generator `getSchemaReference` for the same class - should generate default name
        $groups = ['groupA'];
        expect(fn () => $componentsRegistry->getNamedSchemaReference($className, $customName, $groups))
            ->toThrow(
                Dokky\DokkyException::class,
                sprintf(
                    'The schema name "%s" is already used for class "%s" with groups ""',
                    $customName,
                    $className
                )
            )
            ->and($componentsRegistry->getSchemaReference($className))
            ->toBe('#/components/schemas/Basic') // Default name
            ->and($componentsRegistry->getSchemaComponents())
            ->toHaveCount(2) // Count should increase (one named, one auto)
            ->toContain([ // Check the auto-generated one exists
                'className' => $className,
                'groups' => null,
                'schemaName' => 'Basic',
            ]);

        // Call the auto-generator `getSchemaReference` *first* for a new class, then try to use its name with `getNamedSchemaReference` - should throw exception
        $newClassName = Dokky\Tests\Datasets\Classes\DataWithDateTime::class;
        $autoGeneratedName = 'DataWithDateTime';

        expect($componentsRegistry->getSchemaReference($newClassName))
            ->toBe('#/components/schemas/'.$autoGeneratedName)
            ->and(fn () => $componentsRegistry->getNamedSchemaReference($newClassName, $autoGeneratedName))
            ->toThrow(
                Dokky\DokkyException::class,
                sprintf(
                    'The schema name "%s" is already used for autogenerated schemes',
                    $autoGeneratedName
                )
            )
            ->and($componentsRegistry->getUniqueClassNames())
            ->toBe([
                Dokky\Tests\Datasets\Classes\Basic::class,
                Dokky\Tests\Datasets\Classes\DataWithDateTime::class,
            ])
            ->and($componentsRegistry->getSchemaComponents())
            ->toHaveCount(3);
    }
);
