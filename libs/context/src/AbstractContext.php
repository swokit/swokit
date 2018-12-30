<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-08-29
 * Time: 11:38
 */

namespace SwoKit\Context;

use SwoKit\Context\Traits\ArrayAccessByPropertyTrait;

/**
 * Class Context
 * @package SwoKit\Context
 */
abstract class AbstractContext implements ContextInterface, \ArrayAccess
{
    use ArrayAccessByPropertyTrait;

    /**
     * it is `request->fd` OR `\Swoole\Coroutine::getuid()`
     * @var int|string
     */
    protected $id = 0;

    /**
     * a unique ID string generate by $id
     * @var string
     */
    protected $key;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @param $id
     * @return string
     */
    public static function genKey($id): string
    {
        return \md5($id . \getmypid());
    }

    /**
     * Context constructor.
     */
    public function __construct()
    {
        // do something ...
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
        ];
    }

    /**
     * destructor
     */
    public function __destruct()
    {
        $this->destroy();
    }

    /**
     * destroy
     */
    public function destroy()
    {
        $this->data = [];
        $this->id = $this->key = null;
    }

    /*******************************************************************************
     * getter/setter methods
     ******************************************************************************/

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

}
