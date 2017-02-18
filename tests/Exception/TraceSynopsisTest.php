<?php

namespace TheIconic\Synopsis\Exception;

use PHPUnit\Framework\TestCase;
use Exception;
use TheIconic\Synopsis\AbstractSynopsis;

class TraceSynopsisTest extends TestCase
{
    /**
     *
     */
    public function testProcess()
    {
        $synopsis = $this->getSynopsis(0);
        $line = __LINE__ - 1;

        $this->assertEquals(sprintf('%s->getSynopsis()', __CLASS__), $synopsis->getType());
        $this->assertEquals(sprintf('%s (%s)', __FILE__, $line), $synopsis->getValue());
        $this->assertEquals(1, $synopsis->getLength());
        $this->assertEquals($line, $synopsis->getLine());
        $this->assertEquals(__FILE__, $synopsis->getFile());
        $this->assertEquals('getSynopsis', $synopsis->getFunction());
        $this->assertEquals(__CLASS__, $synopsis->getClass());

        /** @var AbstractSynopsis $firstChild */
        $firstChild = $synopsis->getChildren()[0];

        $this->assertInstanceOf(AbstractSynopsis::class, $firstChild);

        $this->assertEquals('integer', $firstChild->getType());
        $this->assertEquals(0, $firstChild->getValue());
        $this->assertEquals(1, $firstChild->getLength());
    }

    /**
     * @param int $index
     * @return TraceSynopsis
     */
    protected function getSynopsis(int $index)
    {
        $synopsis = new TraceSynopsis();

        try {
            throw new Exception();
        } catch (Exception $e) {
            $trace = $e->getTrace();

            $synopsis->process($trace[0], 3);
        }

        return $synopsis;
    }
}