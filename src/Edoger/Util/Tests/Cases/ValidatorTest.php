<?php

/**
 * This file is part of the Edoger framework.
 *
 * @author    Qingshan Luo <shanshan.lqs@gmail.com>
 * @copyright 2017 - 2018 Qingshan Luo
 * @license   GNU Lesser General Public License 3.0
 */

namespace Edoger\Util\Tests\Cases;

use Edoger\Util\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function testValidatorIsNotEmptyString()
    {
        $this->assertFalse(Validator::isNotEmptyString(''));
        $this->assertFalse(Validator::isNotEmptyString(null));
        $this->assertFalse(Validator::isNotEmptyString(1));
        $this->assertFalse(Validator::isNotEmptyString([]));
        $this->assertTrue(Validator::isNotEmptyString('0'));
        $this->assertTrue(Validator::isNotEmptyString('foo'));
    }

    public function testValidatorIsNotEmptyArray()
    {
        $this->assertFalse(Validator::isNotEmptyArray(''));
        $this->assertFalse(Validator::isNotEmptyArray(null));
        $this->assertFalse(Validator::isNotEmptyArray(1));
        $this->assertFalse(Validator::isNotEmptyArray([]));
        $this->assertTrue(Validator::isNotEmptyArray(['0']));
        $this->assertTrue(Validator::isNotEmptyArray(['foo' => 'foo']));
    }

    public function testValidatorIsNumber()
    {
        $this->assertFalse(Validator::isNumber(''));
        $this->assertFalse(Validator::isNumber(null));
        $this->assertFalse(Validator::isNumber([]));
        $this->assertFalse(Validator::isNumber('0'));
        $this->assertFalse(Validator::isNumber('foo'));
        $this->assertTrue(Validator::isNumber(1));
        $this->assertTrue(Validator::isNumber(1.5));
        $this->assertTrue(Validator::isNumber(0));
        $this->assertTrue(Validator::isNumber(-1));
        $this->assertTrue(Validator::isNumber(-1.5));
    }

    public function testValidatorIsStringOrNumber()
    {
        $this->assertFalse(Validator::isStringOrNumber(null));
        $this->assertFalse(Validator::isStringOrNumber([]));
        $this->assertTrue(Validator::isStringOrNumber('0'));
        $this->assertTrue(Validator::isStringOrNumber('foo'));
        $this->assertTrue(Validator::isStringOrNumber(1));
        $this->assertTrue(Validator::isStringOrNumber(1.5));
        $this->assertTrue(Validator::isStringOrNumber(0));
        $this->assertTrue(Validator::isStringOrNumber(-1));
        $this->assertTrue(Validator::isStringOrNumber(-1.5));
        $this->assertTrue(Validator::isStringOrNumber('1'));
        $this->assertTrue(Validator::isStringOrNumber('1.5'));
        $this->assertTrue(Validator::isStringOrNumber(0));
        $this->assertTrue(Validator::isStringOrNumber('-1'));
        $this->assertTrue(Validator::isStringOrNumber('-1.5'));
        $this->assertTrue(Validator::isStringOrNumber(0xf));
    }

    public function testValidatorIsPositiveNumber()
    {
        $this->assertFalse(Validator::isPositiveNumber(''));
        $this->assertFalse(Validator::isPositiveNumber(null));
        $this->assertFalse(Validator::isPositiveNumber([]));
        $this->assertFalse(Validator::isPositiveNumber('0'));
        $this->assertFalse(Validator::isPositiveNumber('foo'));
        $this->assertTrue(Validator::isPositiveNumber(1));
        $this->assertTrue(Validator::isPositiveNumber(1.5));
        $this->assertFalse(Validator::isPositiveNumber(0));
        $this->assertFalse(Validator::isPositiveNumber(-1));
        $this->assertFalse(Validator::isPositiveNumber(-1.5));
    }

    public function testValidatorIsPositiveInteger()
    {
        $this->assertFalse(Validator::isPositiveInteger(''));
        $this->assertFalse(Validator::isPositiveInteger(null));
        $this->assertFalse(Validator::isPositiveInteger([]));
        $this->assertFalse(Validator::isPositiveInteger('0'));
        $this->assertFalse(Validator::isPositiveInteger('foo'));
        $this->assertTrue(Validator::isPositiveInteger(1));
        $this->assertFalse(Validator::isPositiveInteger(1.5));
        $this->assertFalse(Validator::isPositiveInteger(0));
        $this->assertFalse(Validator::isPositiveInteger(-1));
        $this->assertFalse(Validator::isPositiveInteger(-1.5));
    }

    public function testValidatorIsNegativeNumber()
    {
        $this->assertFalse(Validator::isNegativeNumber(''));
        $this->assertFalse(Validator::isNegativeNumber(null));
        $this->assertFalse(Validator::isNegativeNumber([]));
        $this->assertFalse(Validator::isNegativeNumber('0'));
        $this->assertFalse(Validator::isNegativeNumber('foo'));
        $this->assertFalse(Validator::isNegativeNumber(1));
        $this->assertFalse(Validator::isNegativeNumber(1.5));
        $this->assertFalse(Validator::isNegativeNumber(0));
        $this->assertTrue(Validator::isNegativeNumber(-1));
        $this->assertTrue(Validator::isNegativeNumber(-1.5));
    }

    public function testValidatorIsNegativeInteger()
    {
        $this->assertFalse(Validator::isNegativeInteger(''));
        $this->assertFalse(Validator::isNegativeInteger(null));
        $this->assertFalse(Validator::isNegativeInteger([]));
        $this->assertFalse(Validator::isNegativeInteger('0'));
        $this->assertFalse(Validator::isNegativeInteger('foo'));
        $this->assertFalse(Validator::isNegativeInteger(1));
        $this->assertFalse(Validator::isNegativeInteger(1.5));
        $this->assertFalse(Validator::isNegativeInteger(0));
        $this->assertTrue(Validator::isNegativeInteger(-1));
        $this->assertFalse(Validator::isNegativeInteger(-1.5));
    }

    public function testValidatorIsIpv4()
    {
        $this->assertFalse(Validator::isIpv4(''));
        $this->assertFalse(Validator::isIpv4('123'));
        $this->assertFalse(Validator::isIpv4(123));
        $this->assertTrue(Validator::isIpv4('123.123.123.123'));
        $this->assertTrue(Validator::isIpv4('0.0.0.0'));
        $this->assertTrue(Validator::isIpv4('255.255.255.255'));
        $this->assertTrue(Validator::isIpv4('192.168.12.12'));
        $this->assertTrue(Validator::isIpv4('127.0.0.1'));

        $this->assertFalse(Validator::isIpv4('192.168.12.12', true));
        $this->assertFalse(Validator::isIpv4('127.0.0.1', false, true));
    }

    public function testValidatorIsIpv6()
    {
        $this->assertFalse(Validator::isIpv6(''));
        $this->assertFalse(Validator::isIpv6('123'));
        $this->assertFalse(Validator::isIpv6(123));
        $this->assertTrue(Validator::isIpv6('ABCD:EF01:2345:6789:ABCD:EF01:2345:6789'));
        $this->assertTrue(Validator::isIpv6('::'));
        $this->assertTrue(Validator::isIpv6('FFFF:FFFF:FFFF:FFFF:FFFF:FFFF:FFFF:FFFF'));
        $this->assertTrue(Validator::isIpv6('FD:FD:FD:FD:FD:FD:FD:FD'));
        $this->assertTrue(Validator::isIpv6('FC:FC:FC:FC:FC:FC:FC:FC'));

        $this->assertFalse(Validator::isIpv6('FD:FD:FD:FD:FD:FD:FD:FD', true));
        $this->assertFalse(Validator::isIpv6('FD::FD', true));
        $this->assertFalse(Validator::isIpv6('FC::FC', true));
        $this->assertFalse(Validator::isIpv6('::1', false, true));
        $this->assertFalse(Validator::isIpv6('::FFFF::', false, true));
        $this->assertFalse(Validator::isIpv6('FE80::', false, true));
    }

    public function testValidatorIsEmail()
    {
        $this->assertFalse(Validator::isEmail(''));
        $this->assertFalse(Validator::isEmail('@foo.com'));
        $this->assertFalse(Validator::isEmail('foo@'));
        $this->assertFalse(Validator::isEmail('foo@foo'));
        $this->assertFalse(Validator::isEmail('foo@foo..com'));
        $this->assertFalse(Validator::isEmail('f..oo@foo.com'));
        $this->assertTrue(Validator::isEmail('foo@foo.com'));
        $this->assertTrue(Validator::isEmail('f-oo@foo.com'));
        $this->assertTrue(Validator::isEmail('f.oo@foo.com'));
        $this->assertTrue(Validator::isEmail('f.o.o@foo.com'));
    }

    public function testValidatorIsIntergerString()
    {
        $this->assertFalse(Validator::isIntergerString(''));
        $this->assertFalse(Validator::isIntergerString('foo'));
        $this->assertFalse(Validator::isIntergerString('0123'));
        $this->assertFalse(Validator::isIntergerString(123));
        $this->assertTrue(Validator::isIntergerString('123'));
    }

    public function testValidatorIsMobileNumber()
    {
        $this->assertFalse(Validator::isMobileNumber(''));
        $this->assertFalse(Validator::isMobileNumber('12345'));
        $this->assertFalse(Validator::isMobileNumber('01212341234'));
        $this->assertTrue(Validator::isMobileNumber('13312341234'));
        $this->assertTrue(Validator::isMobileNumber('12312341234'));
    }

    public function testValidatorIsTelNumber()
    {
        $this->assertFalse(Validator::isTelNumber(''));
        $this->assertFalse(Validator::isTelNumber('123--'));
        $this->assertFalse(Validator::isTelNumber('123-1234-'));
        $this->assertTrue(Validator::isTelNumber('12345'));
        $this->assertTrue(Validator::isTelNumber('01212341234'));
        $this->assertTrue(Validator::isTelNumber('13312341234'));
        $this->assertTrue(Validator::isTelNumber('12312341234'));
        $this->assertTrue(Validator::isTelNumber('123-12341234'));
        $this->assertTrue(Validator::isTelNumber('123-1234-1234'));
        $this->assertTrue(Validator::isTelNumber('0123-1234-1234'));
    }

    public function testValidatorIsDomainName()
    {
        $this->assertFalse(Validator::isDomainName(''));
        $this->assertFalse(Validator::isDomainName('foo'));
        $this->assertFalse(Validator::isDomainName('_.com'));
        $this->assertTrue(Validator::isDomainName('foo.com'));
        $this->assertTrue(Validator::isDomainName('foo.bar.com'));
        $this->assertTrue(Validator::isDomainName('foo.bar.baz.com'));
        $this->assertTrue(Validator::isDomainName('1.123.com'));
    }

    public function testValidatorIsAttributeName()
    {
        $this->assertFalse(Validator::isAttributeName(''));
        $this->assertFalse(Validator::isAttributeName('a:b:c'));
        $this->assertFalse(Validator::isAttributeName('a.b'));
        $this->assertFalse(Validator::isAttributeName('1ab'));
        $this->assertTrue(Validator::isAttributeName('abc'));
        $this->assertTrue(Validator::isAttributeName('_abc'));
        $this->assertTrue(Validator::isAttributeName('abc1'));
        $this->assertTrue(Validator::isAttributeName('abc_5'));
    }

    public function testValidatorIsPortNumber()
    {
        $this->assertFalse(Validator::isPortNumber(''));
        $this->assertFalse(Validator::isPortNumber('123'));
        $this->assertFalse(Validator::isPortNumber(-123));
        $this->assertFalse(Validator::isPortNumber(70000));
        $this->assertFalse(Validator::isPortNumber(12.5));
        $this->assertTrue(Validator::isPortNumber(123));
        $this->assertTrue(Validator::isPortNumber(1));
        $this->assertTrue(Validator::isPortNumber(65535));
    }
}
