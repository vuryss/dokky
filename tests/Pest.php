<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

// pest()->extend(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeValidJsonSchema', function () {
    $validator = new JsonSchema\Validator();
    $validator->validate(
        $this->value,
        (object) ['$ref' => 'file://' . __DIR__ . '/schemas/json-schema/schema.json']
    );

    $errorMessages = [];

    foreach ($validator->getErrors() as $error) {
        $errorMessages[] = sprintf("[%s] %s\n", $error['property'], $error['message']);
    }

    expect($validator->isValid())->toBeTrue(implode(PHP_EOL, $errorMessages));

    return $this;
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function cleanObject(object $object): object {
    try {
        $json = new \Vuryss\Serializer\Serializer()
            ->serialize(
                $object,
                [\Vuryss\Serializer\SerializerInterface::ATTRIBUTE_SKIP_NULL_VALUES => true]
            );
    } catch (\Vuryss\Serializer\SerializerException $e) {
        throw new RuntimeException('Failed to serialize object', 0, $e);
    }

    try {
        return (object)json_decode(
            $json,
            associative: false,
            flags: JSON_THROW_ON_ERROR
        );
    } catch (JsonException $e) {
        throw new RuntimeException('Failed to decode JSON', 0, $e);
    }
}
