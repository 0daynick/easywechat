<?php

declare(strict_types=1);

namespace EasyWeChat\OpenPlatform;

use EasyWeChat\Kernel\Exceptions\RuntimeException;
use EasyWeChat\OpenPlatform\Contracts\VerifyTicket as VerifyTicketInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

class VerifyTicket implements VerifyTicketInterface
{
    protected CacheInterface $cache;

    public function __construct(
        protected string $appId,
        ?CacheInterface $cache = null,
        protected ?string $key = null,
    ) {
        $this->cache = $cache ?? new Psr16Cache(new FilesystemAdapter(namespace: 'easywechat', defaultLifetime: 1500));
    }

    public function getKey(): string
    {
        return $this->key ?? $this->key = \sprintf('open_platform.verify_ticket.%s', $this->appId);
    }

    public function setKey(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    public function setTicket(string $ticket): static
    {
        $this->cache->set($this->getKey(), $ticket, 6000);

        return $this;
    }

    /**
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function getTicket(): string
    {
        $ticket = $this->cache->get($this->getKey());

        if (!$ticket) {
            throw new RuntimeException('No component_verify_ticket found.');
        }

        return $ticket;
    }
}
