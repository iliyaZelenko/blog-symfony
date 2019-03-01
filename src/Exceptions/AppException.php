<?php

namespace App\Exceptions;

/**
 * Исключения приложении. Но по возможности используются SPL и исключения встроенные в Symfony
 */
class AppException extends \Exception implements AppExceptionInterface
{

}
