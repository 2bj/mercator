<?php

namespace App\Entities;

use Eloquent;

/**
 * Class AbstractEntity
 *
 * @method static mixed create($attributes)
 * @method static mixed firstOrCreate($attributes, $values = array())
 *
 * @package App\Entities
 */
abstract class AbstractEntity extends Eloquent
{

}