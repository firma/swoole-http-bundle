<?php
/**
 * Created by Date: 2018/9/3
 */

namespace Firma\Bundle\SwooleBundle;

use Firma\Bundle\SwooleBundle\DependencyInjection\FirmaSwooleExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FirmaSwooleBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new FirmaSwooleExtension();
    }
}
