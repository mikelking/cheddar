<?php

namespace Moron;

class Dum {
    const STUPID = true;

    public function renderStupid() {
        print( self::STUPID . PHP_EOL );
    }

    public function getStupid() {
        return( self::STUPID );
    }
}
