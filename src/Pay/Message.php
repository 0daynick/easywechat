<?php

namespace EasyWeChat\Pay;

class Message extends \EasyWeChat\Kernel\Message
{
    public function getOriginalAttributes(): array
    {
        return json_decode($this->getOriginalContents(), true);
    }

    public function getEventType(): ?string
    {
        return $this->getOriginalAttributes()['event_type'] ?? null;
    }
}