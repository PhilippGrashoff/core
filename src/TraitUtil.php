<?php

declare(strict_types=1);

namespace atk4\core;

final class TraitUtil
{
    /** @var bool[className][traitName] */
    private static $_hasTraitMap = [];

    private function __construct()
    {
        // zeroton
    }

    /**
     * @param object|string $class
     */
    public static function hasTrait($class, string $traitName): bool
    {
        if (is_object($class)) {
            $class = get_class($class);
        }

        // prevent mass use for other than internal use then we can decide
        //if we want to keep support this or replace with pure interfaces
        if (strpos($traitName, 'atk4\core\\') !== 0) {
            throw new Exception('Core::hasTrait is not indended for use with other than \atk4\core\* traits.');
        }

        if (!isset(self::$_hasTraitMap[$class][$traitName])) {
            $getUsesFunc = function (string $trait) use (&$getUsesFunc): array {
                $uses = class_uses($trait);
                foreach ($uses as $use) {
                    $uses += $getUsesFunc($use);
                }

                return $uses;
            };

            $uses = [];
            foreach (array_reverse(class_parents($class)) + [-1 => $class] as $class) {
                $uses += $getUsesFunc($class);
            }
            $uses = array_unique($uses);

            self::$_hasTraitMap[$class][$traitName] = in_array($traitName, $uses, true);
        }

        return self::$_hasTraitMap[$class][$traitName];
    }

    /*
     * ConfigTrait - not used
     * DebugTrait - not used
     * DynamicMethodTrait - not used
     * FactoryTrait - not used
     * StaticAddToTrait - not used
     * TranslatableTrait - not used
     *
     * QuickExceptionTrait - QuickException will be removed, not used outside QuickException class
     */

    /**
     * @param object|string $class
     */
    public static function hasAppScopeTrait(object $class): bool
    {
        return self::hasTrait($class, AppScopeTrait::class);
    }

    /**
     * @param object|string $class
     */
    public static function hasContainerTrait(object $class): bool
    {
        return self::hasTrait($class, ContainerTrait::class);
    }

    /**
     * Used in Factory and in ui/View only.
     *
     * @param object|string $class
     */
    public static function hasDIContainerTrait(object $class): bool
    {
        return self::hasTrait($class, DIContainerTrait::class);
    }

    /**
     * Used in DynamicMethodTrait only.
     *
     * @param object|string $class
     */
    public static function hasHookTrait(object $class): bool
    {
        return self::hasTrait($class, HookTrait::class);
    }

    /**
     * @param object|string $class
     */
    public static function hasInitializerTrait(object $class): bool
    {
        return self::hasTrait($class, InitializerTrait::class);
    }

    /**
     * @param object|string $class
     */
    public static function hasNameTrait(object $class): bool
    {
        return self::hasTrait($class, NameTrait::class);
    }

    /**
     * Used in ui\TableColumn\FilterModel\Generic only.
     *
     * @param object|string $class
     */
    public static function hasSessionTrait(object $class): bool
    {
        return self::hasTrait($class, SessionTrait::class);
    }

    /**
     * @param object|string $class
     */
    public static function hasTrackableTrait(object $class): bool
    {
        return self::hasTrait($class, TrackableTrait::class);
    }
}
