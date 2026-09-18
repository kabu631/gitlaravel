<?php

namespace App\Exceptions;

use RuntimeException;

/** Thrown when a cart item can no longer be fulfilled from available stock. */
class OutOfStockException extends RuntimeException
{
}
