<?php

namespace Webspot\SpotSpec;

/**
 * @method  bool  toBeA($type)
 * @method  bool  toNotBeA($type)
 * @method  bool  toBeAn($type)
 * @method  bool  toNotBeAn($type)
 * @method  bool  toBeTruthy()
 * @method  bool  toNotBeTruthy()
 * @method  bool  toBeFalsy()
 * @method  bool  toNotBeFalsy()
 * @method  bool  toBeNull()
 * @method  bool  toNotBeNull()
 * @method  bool  toBeTrue()
 * @method  bool  toNotBeTrue()
 * @method  bool  toBeFalse()
 * @method  bool  toNotBeFalse()
 * @method  bool  toEqual($match)
 * @method  bool  toNotEqual($match)
 * @method  bool  toBeLike($match)
 * @method  bool  toNotBeLike($match)
 * @method  bool  toContain($match)
 * @method  bool  toNotContain($match)
 * @method  bool  toBeGreaterThan($match)
 * @method  bool  toNotGreaterThan($match)
 * @method  bool  toBeLesserThan($match)
 * @method  bool  toNotBeLesserThan($match)
 * @method  bool  toValidate(\Closure $validator)
 * @method  bool  toNotValidate(\Closure $validator)
 * @method  bool  toThrowException($exceptionClass)
 * @method  bool  toNotThrowException($exceptionClass)
 * @method  bool  toBeExecutable()
 * @method  bool  toNotBeExecutable()
 */
class Expectation
{
    /** @var  mixed */
    private $value;

    /**
     * @param  mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function beA($type)
    {
        if (strtolower($type) === 'scalar') {
            return is_scalar($this->value);
        } elseif (strtolower($type) === 'object') {
            return is_object($this->value);
        } elseif (strtolower($type) === 'callable') {
            return is_callable($this->value);
        } elseif (!is_object($this->value)) {
            return gettype($this->value) === strtolower($type);
        }

        return $this->value instanceof $type;
    }

    public function beAn($type)
    {
        return $this->beA($type);
    }

    public function beTruthy()
    {
        return $this->value == true;
    }

    public function beFalsy()
    {
        return $this->value == false;
    }

    public function beNull()
    {
        return is_null($this->value);
    }

    public function beTrue()
    {
        return $this->value === true;
    }

    public function beFalse()
    {
        return $this->value === false;
    }

    public function equal($match)
    {
        return $this->value === $match;
    }

    public function beLike($match)
    {
        return $this->value == $match;
    }

    public function contain($value)
    {
        if (is_array($this->value)) {
            return in_array($value, $this->value);
        } elseif (is_string($this->value)) {
            return strpos($this->value, $value) !== false;
        }

        throw new \BadMethodCallException('Cannot use contain, only available on strings & arrays.');
    }

    public function beGreaterThan($value)
    {
        return $this->value > $value;
    }

    public function beLesserThan($value)
    {
        return $this->value < $value;
    }

    public function validate(\Closure $validator)
    {
        return call_user_func($validator, $this->value);
    }

    public function throwException($class)
    {
        if (!is_callable($this->value)) {
            return false;
        }

        try {
            $this->value = call_user_func_array($this->value, array_slice(func_get_args(), 1));
            return false;
        } catch (\Exception $e) {
            return get_class($e) === $class;
        }
    }

    public function beExecutable()
    {
        if (!is_callable($this->value)) {
            return false;
        }

        try {
            $this->value = call_user_func_array($this->value, func_get_args());
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function __call($method, array $args)
    {
        $negation = false;
        if (strpos($method, 'toNot') === 0) {
            $method = lcfirst(substr($method, 5));
            $negation = true;
        } elseif (strpos($method, 'to') === 0) {
            $method = lcfirst(substr($method, 2));
        }

        $method = new \ReflectionMethod($this, $method);
        if (!$method->isPublic()) {
            throw new \BadMethodCallException('Unavailable matcher');
        }

        $result = $method->invokeArgs($this, $args);
        return $negation ? !$result : $result;
    }
}
