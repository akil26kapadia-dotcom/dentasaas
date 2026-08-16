<?php

namespace App\Exceptions;

use Exception;

class PlanLimitException extends Exception
{
    public string $resource;

    public int $limit;

    public function __construct(string $resource, int $limit)
    {
        $this->resource = $resource;
        $this->limit = $limit;

        parent::__construct("You have reached your plan's limit of {$limit} for {$resource}.");
    }
}
