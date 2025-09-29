<?php

namespace App\Controller;

use App\Entity\Order;
use App\Message\OrderCreatedMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/orders', name: 'app_order_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, MessageBusInterface $bus): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $items = $data['items'] ?? [];
        $total = $data['total'] ?? 0;

        $order = new Order();
        $order->setItems($items);
        $order->setTotal($total);

        $em->persist($order);
        $em->flush();

        // Dispatch message
        $bus->dispatch(new OrderCreatedMessage(
            $order->getId(),
            $order->getTotal(), 
            $items
        ));

        return new JsonResponse([
            'orderId' => $order->getId(),
            'status' => $order->getStatus()
        ]);
    }

    #[Route('/orders/{id}/mark-paid', name: 'app_order_mark_paid', methods: ['POST'])]
    public function markPaid(int $id, EntityManagerInterface $em): JsonResponse
    {
        $order = $em->getRepository(Order::class)->find($id);
        
        if (!$order) {
            return new JsonResponse(['error' => 'Order not found'], 404);
        }

        $order->setStatus('paid');
        $em->flush();

        return new JsonResponse(['status' => 'success']);
    }
}