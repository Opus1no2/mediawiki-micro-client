Media Wiki Micro Client
=======================
 
This is micro API client for MediaWiki's REST API. This is was done 
for fun and should not be taken seriously or considered for production.

Usage
-----

Using the client is easy:

``` php
<?php

require_once 'Client.php';

try {
    $client = new MediaWiki();
    $client->query(array(
        'prop'   => 'categories', 
        'titles' => 'Albert Einstein')
    );
} catch (Exception $e) {
    echo $e->getMessage();
}

```

Documentation
-------------

Comprehensive documention can be found at:

http://en.wikipedia.org/w/api.php

License
-------

ROFL