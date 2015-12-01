<?php

namespace AppCommentBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppCommentBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSCommentBundle';
    }
}
