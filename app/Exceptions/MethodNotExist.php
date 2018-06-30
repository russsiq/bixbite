<?php

namespace BBCMS\Exceptions;

class MethodNotExist extends \Exception
{
    /**
     * Exception message.
     *
     * @var string
     */
    protected $message = 'Call to undefined method!';
}
