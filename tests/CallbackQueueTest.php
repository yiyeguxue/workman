<?php
class CallbackQueueTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        @unlink('./artifacts/output');
    }

    public function testCallbackQueue()
    {
        $callback = function($arg1) {
            file_put_contents('./artifacts/output', $arg1 . "\n", FILE_APPEND);
        };

        $callbackQueue = new Workman\CallbackQueue($callback);
        for ($c = 0; $c < 100; $c++) {
            $callbackQueue->push([$c]);
        }
        $callbackQueue->work(5);

        $lines = file('./artifacts/output');
        $this->assertEquals(100, count($lines));
        foreach ($lines as $line) {
            $trimmedLines[] = trim($line);
        }
        sort($trimmedLines);
        $this->assertEquals(range(0, 99), $trimmedLines);
    }

    public function tearDown()
    {
        @unlink('./artifacts/output');
    }
}