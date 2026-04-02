<?php

declare(strict_types=1);

namespace Modules\Notification\Traits;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Modules\Notification\Enums\EventType;
use Modules\Notification\Otp\Gateways\OtpGateway;
use Modules\Role\Enums\Permission;
use Modules\Settings\Models\Settings;
use Modules\User\Models\Profile;
use Modules\User\Models\User;

trait SmsTrait
{
    public function sendSmsOnRefund($smsArray)
    {
        try {
            $order = $smsArray['order'];
            $smsGateway = $this->getOtpGateway();
            $userType = $this->getWhichUserWillGetSms($smsArray['smsEventName'], $smsArray['language']);
            if ($userType['customer'] === true) {
                $smsGateway->sendSms($order->customer_contact, $smsArray['customerMessage']);
            }

            if ($userType['admin'] === true) {

                $adminList = $this->adminList();

                foreach ($adminList as $admin) {
                    $adminProfile = $admin->profile;
                    if ($adminProfile) {
                        $smsGateway->sendSms($adminProfile->contact, $smsArray['adminMessage']);
                    }
                }
            }
        } catch (Exception $e) {
            // Log::error($e->getMessage());
        }
    }

    /**
     * @param  $data
     * @return array
     */
    public function sendSmsOnOrderEvent($smsArray, $shouldSendToChildOrder = true): void
    {

        try {
            $order = $smsArray['order'];
            $smsGateway = $this->getOtpGateway();
            $userType = $this->getWhichUserWillGetSms($smsArray['smsEventName'], $smsArray['language']);

            if ($userType['customer'] && $order->parent_id === null) {
                $smsGateway->sendSms($order->customer_contact, $smsArray['customerMessage']);
                /* $customer = $order->customer;
                 if ($customer && $customer->profile && $customer->profile->contact) {
                     $smsGateway->sendSms($customer->profile->contact, $smsArray['customerMessage']);
                 }*/
            }
            if ($userType['admin']) {

                $adminList = $this->adminList();

                foreach ($adminList as $admin) {
                    $adminProfile = $admin->profile;
                    if ($adminProfile) {
                        $smsGateway->sendSms($adminProfile->contact, $smsArray['adminMessage']);
                    }
                }
            }
            if ($userType['vendor']) {
                $message = $smsArray['storeOwnerMessage'];
                if ($order->parent_id === null) {
                    if (! $shouldSendToChildOrder) {
                        return;
                    }
                    $childOrders = $order->children;

                    foreach ($childOrders as $childOrder) {
                        $storeOwner = $childOrder->shop->owner;
                        $shopOwnerProfile = Profile::where('customer_id', $storeOwner->id)->firstOrFail();

                        if ($shopOwnerProfile) {
                            $smsGateway->sendSms($shopOwnerProfile->contact, str_replace(':ORDER_TRACKING_NUMBER', $childOrder->tracking_number, $message));
                        }
                    }
                } else {
                    $storeOwner = $order->shop->owner;
                    $storeOwnerProfile = $storeOwner->profile;
                    if ($storeOwnerProfile && $storeOwnerProfile->contact) {
                        $smsGateway->sendSms($storeOwnerProfile->contact, str_replace(':ORDER_TRACKING_NUMBER', $order->tracking_number, $message));
                    }
                }
            }
        } catch (Exception $e) {
            // do nothing
            info('This exception info is from SmsTrait sendSmsOnOrderEvent method');
        }
    }

    /**
     * Get which user will get sms
     *
     * @return mixed
     */
    public function getWhichUserWillGetSms(string $smsEventName, string $language): array
    {
        return $this->getWhichUserWillGetEventSmsOrEmail($smsEventName, 'smsEvent', $language);
    }

    /**
     * Get admin List
     */
    public function adminList(): Collection
    {
        return User::permission(Permission::SuperAdmin->value)->get();
    }

    public function getWhichUserWillGetEmail($emailEventName, $language): array
    {
        return $this->getWhichUserWillGetEventSmsOrEmail($emailEventName, 'emailEvent', $language);
    }

    public function getWhichUserWillGetEventSmsOrEmail(string $eventName, string $eventType, string $language): array
    {
        $orderStatusChangeArray = [
            EventType::OrderCancelled->value, EventType::OrderDelivered->value, EventType::OrderCreated->value, EventType::OrderStatusChanged->value,
        ];
        if (in_array($eventName, $orderStatusChangeArray)) {
            $eventName = EventType::OrderStatusChanged->value;
        }
        if (in_array($eventName, [EventType::OrderPaymentFailed->value, EventType::OrderPaymentSuccess->value])) {
            $eventName = EventType::OrderPayment->value;
        }
        $userArray = ['customer' => false, 'admin' => false, 'vendor' => false];
        $settings = Settings::getData($language);
        if (! isset($settings->options[$eventType])) {
            return $userArray;
        }
        $options = $settings->options;
        foreach ($userArray as $key => $value) {
            if (isset($options[$eventType][$key][$eventName])) {
                $userArray[$key] = $options[$eventType][$key][$eventName];
            }
        }
        // send a test email

        return $userArray;
    }

    /**
     * Get OTP gateway
     *
     * @return OtpGateway
     */
    protected function getOtpGateway()
    {
        $gateway = config('auth.active_otp_gateway');
        $gateWayClass = "Modules\Notification\\Otp\\Gateways\\".ucfirst($gateway).'Gateway';

        return new OtpGateway(new $gateWayClass());
    }
}
