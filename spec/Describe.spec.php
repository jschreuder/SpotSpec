<?php

use Webspot\SpotSpec\Describe;

return (new Describe('how Describe describes a spec suite'))

    ->beforeEach(function () {
        $this->description = 'a test';
        $this->suite = new Describe($this->description);
    })

    ->it('can be created', function () {
        return $this->expect($this->suite instanceof Describe)->toBeTrue();
    })

    ->it('can have a property set upon it', function () {
        $value = 'value';
        $this->suite->property = $value;
        return $this->expect($this->suite->property)->toEqual($value);
    })

    ->it('has a description', function () {
        return $this->expect($this->suite->getDescription())->toEqual($this->description);
    })

    ->it('will fail', function () {
        return $this->expect(true)->toNotBeFalse();
    })
;
