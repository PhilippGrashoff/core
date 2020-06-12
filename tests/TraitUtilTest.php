<?php

declare(strict_types=1);

namespace atk4\core\tests;

use atk4\core\AtkPhpunit;
use atk4\core\NameTrait;
use atk4\core\TraitUtil;

/**
 * @coversDefaultClass \atk4\core\TraitUtil
 */
class TraitUtilTest extends AtkPhpunit\TestCase
{
    public function testHasTrait()
    {
        $this->assertFalse(TraitUtil::hasTrait(TraitUtilTestA::class, NameTrait::class));
        $this->assertTrue(TraitUtil::hasTrait(TraitUtilTestB::class, NameTrait::class));
        $this->assertTrue(TraitUtil::hasTrait(TraitUtilTestC::class, NameTrait::class));

        $this->assertFalse(TraitUtil::hasTrait(new TraitUtilTestA(), NameTrait::class));
        $this->assertTrue(TraitUtil::hasTrait(new TraitUtilTestB(), NameTrait::class));
        $this->assertTrue(TraitUtil::hasTrait(new TraitUtilTestC(), NameTrait::class));

        $this->assertFalse(TraitUtil::hasTrait(new class() extends TraitUtilTestA {
        }, NameTrait::class));
        $this->assertTrue(TraitUtil::hasTrait(new class() extends TraitUtilTestB {
        }, NameTrait::class));
        $this->assertTrue(TraitUtil::hasTrait(new class() extends TraitUtilTestC {
        }, NameTrait::class));
    }
}

class TraitUtilTestA
{
}

class TraitUtilTestB extends TraitUtilTestA
{
    use NameTrait;
}

class TraitUtilTestC extends TraitUtilTestB
{
}
