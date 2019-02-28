<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-08-31
 * Time: 15:19
 */

namespace SwoKit\Context;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface ContextInterface
 * @package SwoKit\Context
 *
 * @property string $id The request context unique ID
 */
interface ContextInterface
{
    /**
     * @return string|int
     */
    public function getId();

    /**
     * @param string $id
     */
    public function setId($id): void;

    /**
     * @return string
     */
    public function getKey(): string;

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void;

    /**
     * destroy something ...
     */
    public function destroy(): void;

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @param array $data
     */
    public function setData(array $data): void;
}
