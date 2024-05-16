<?php

namespace Modules\Core\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

abstract class BaseAuthModel extends Authenticatable
{
    use BaseModelTrait;
}
