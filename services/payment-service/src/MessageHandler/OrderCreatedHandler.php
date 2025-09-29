<?php

namespace App\MessageHandler;

use App\Message\OrderCreatedMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsMessageHandler]
class OrderCreatedHandler
{
    public function __construct(private LoggerInterface $logger, private HttpClientInterface $http)
    {
    }

    public function __invoke(OrderCreatedMessage $msg)
    {
        // Simulate payment processing
        dump('Processing payment for Order ID: '.$msg->getOrderId().' Amount: '.$msg->getTotal());
        
        $this->logger->info('Payment service received order', ['orderId' => $msg->getOrderId()]);

        // simulate processing delay
        sleep(2);

        // simulate success/failure randomly
        $success = random_int(1, 100) > 10; // 90% success

        if ($success) {
            $this->logger->info('Payment success', ['orderId' => $msg->getOrderId()]);
            // notify order-service that payment succeeded
            $this->http->request('POST', getenv('ORDER_SERVICE_URL').'/orders/'.$msg->getOrderId().'/mark-paid');
        } else {
            $this->logger->warning('Payment failed', ['orderId' => $msg->getOrderId()]);
            // optionally call back order-service to mark failed - omitted for brevity
        }
    }
}