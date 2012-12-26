<?php

namespace Bricks\MessageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BricksMessageBundle extends Bundle
{
    /**
     * extends FOSMessageBundle
     */
    public function getParent()
    {
        return 'FOSMessageBundle';
    }
}
