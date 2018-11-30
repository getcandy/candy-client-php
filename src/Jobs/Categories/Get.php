<?php

namespace GetCandy\Client\Jobs\Categories;

use GetCandy\Client\AbstractJob;

class Get extends AbstractJob
{
    protected $endpoint = 'categories';
    protected $handle = 'categories-get';
}
