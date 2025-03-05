<?php

declare(strict_types=1);

namespace Dokky\Tests\Datasets\Classes;

use Dokky\Attribute\Groups as DokkyGroups;
use Symfony\Component\Serializer\Attribute\Groups as SymfonyGroups;

class DataWithGroups
{
    // Wih Symfony attributes
    #[SymfonyGroups(['group1'])]
    public string $property1;

    #[SymfonyGroups(['group2'])]
    public string $property2;

    #[SymfonyGroups(['group1', 'group2'])]
    public string $property3;

    #[SymfonyGroups(['Default'])]
    public string $property4;

    public string $property5;

    // With own attributes
    #[DokkyGroups(['group1'])]
    public string $property11;

    #[DokkyGroups(['group2'])]
    public string $property12;

    #[DokkyGroups(['group1', 'group2'])]
    public string $property13;

    #[DokkyGroups(['Default'])]
    public string $property14;

    public string $property15;

    public function __construct(
        // With Symfony attributes
        #[SymfonyGroups(['group1'])]
        public string $property6,

        #[SymfonyGroups(['group2'])]
        public string $property7,

        #[SymfonyGroups(['group1', 'group2'])]
        public string $property8,

        #[SymfonyGroups(['Default'])]
        public string $property9,

        public string $property10,

        // With own attributes
        #[DokkyGroups(['group1'])]
        public string $property16,

        #[DokkyGroups(['group2'])]
        public string $property17,

        #[DokkyGroups(['group1', 'group2'])]
        public string $property18,

        #[DokkyGroups(['Default'])]
        public string $property19,

        public string $property20,
    ) {
    }
}
