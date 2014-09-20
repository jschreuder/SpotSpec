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

    ->it('will run before() and after()', function () {
        $this->suite->before(function () {
            $this->start = 40;
        });
        $this->suite->after(function () {
            $this->end = $this->start + 2;
        });
        $this->suite->run();
        return $this->expect($this->suite->end)->toEqual(42);
    })

    ->it('will run beforeEach() and afterEach()', function () {
        $beforeCount = 0;
        $afterCount = 0;
        $this->suite->beforeEach(function () use (&$beforeCount) {
            $beforeCount++;
        });
        $this->suite->afterEach(function () use (&$afterCount) {
            $afterCount++;
        });

        $this->suite->it('tests', function () {
            return true;
        });
        $this->suite->it('fails', function () {
            return false;
        });

        $this->suite->run();

        return $this->expect($beforeCount)->toEqual(2)
            && $this->expect($afterCount)->toEqual(2);
    });
;
