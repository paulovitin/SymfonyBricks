<?php

namespace Bricks\SiteBundle\Namer;

use Vich\UploaderBundle\Naming\NamerInterface;

class BricksNamer implements NamerInterface
{
    public function name($obj, $field)
    {
        $file = $obj->$field;
        $extension = $file->guessExtension();

        return uniqid('', true).'.'.$extension;
    }
}
