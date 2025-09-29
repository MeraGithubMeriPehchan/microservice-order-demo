<?php

namespace App\Message;

class OrderCreatedMessage
{
    public function __construct(
        private int $orderId,
        private float $total,
        private array $items = []
    ) {}

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function __serialize(): array
    {
        return [
            'orderId' => $this->orderId,
            'total'   => $this->total,
            'items'   => $this->items,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->orderId = $data['orderId'];
        $this->total   = $data['total'];
        $this->items   = $data['items'];
    }
}