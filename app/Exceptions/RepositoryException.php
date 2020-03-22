<?php

namespace App\Exceptions;

class RepositoryException extends \Exception
{
    /**
     * Exception message.
     *
     * @var string
     */
    protected $message = 'Repository not available!';
}
