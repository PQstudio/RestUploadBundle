Setting up the bundle
=====================

### A) Install RestUploadBundle

Add to your composer.json:

``` json
"pqstudio/rest-upload-bundle": "dev-master"
```

### B) Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new PQstudio\RestUploadBundle\PQstudioRestUploadBundle(),
    );
}
```

Basic configuration
===================

