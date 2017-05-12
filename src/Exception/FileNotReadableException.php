<?php

namespace Akatsuki\Component\Torrent\Exception;

use InvalidArgumentException;

/**
 * Class FileNotReadableException
 *
 * @package Akatsuki\Component\Torrent\Exception
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 */
class FileNotReadableException extends InvalidArgumentException
{
    public function __construct(string $file)
    {
        parent::__construct(
            sprintf(
                'File %s does not exists or can not be read.',
                $file
            )
        );
    }
}