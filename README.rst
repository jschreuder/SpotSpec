SpotSpec
========

SpotSpec is a very lightweight testing library loosly inspired by Jasmine. It
is mostly another one of my attempts to implement something common to learn
about its complexities and nuances. So don't use this.

Example
-------

A simple test is a file suffixed with a `.spec.php` extension. You can run all
tests using `php spotspec run` from the directory where the specs are located
or provide a relative path from your current working directory at the end:
`php spotspec run ./spec`

A test may look something like the following:

.. code-block:: php

  <?php

  use Webspot\SpotSpec\Describe;

  return (new Describe('an example of a spec suite'))

      ->beforeEach(function() {
          $this->setUpDone = true;
      });

      ->it('expects a property on $this to be set', function() {
          return $this->expects($this->setUpDone)->toBeTrue();
      })

      ->it('expects the PHP function trim() to work', function() {
          return $this->expects(trim("\t\n test \n"))->toEqual('test');
      })
  ;

Which should give the following output when run:

.. code-block::

  OK   when describing an example of a spec suite
  -----------------------------------------------------
  [+] It expects a property on $this to be set
  [+] It expects the PHP function trim() to work
  -----------------------------------------------------

Though you don't have to use the Expectation object, you can just return a
boolean. Or you might combine multiple expectations:

.. code-block:: php

  <?php

  use Webspot\SpotSpec\Describe;

  return (new Describe('an example of a spec suite'))

      ->beforeEach(function() {
          $this->setUpDone = true;
      });

      ->it('expects a property on $this to be set', function() {
          return $this->setUpDone === true;
      })

      ->it('expects the PHP function trim() to work', function() {
          $value = trim("\t\n test \n");
          return $this->expects($value)->toEqual('test')
              && $this->expects($value)->toBeAn('integer');
      })
  ;

Now as the second test will obviously fail, the result will look like the
following after these modifications:

.. code-block::

  FAIL when describing an example of a spec suite
  -----------------------------------------------------
  [+] It expects a property on $this to be set
  [ ] It expects the PHP function trim() to work
  -----------------------------------------------------
