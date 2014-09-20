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
;
