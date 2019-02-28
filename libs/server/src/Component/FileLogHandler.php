<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-09-25
 * Time: 16:45
 */

namespace Swokit\Server\Component;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Swokit\Server\KitServer;
use Swoole\Async;
use Toolkit\PhpUtil\PhpHelper;

/**
 * Class FileLogHandler
 * @package Sws\Async
 */
class FileLogHandler extends AbstractProcessingHandler
{
    public const SPLIT_NO   = 0;
    public const SPLIT_DAY  = 1;
    public const SPLIT_HOUR = 2;

    protected $file;
    private $errorMessage;
    protected $filePermission;

    private $dirCreated;

    public $onWriteEnd;

    /** @var int */
    private $splitType;

    /**
     * @var KitServer
     */
    protected $server;

    /**
     * @param string   $file
     * @param bool|int $level The minimum logging level at which this handler will be triggered
     * @param int|null $filePermission Optional file permissions (default (0644) are only for owner read/write)
     * @param int      $splitType
     * @throws \InvalidArgumentException
     */
    public function __construct($file, $level = Logger::DEBUG, $splitType = self::SPLIT_NO, $filePermission = null)
    {
        parent::__construct($level);

        if (\is_string($file)) {
            $this->file = $file;
        } else {
            throw new \InvalidArgumentException('A stream must either be a resource or a string.');
        }

        $this->filePermission = $filePermission;
        $this->splitType      = $splitType;

        // fix it
        if ($this->splitType && !\in_array($this->splitType, [self::SPLIT_DAY, self::SPLIT_HOUR], true)) {
            $this->splitType = self::SPLIT_DAY;
        }
    }

    /**
     * @param KitServer $server
     */
    public function setServer(KitServer $server): void
    {
        $this->server = $server;
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $record): void
    {
        if (null === $this->file || '' === $this->file) {
            throw new \LogicException('Missing stream file, the stream can not be opened. This may be caused by a premature call to close().');
        }
        $this->createDir();
        $this->errorMessage = null;

        $info = pathinfo($this->file);
        $dir  = $info['dirname'];
        $name = $info['filename'] ?? 'unknown';
        $ext  = $info['extension'] ?? 'log';

        // e.g {/var/logs}/{sws}_{20170927_18}.{log}
        $file = sprintf('%s/%s_%s.%s', $dir, $name, $this->getFilenameSuffix(), $ext);

        set_error_handler([$this, 'customErrorHandler']);
        if ($this->filePermission !== null) {
            @chmod($file, $this->filePermission);
        }
        restore_error_handler();

        if (!$this->asyncIsEnabled()) {
            file_put_contents($file, (string)$record['formatted'], FILE_APPEND);
        } else {
            Async::writeFile($file, (string)$record['formatted'], $this->onWriteEnd, FILE_APPEND);
        }
    }

    /**
     * Processes a record.
     *
     * @param  array $record
     * @return array
     */
    protected function processRecord(array $record): array
    {
        if ($this->processors) {
            foreach ($this->processors as $processor) {
                $record = PhpHelper::call($processor, $record);
            }
        }

        return $record;
    }

    protected function asyncIsEnabled(): bool
    {
        return class_exists(Async::class, false) &&
            $this->server &&
            $this->server->isBootstrapped() &&
            !$this->server->isTaskWorker();
    }

    /**
     * {@inheritdoc}
     */
    public function close(): void
    {
        $this->file   = null;
        $this->server = null;
    }

    /**
     * Return the stream URL if it was configured with a URL and not an active resource
     * @return string|null
     */
    public function getFile(): ?string
    {
        return $this->file;
    }

    private function customErrorHandler($code, $msg): void
    {
        $this->errorMessage = "(code: $code)" . preg_replace('{^(fopen|mkdir)\(.*?\): }', '', $msg);
    }

    /**
     * @param string $stream
     * @return null|string
     */
    private function getDirFromStream($stream): ?string
    {
        $pos = strpos($stream, '://');
        if ($pos === false) {
            return \dirname($stream);
        }

        if (0 === strpos($stream, 'file://')) {
            return \dirname(substr($stream, 7));
        }

        return null;
    }

    private function createDir(): void
    {
        // Do not try to create dir if it has already been tried.
        if ($this->dirCreated) {
            return;
        }

        $dir = $this->getDirFromStream($this->file);
        if (null !== $dir && !is_dir($dir)) {
            $this->errorMessage = null;
            set_error_handler([$this, 'customErrorHandler']);
            $status = mkdir($dir, 0777, true);
            restore_error_handler();
            if (false === $status) {
                throw new \UnexpectedValueException(sprintf('There is no existing directory at "%s" and its not buildable: ' . $this->errorMessage,
                    $dir));
            }
        }
        $this->dirCreated = true;
    }

    /**
     * @param string $file
     * @return bool
     */
    protected function fileIsChanged($file): bool
    {
        if (!$this->splitType) {
            return false;
        }

        $str = $this->getFilenameSuffix();

        return !strpos($file, '_' . $str);
    }

    /**
     * @return string
     */
    public function getFilenameSuffix(): string
    {
        $str = '';

        if ($this->splitType === self::SPLIT_DAY) {
            $str = date('Ymd');
        } elseif ($this->splitType === self::SPLIT_HOUR) {
            $str = date('Ymd_H');
        }

        return $str;
    }

}
