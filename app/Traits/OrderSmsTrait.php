<?php

namespace Modules\Notification\Traits;

use Illuminate\Support\Facades\App;
use Modules\Notification\Enums\EventType;
use Modules\Order\Models\Order;

trait OrderSmsTrait
{
    use SmsTrait;

    public function sendOrderCancelSms(Order $order): void
    {
        $language = $order->language;
        App::setLocale($language);
        $customerName = $this->getCustomerName($order);
        $smsArray = [
            'order' => $order,
            'language' => $order->language ?? DEFAULT_LANGUAGE,
            'smsEventName' => EventType::ORDER_CANCELLED,
            'adminMessage' => __('notification::sms.order.cancelOrder.admin.message', ['ORDER_TRACKING_NUMBER' => $order->tracking_number, 'customer_name' => $customerName]),
            'customerMessage' => __('notification::sms.order.cancelOrder.customer.message', ['ORDER_TRACKING_NUMBER' => $order->tracking_number, 'customer_name' => $customerName]),
            'storeOwnerMessage' => __('notification::sms.order.cancelOrder.storeOwner.message'),
        ];
        $this->sendSmsOnOrderEvent($smsArray);
    }

    public function sendOrderCreationSms(Order $order): void
    {
        $language = $order->language;
        App::setLocale($language);
        $smsArray = [
            'order' => $order,
            'language' => $order->language ?? DEFAULT_LANGUAGE,
            'smsEventName' => EventType::ORDER_CREATED,
            'adminMessage' => __('notification::sms.order.orderCreated.admin.message', ['ORDER_TRACKING_NUMBER' => $order->tracking_number]),
            'customerMessage' => __('notification::sms.order.orderCreated.customer.message', ['ORDER_TRACKING_NUMBER' => $order->tracking_number]),
            'storeOwnerMessage' => __('notification::sms.order.orderCreated.storeOwner.message'),
        ];
        $this->sendSmsOnOrderEvent($smsArray);
    }

    public function sendPaymentDoneSuccessfullySms(Order $order): void
    {
        $language = $order->language;
        App::setLocale($language);
        $smsArray = [
            'order' => $order,
            'language' => $order->language ?? DEFAULT_LANGUAGE,
            'smsEventName' => EventType::ORDER_PAYMENT_SUCCESS,
            'adminMessage' => __('notification::sms.order.paymentSuccessOrder.admin.message', ['ORDER_TRACKING_NUMBER' => $order->tracking_number]),
            'customerMessage' => __('notification::sms.order.paymentSuccessOrder.customer.message', ['ORDER_TRACKING_NUMBER' => $order->tracking_number]),
            'storeOwnerMessage' => __('notification::sms.order.paymentSuccessOrder.storeOwner.message'),
        ];
        $this->sendSmsOnOrderEvent($smsArray);
    }

    public function sendOrderStatusChangeSms(Order $order): void
    {
        $language = $order->language;
        App::setLocale($language);
        $status = ucfirst(str_replace('-', ' ', $order->order_status));
        $smsArray = [
            'order' => $order,
            'language' => $order->language ?? DEFAULT_LANGUAGE,
            'smsEventName' => EventType::ORDER_STATUS_CHANGED,
            'adminMessage' => __('notification::sms.order.statusChangeOrder.admin.message', [
                'ORDER_TRACKING_NUMBER' => $order->tracking_number,
                'order_status' => $status,
            ]),
            'customerMessage' => __('notification::sms.order.statusChangeOrder.customer.message', [
                'ORDER_TRACKING_NUMBER' => $order->tracking_number,
                'order_status' => $status,
            ]),
            'storeOwnerMessage' => __('notification::sms.order.statusChangeOrder.storeOwner.message', ['order_status' => $status]),
        ];
        $this->sendSmsOnOrderEvent($smsArray, false);
    }

    public function sendOrderDeliveredSms($order): void
    {
        $language = $order->language;
        App::setLocale($language);
        $smsArray = [
            'order' => $order,
            'language' => $order->language ?? DEFAULT_LANGUAGE,
            'smsEventName' => EventType::ORDER_DELIVERED,
            'adminMessage' => __('notification::sms.order.deliverOrder.admin.message', ['ORDER_TRACKING_NUMBER' => $order->tracking_number]),
            'customerMessage' => __('notification::sms.order.deliverOrder.customer.message', ['ORDER_TRACKING_NUMBER' => $order->tracking_number]),
            'storeOwnerMessage' => __('notification::sms.order.deliverOrder.storeOwner.message'),
        ];
        $this->sendSmsOnOrderEvent($smsArray, false);
    }

    public function sendPaymentFailedSms($order): void
    {
        $language = $order->language;
        App::setLocale($language);
        $smsArray = [
            'order' => $order,
            'language' => $order->language ?? DEFAULT_LANGUAGE,
            'smsEventName' => EventType::ORDER_PAYMENT_FAILED,
            'adminMessage' => __('notification::sms.order.paymentFailedOrder.admin.message', ['ORDER_TRACKING_NUMBER' => $order->tracking_number]),
            'customerMessage' => __('notification::sms.order.paymentFailedOrder.customer.message', ['ORDER_TRACKING_NUMBER' => $order->tracking_number]),
            'storeOwnerMessage' => __('notification::sms.order.paymentFailedOrder.storeOwner.message'),
        ];
        $this->sendSmsOnOrderEvent($smsArray, false);
    }

    protected function getCustomerName($order)
    {
        $customerName = $order->customer;
        if (! $customerName) {
            $customerName = 'Guest Customer';
        } else {
            $customerName = $order->customer->name;
        }

        return $customerName;
    }

    public function sendRefundRequestedSms($refund): void
    {
        $order = $refund->order;
        $language = $order->language;
        App::setLocale($language);
        $smsArray = [
            'order' => $order,
            'language' => $order->language ?? DEFAULT_LANGUAGE,
            'smsEventName' => EventType::ORDER_REFUND,
            'adminMessage' => __('notification::sms.order.refundRequested.admin.message', ['ORDER_TRACKING_NUMBER' => $order->tracking_number]),
            'customerMessage' => __('notification::sms.order.refundRequested.customer.message', ['ORDER_TRACKING_NUMBER' => $order->tracking_number]),
        ];
        $this->sendSmsOnRefund($smsArray);
    }

    public function sendRefundUpdateSms($refund): void
    {
        $order = $refund->order;
        $language = $order->language;
        App::setLocale($language);
        $smsArray = [
            'order' => $order,
            'language' => $order->language ?? DEFAULT_LANGUAGE,
            'smsEventName' => EventType::ORDER_REFUND,
            'adminMessage' => __('notification::sms.order.refundUpdated.admin.message', ['ORDER_TRACKING_NUMBER' => $order->tracking_number, ':refund_status' => $refund->status]),
            'customerMessage' => __('notification::sms.order.refundUpdated.customer.message', ['ORDER_TRACKING_NUMBER' => $order->tracking_number, ':refund_status' => $refund->status]),
        ];
        $this->sendSmsOnRefund($smsArray);
    }
}
