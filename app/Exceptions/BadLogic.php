<?php

namespace App\Exceptions;

use LogicException;

class BadLogic extends LogicException
{
    /**
     * Exception message.
     *
     * @var string
     */
    protected $message = 'Well, that is sloppy logic!';
}
