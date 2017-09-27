<?php

function test1() {return null;}
function test2() {return null;}

class Test {
    const TEST_SCALAR = 3;
    const TEST_ARRAY = array("t" => 666);
    public function testMethod() {return null;}
}

$test = new Test();

$result = Test::TEST_SCALAR ?? 0;

$result = Test::TEST_ARRAY["f"] ?? 1;

$result = Test::TEST_ARRAY["f"]["d"]["y"] ?? 1;

$result = $input ?? 'fixed-value';

$result = $input ?? $input2 ?? $input3;

$result = test1() ?? test2() ?? 0;

if (null === $input ?? null) {
}

$result = $test->testMethod() ?? 0;