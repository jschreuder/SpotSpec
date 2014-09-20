<?php

use Webspot\SpotSpec\Describe;
use Webspot\SpotSpec\Expectation;

return (new Describe('how Expectation makes handling expectations nicer'))

    ->it('should be able to test for a scalar value', function () {
        return (new Expectation(5))->toBeA('scalar')
            && (new Expectation('string'))->toBeA('scalar')
            && (new Expectation(5.23))->toBeA('scalar');
    })

    ->it('should be able to test for an object and its type', function () {
        return (new Expectation(new stdClass()))->toBeAn('object')
            && (new Expectation(new ArrayObject()))->toBeAn('ArrayObject')
            && (new Expectation(new stdClass()))->toBeA('stdClass');
    })

    ->it('should be able to test for an array', function () {
        return (new Expectation([]))->toBeAn('array')
            && (new Expectation([]))->toNotBeA('scalar');
    })

    ->it('should be able to test for a callable', function () {
        return (new Expectation('trim'))->toBeA('callable')
            && (new Expectation(function () {}))->toBeA('callable');
    })

    ->it('should be able to test for truthyness', function () {
        return (new Expectation('Stephen Colbert'))->toBeTruthy()
            && (new Expectation(intval('Bill O\'Reilly')))->toNotBeTruthy()
            && (new Expectation(true))->toBeTruthy();
    })

    ->it('should be able to test for falsyness', function () {
        return (new Expectation('Stephen Colbert'))->toNotBeFalsy()
        && (new Expectation(intval('Bill O\'Reilly')))->toBeFalsy()
        && (new Expectation(false))->toBeFalsy();
    })

    ->it('should be able to test for true', function () {
        return (new Expectation(true))->toBeTrue()
        && (new Expectation('Bill O\'Reilly'))->toNotBeTrue();
    })

    ->it('should be able to test for false', function () {
        return (new Expectation(false))->toBeFalse()
        && (new Expectation('Stephen Colbert'))->toNotBeFalse();
    })

    ->it('should be able to test for null', function () {
        return (new Expectation(null))->toBeNull();
    })

    ->it('should be able to test equality', function () {
        return (new Expectation('equal'))->toEqual('equal')
            && (new Expectation('42'))->toNotEqual(42)
            && (new Expectation(new stdClass()))->toNotEqual(new stdClass());
    })

    ->it('should be able to test for lookalikes', function () {
        return (new Expectation('equal'))->toBeLike('equal')
        && (new Expectation('42'))->toBeLike(42)
        && (new Expectation(new stdClass()))->toBeLike(new stdClass())
        && (new Expectation('42'))->toNotBeLike('unlikeness');
    })

    ->it('should be able to check containment', function () {
        return (new Expectation('substring'))->toContain('sub')
            && (new Expectation([1, 2, 3]))->toContain(2)
            && (new Expectation(function () {
                return (new Expectation(new stdClass()))->toContain('something');
            }))->toThrowException('BadMethodCallException');
    })

    ->it('should be able to compare greatness', function () {
        return (new Expectation('42'))->toBeGreaterThan(0)
            && (new Expectation(42))->toNotBeGreaterThan('42')
            && (new Expectation('2014-09-19'))->toBeGreaterThan('2014-09-18');
    })

    ->it('should be able to compare lessness', function () {
        return (new Expectation('0'))->toBeLesserThan(42)
            && (new Expectation(42))->toNotBeLesserThan('42')
            && (new Expectation('2014-09-19'))->toBeLesserThan('2014-09-20');
    })

    ->it('should be able to validate the value', function () {
        return (new Expectation(42))->toValidate(function ($value, $match) {
                return $value === $match;
            }, 42)
            && (new Expectation(42))->toNotValidate(function ($value, $match) {
                return $value !== $match;
            }, 42);
    })

    ->it('should be able to catch an exception', function () {
        return (new Expectation(function () { throw new \RuntimeException; }))->toThrowException('RuntimeException')
            && (new Expectation(function () { throw new \RuntimeException; }))->toNotThrowException('DomainException')
            && (new Expectation(function () { return true; }))->toNotThrowException('Exception');
    })

    ->it('should be able to execute a callable and work with its result', function () {
        $expectation = new Expectation(function ($value) { return $value; });
        return $expectation->toBeExecutable(42)
            && $expectation->toEqual(42)
            && $expectation->toNotBeExecutable();
    })
;
