<?php
/**
 * Author: Rumen Tabakov
 *         rumen.tabakov@gmail.com
 */

namespace App\Core\Command;


interface CommandInterface
{
    public function setParameters(array $parameters): void;

    public function runCommand(): void;
}
