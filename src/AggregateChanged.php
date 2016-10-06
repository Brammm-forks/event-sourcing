<?php
/**
 * This file is part of the prooph/event-sourcing.
 * (c) 2014-2016 prooph software GmbH <contact@prooph.de>
 * (c) 2015-2016 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\EventSourcing;

use Assert\Assertion;
use Prooph\Common\Messaging\DomainEvent;

/**
 * AggregateChanged
 *
 * @author Alexander Miertsch <contact@prooph.de>
 * @package Prooph\EventSourcing
 */
class AggregateChanged extends DomainEvent
{
    /**
     * @var array
     */
    protected $payload = [];

    public static function occur(string $aggregateId, array $payload = []) : self
    {
        return new static($aggregateId, $payload);
    }

    protected function __construct(string $aggregateId, array $payload, array $metadata = [])
    {
        //Metadata needs to be set before setAggregateId is called
        $this->metadata = $metadata;
        $this->setAggregateId($aggregateId);
        $this->setPayload($payload);
        $this->init();
    }

    public function aggregateId(): string
    {
        return $this->metadata['aggregate_id'];
    }

    /**
     * Return message payload as array
     *
     * The payload should only contain scalar types and sub arrays.
     * The payload is normally passed to json_encode to persist the message or
     * push it into a message queue.
     */
    public function payload(): array
    {
        return $this->payload;
    }

    protected function setAggregateId(string $aggregateId): void
    {
        Assertion::string($aggregateId);
        Assertion::notEmpty($aggregateId);

        $this->metadata['aggregate_id'] = $aggregateId;
    }

    /**
     * This method is called when message is instantiated named constructor fromArray
     */
    protected function setPayload(array $payload): void
    {
        $this->payload = $payload;
    }
}
