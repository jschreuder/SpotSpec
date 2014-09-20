<?php

namespace Webspot\SpotSpec;

class Describe
{
    /** @var  string */
    private $description;

    /** @var  \Closure[]  definitions indexed by descriptions */
    private $specs = [];

    /** @var  \Closure */
    private $before;

    /** @var  \Closure */
    private $beforeEach;

    /** @var  \Closure */
    private $after;

    /** @var  \Closure */
    private $afterEach;

    /** @var  bool[]  results indexed by descriptions */
    private $result;

    /** @var  array */
    private $data = [];

    /**
     * @param  string $description
     */
    public function __construct($description)
    {
        $this->description = $description;
    }

    /**
     * @return  string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the specification descriptions as keys with their result as the value
     *
     * @return  \bool[]
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param   string $description
     * @param   callable $spec
     * @return  $this
     */
    public function it($description, \Closure $spec)
    {
        if (isset($this->specs[$description])) {
            throw new \DomainException('Description already in use.');
        }

        $this->specs[$description] = $spec;
        return $this;
    }

    /**
     * @param   callable $before
     * @return  $this
     */
    public function before(\Closure $before)
    {
        $this->before = $before;
        return $this;
    }

    /**
     * @param   callable $before
     * @return  $this
     */
    public function beforeEach(\Closure $before)
    {
        $this->beforeEach = $before;
        return $this;
    }

    /**
     * @param   callable $after
     * @return  $this
     */
    public function after(\Closure $after)
    {
        $this->after = $after;
        return $this;
    }

    /**
     * @param   callable $after
     * @return  $this
     */
    public function afterEach(\Closure $after)
    {
        $this->afterEach = $after;
        return $this;
    }

    /**
     * @return  bool
     */
    public function run()
    {
        $this->before && call_user_func($this->before);
        $resetData = $this->data;
        foreach ($this->specs as $description => $spec) {
            $this->data = $resetData;
            $this->result[$description] = $this->runSpec($spec);
        }
        $this->after && call_user_func($this->after);

        return count($this->result) === count(array_filter($this->result));
    }

    /**
     * @param   \Closure $spec
     * @return  bool
     */
    public function runSpec(\Closure $spec)
    {
        $this->beforeEach && call_user_func($this->beforeEach);
        $result = call_user_func($spec);
        $this->afterEach && call_user_func($this->afterEach);
        return $result;
    }

    /**
     * @param   mixed $value
     * @return  Expectation
     */
    public function expect($value)
    {
        return new Expectation($value);
    }

    /**
     * @param   string $key
     * @param   mixed $data
     * @return  void
     */
    public function __set($key, $data)
    {
        $this->data[$key] = $data;
    }

    /**
     * @param   string $key
     * @return  mixed
     */
    public function __get($key)
    {
        if (!isset($this->data[$key])) {
            throw new \OutOfBoundsException('No data set for: '.$key);
        }
        return $this->data[$key];
    }

    /**
     * @param   string $key
     * @return  bool
     */
    public function __isset($key)
    {
        return array_key_exists($key, $this->data);
    }
}
