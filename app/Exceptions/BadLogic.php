<?php

namespace BBCMS\Exceptions;

class BadLogic extends \LogicException
{
    /**
     * Exception message.
     *
     * @var string
     */
    protected $message = 'Well, that is sloppy logic!';
}
