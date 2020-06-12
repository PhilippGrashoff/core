<?php

declare(strict_types=1);

namespace atk4\core;

/**
 * @deprecated will be removed in 2021-jun
 */
trait FactoryTrait
{
    /**
     * See \atk4\core\Factory::mergeSeeds().
     *
     * @deprecated will be removed in 2021-jun
     */
    public function mergeSeeds(...$seeds)
    {
        return Factory::mergeSeeds(...$seeds);
    }

    /**
     * See \atk4\core\Factory::factory().
     *
     * @deprecated will be removed in 2021-jun
     */
    public function factory($seed, $defaults = []): object
    {
        if (func_num_args() > 2) { // prevent bad usage
            throw new \Error('Too many method arguments, factory does no longer support prefix');
        }

        return Factory::factory($seed, $defaults);
    }
}
