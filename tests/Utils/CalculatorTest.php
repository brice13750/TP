<?php
namespace App\Tests\Utils;



use App\Utils\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    public function testAdd()
    {
        // La classe qu'on veut tester
        $calculator = new Calculator();
        // La méthode que l'on souhaite tester
        $result = $calculator->add(30, 12);
        // assert that your calculator added the numbers correctly!
        $this->assertEquals(42, $result);
    }
}