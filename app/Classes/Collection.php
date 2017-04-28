<?php

namespace App\Classes;

use \Exception;
use App\Exceptions\InvalidKeyException;
use App\Exceptions\KeyInUseException;
use App\Exceptions\InvalidCollectionItemException;

abstract class Collection
{
    protected $items = array();
    protected $allowedClass;

    public function __construct(string $allowedClass)
    {
        $this->allowedClass = $allowedClass;
    }

    /**
     * @todo - add doc notation!
     */
    public function keyExists($key): bool
    {
        return isset($this->items[$key]);
    }

    /**
     * @todo - add doc notation!
     */
    public function addItem($item, $key = null): void
    {
        if ( ! is_object($item) || ! $item instanceof $this->allowedClass) {
            throw new InvalidCollectionItemException('Supplied item is either not an object or is not an instance of the allowed class.');
        }

        if ($key === null) {
            $this->items[] = $item;
            return;
        }

        if (isset($this->items[$key])) {
            throw new KeyInUseException('Key already in use: ' . $key);
        }

        $this->items[$key] = $item;
    }

    /**
     * @todo - add doc notation!
     */
    public function deleteItem($key): void
    {
        if ( ! isset($this->items[$key])) {
            throw new InvalidKeyException('Invalid key: ' . $key);
        }

        unset($this->items[$key]);
    }

    /**
     * @todo - add doc notation!
     */
    public function getItem($key)
    {
        if ( ! isset($this->items[$key])) {
            throw new InvalidKeyException('Invalid key ' . $key . '!');
        }

        return $this->items[$key];
    }

    /**
     * @todo - add doc notation!
     */
    public function getKeys(): array
    {
        return array_keys($this->items);
    }

    /**
     * @todo - add doc notation!
     */
    public function length(): int
    {
        return count($this->items);
    }
}