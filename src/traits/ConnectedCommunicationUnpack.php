<?php

declare(strict_types=1);

namespace chaser\stream\traits;

use chaser\stream\interfaces\part\ConnectedCommunicationUnpackInterface;
use chaser\stream\event\{Message, RecvBufferFull, UnpackingFail};
use chaser\stream\exception\UnpackedException;
use Stringable;

/**
 * 通信包装部分特征
 *
 * @package chaser\stream\traits
 *
 * @property-read int $maxRecvBufferSize
 *
 * @uses CommunicationConnected
 */
trait ConnectedCommunicationUnpack
{
    /**
     * 接收缓冲区内容
     *
     * @var string
     */
    protected string $recvBuffer = '';

    /**
     * 整包字节数
     *
     * @var int|null
     */
    protected ?int $unpackBytes = null;

    /**
     * @inheritDoc
     */
    public static function configurations(): array
    {
        return ['maxRecvBufferSize' => ConnectedCommunicationUnpackInterface::MAX_RECV_BUFFER_SIZE];
    }

    /**
     * @inheritDoc
     */
    protected function readHandle(string $data): void
    {
        $this->recvBuffer .= $data;
        try {
            $message = $this->unpack();
            if ($message) {
                $this->dispatch(Message::class, $message);
            } elseif (strlen($this->recvBuffer) >= $this->maxRecvBufferSize) {
                $this->dispatch(RecvBufferFull::class);
                $this->destroy(true);
            }
        } catch (UnpackedException $e) {
            $this->dispatch(UnpackingFail::class, $e);
            $this->destroy(true);
        }
    }

    /**
     * 尝试解包
     *
     * @return string|Stringable|null
     */
    protected function unpack(): string|Stringable|null
    {
        if ($this->unpackBytes === null) {
            if (null === $size = $this->tryToGetPackageSize()) {
                return null;
            }
            $this->unpackBytes = $size;
        }

        if (strlen($this->recvBuffer) >= $this->unpackBytes) {
            $request = $this->getRequest();
            $this->recvBuffer = substr($this->recvBuffer, $this->unpackBytes);
            $this->unpackReset();
            return $request;
        }

        return null;
    }
}
