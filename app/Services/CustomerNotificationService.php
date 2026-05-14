<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Order;
use App\Models\User;

class CustomerNotificationService
{
    public function send(?User $customer, array $data): ?Notification
    {
        if (! $customer) {
            return null;
        }

        return Notification::create([
            'title' => $data['title'] ?? null,
            'message' => $data['message'] ?? null,
            'type' => $data['type'] ?? Notification::TYPE_SYSTEM,
            'icon' => $data['icon'] ?? 'fas fa-bell',
            'color' => $data['color'] ?? '#df71e5',
            'notifiable_id' => $customer->id,
            'notifiable_type' => User::class,
            'related_id' => $data['related_id'] ?? null,
            'related_type' => $data['related_type'] ?? null,
            'action_url' => $data['action_url'] ?? null,
            'data' => $data['data'] ?? null,
            'is_read' => 0,
        ]);
    }

    public function orderPlaced(Order $order): ?Notification
    {
        return $this->send($order->customer, [
            'title' => 'Order placed',
            'message' => $order->order_number . ' placed successfully. Invoice generated.',
            'type' => Notification::TYPE_ORDER,
            'icon' => 'fas fa-bag-shopping',
            'color' => '#111111',
            'related_id' => $order->id,
            'related_type' => Order::class,
            'action_url' => route('frontend.orders.show', $order),
        ]);
    }

    public function invoiceGenerated(Order $order): ?Notification
    {
        if (! $order->latestInvoice) {
            return null;
        }

        return $this->send($order->customer, [
            'title' => 'Invoice ready',
            'message' => $order->latestInvoice->invoice_number . ' is ready for your order.',
            'type' => Notification::TYPE_INVOICE,
            'icon' => 'fas fa-file-invoice',
            'color' => '#4F46E5',
            'related_id' => $order->latestInvoice->id,
            'related_type' => get_class($order->latestInvoice),
            'action_url' => route('frontend.invoices.show', $order->latestInvoice),
        ]);
    }

    public function paymentPaid(Order $order): ?Notification
    {
        $receipt = optional($order->latestPayment)->receipts()->latest()->first();

        return $this->send($order->customer, [
            'title' => 'Payment received',
            'message' => 'Payment for ' . $order->order_number . ' is marked paid.',
            'type' => Notification::TYPE_PAYMENT,
            'icon' => 'fas fa-receipt',
            'color' => '#1f7a35',
            'related_id' => optional($receipt)->id ?: $order->id,
            'related_type' => $receipt ? get_class($receipt) : Order::class,
            'action_url' => $receipt ? route('frontend.receipts.show', $receipt) : route('frontend.orders.show', $order),
        ]);
    }

    public function deliveryStatus(Order $order, string $status): ?Notification
    {
        return $this->send($order->customer, [
            'title' => 'Delivery update',
            'message' => $order->order_number . ' is now ' . str_replace('_', ' ', $status) . '.',
            'type' => Notification::TYPE_DELIVERY,
            'icon' => 'fas fa-truck-fast',
            'color' => '#0ea5e9',
            'related_id' => $order->id,
            'related_type' => Order::class,
            'action_url' => $order->deliveryTracking ? route('frontend.tracking.show', $order->deliveryTracking) : route('frontend.orders.show', $order),
        ]);
    }

    public function returnRequested(Order $order): ?Notification
    {
        return $this->send($order->customer, [
            'title' => 'Return requested',
            'message' => 'Return request submitted for ' . $order->order_number . '.',
            'type' => Notification::TYPE_RETURN,
            'icon' => 'fas fa-rotate-left',
            'color' => '#f59e0b',
            'related_id' => $order->id,
            'related_type' => Order::class,
            'action_url' => route('frontend.orders.show', $order),
        ]);
    }
}
