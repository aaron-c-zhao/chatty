<?php
declare(strict_types=1);

namespace App\Infrastructure\db; 

use App\Infrastructure\InfrastructureException\InfrastructureException; 

class DatabaseFailureException extends InfrastructureException
{
    public $message="Due to internal failure, the operation requested could not be executed.";
}
