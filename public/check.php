<?php

echo 'PHP Version: '.phpversion().'<br>';
echo 'DOM: '.(extension_loaded('dom') ? 'enabled' : 'disabled').'<br>';
echo 'PHP Binary: '.PHP_BINARY;
