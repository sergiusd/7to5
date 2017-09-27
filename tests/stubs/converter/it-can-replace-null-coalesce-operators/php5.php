<?php

function test1()
{
    return null;
}
function test2()
{
    return null;
}
class Test
{
    const TEST_SCALAR = 3;
    const TEST_ARRAY = array("t" => 666);
    public function testMethod()
    {
        return null;
    }
}
$test = new Test();
$result = defined('Test::TEST_SCALAR') ? Test::TEST_SCALAR : 0;
$result = (defined('Test::TEST_ARRAY') and !empty(Test::TEST_ARRAY["f"])) ? Test::TEST_ARRAY["f"] : 1;
$result = (defined('Test::TEST_ARRAY') and !empty(Test::TEST_ARRAY["f"]["d"]["y"])) ? Test::TEST_ARRAY["f"]["d"]["y"] : 1;
$result = isset($input) ? $input : 'fixed-value';
$result = isset($input) ? $input : (isset($input2) ? $input2 : $input3);
$result = !empty(test1()) ? test1() : (!empty(test2()) ? test2() : 0);
if (null === (isset($input) ? $input : null)) {
}
$result = !empty($test->testMethod()) ? $test->testMethod() : 0;