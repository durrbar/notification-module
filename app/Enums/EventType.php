<?php

declare(strict_types=1);

namespace Modules\Notification\Enums;

enum EventType: string
{
    case OrderCancelled = 'cancelOrder';
    case OrderCreated = 'createOrder';
    case OrderDelivered = 'deliverOrder';
    case OrderPayment = 'paymentOrder';
    case OrderPaymentFailed = 'paymentFailedOrder';
    case OrderPaymentSuccess = 'paymentSuccessOrder';
    case OrderRefund = 'refundOrder';
    case OrderStatusChanged = 'statusChangeOrder';
    case OrderUpdated = 'updateOrder';
    case QuestionAnswered = 'answerQuestion';
    case QuestionCreated = 'createQuestion';
    case ReviewCreated = 'createReview';
}
