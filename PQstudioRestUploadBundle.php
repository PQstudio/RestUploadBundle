<?php

namespace PQstudio\RestUploadBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use PQstudio\RestUploadBundle\DependencyInjection\PQstudioRestUploadExtension;

class PQstudioRestUploadBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new PQstudioRestUploadExtension();
    }
}
