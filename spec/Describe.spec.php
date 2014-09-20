<?php

use Webspot\SpotSpec\Describe;
use Webspot\SpotSpec\Expectation;

return (new Describe('how Describe describes a spec suite'))
    ->beforeEach(function () {
        $this->description = 'a test';
        $this->suite = new Describe($this->description);
    })
    ->it('can be created', function () {
        return $this->suite instanceof Describe;
    })
    ->it('can have a property set upon it', function () {
        $value = 'value';
        $this->suite->property = $value;
        return (new Expectation($this->suite->property))->toEqual($value);
    })
    ->it('has a description', function () {
        return (new Expectation($this->suite->getDescription()))->toEqual($this->description);
    })
    ->it('will fail', function () {
        return (new Expectation(true))->toBeFalse();
    });
