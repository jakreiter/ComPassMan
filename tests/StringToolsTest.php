<?php
namespace App\Tests\Model;

use App\Model\StringTools;
use PHPUnit\Framework\TestCase;

class StringToolsTest extends TestCase
{

    public function testAlphaOnly()
    {

        $result = StringTools::alphaOnly('abc');
        $this->assertEquals('abc', $result);

        $result = StringTools::alphaOnly('abc % !');
        $this->assertEquals('abc__', $result);
    }

    public function testAlphaOnly2()
    {

        $result = StringTools::alphaOnly2('abc');
        $this->assertEquals('abc', $result);

        $result = StringTools::alphaOnly2('abc % !');
        $this->assertEquals('abc_', $result);
    }
}