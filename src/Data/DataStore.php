<?php
namespace Gwa\Wordpress\Zero\Data;

use InvalidArgumentException;

abstract class DataStore
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @throws \InvalidArgumentException
     */
    public function set($key, $value)
    {
        if (method_exists($this, 'validate' . ucfirst($key))) {
            $value = call_user_func([$this, 'validate' . ucfirst($key)], $value);
        }

        $this->checkKeyExistInDefault($key);

        $this->data[$key] = $value;

        return $this;
    }

    /**
     * @param  string $key
     *
     * @throws \InvalidArgumentException
     */
    protected function checkKeyExistInDefault($key)
    {
        if (!array_key_exists($key, $this->getDefaults())) {
            throw new InvalidArgumentException(sprintf('Key[%s] dont exists in defaults array.', $key));
        }
    }

    /**
     * @param  string $key
     *
     * @throws \InvalidArgumentException
     * @return mixed
     *
     */
    public function get($key)
    {
        if (!array_key_exists($key, $this->getDefaults())) {
            throw new InvalidArgumentException(sprintf('Key[%s] dont exists.', $key));
        }

        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        return $this->getDefaults()[$key];
    }

    /**
     * @return  array
     */
    abstract public function getDefaults();
}
