<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-11
 * Time: 10:08
 */

namespace SwoKit\Context;

/**
 * Interface ContextManagerInterface
 * @package SwoKit\Context
 */
interface ContextManagerInterface
{
    /**
     * has context
     *
     * @param string $id
     * @return boolean
     */
    public function has($id): bool;

    /**
     * @param ContextInterface $context
     */
    public function add(ContextInterface $context);

    /**
     * @param string|int $id
     * @return ContextInterface|null
     */
    public function get($id = null);

    /**
     * @param int|string|ContextInterface $id
     * @return ContextInterface|null
     */
    public function del($id = null);

    /**
     * @return int
     */
    public function count():int ;

    /**
     * @return \ArrayIterator
     */
    public function getIterator();
}
