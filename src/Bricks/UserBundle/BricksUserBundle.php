<?php

namespace Bricks\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BricksUserBundle extends Bundle
{
    /**
     * extends FOSUserBundle
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
