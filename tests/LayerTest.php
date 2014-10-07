<?php
    require __DIR__ . '/../vendor/autoload.php';
    require __DIR__. '/../app/database/layer.php';

    class LayerTest extends PHPUnit_Framework_TestCase
    {
        private $connect;
        private $layer;

        public function setUp( )
        {
            $this->layer = new Layer();
        }
    }
?>