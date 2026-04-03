<?php

declare(strict_types=1);

namespace Modules\Notification\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use Modules\Ecommerce\Enums\DefaultStatusType;
use Modules\Ecommerce\Enums\ProductStatus;
use Modules\Ecommerce\Models\Product;
use Modules\Notification\Notifications\TransferredShopOwnershipStatus;
use Modules\User\Models\User;
use Modules\User\Traits\UsersTrait;
use Modules\Vendor\Events\OwnershipTransferStatusControl;
use Modules\Vendor\Models\OwnershipTransfer;
use Modules\Vendor\Models\Shop;

class OwnershipTransferStatusControlListener implements ShouldQueue
{
    use UsersTrait;

    /**
     * Handle the event.
     */
    public function handle(OwnershipTransferStatusControl $event): void
    {
        switch ($event->ownershipTransfer->status) {
            case DefaultStatusType::Processing->value:
                $this->processingOwnerShipTransferStatus($event->ownershipTransfer);
                break;

            case DefaultStatusType::Approved->value:
                $this->approvedOwnerShipTransferStatus($event->ownershipTransfer);
                break;

            case DefaultStatusType::Rejected->value:
                $this->rejectingOwnerShipTransferStatus($event->ownershipTransfer);
                break;
        }
    }

    public function processingOwnerShipTransferStatus(OwnershipTransfer $ownershipRequest): void
    {
        // disable shop
        $shop = $ownershipRequest->shop;
        $shop->is_active = false;
        $shop->save();
        $shop->refresh();
        // draft products
        Product::where('shop_id', '=', $ownershipRequest->shop_id)->update(['status' => ProductStatus::Draft->value]);

        $message = [
            'message' => 'Shop transfer request #'.$ownershipRequest->transaction_identifier.' is on processing.',
        ];
        $this->notificationThrowingFunction($shop, $ownershipRequest, $message);
    }

    public function approvedOwnerShipTransferStatus(OwnershipTransfer $ownershipRequest): void
    {
        $shop = $ownershipRequest->shop;
        $shop->owner_id = $ownershipRequest->to;
        $shop->save();
        $shop->refresh();
        $message = [
            'message' => 'Congratulations! Shop transfer request #'.$ownershipRequest->transaction_identifier.' is approved.',
        ];
        $this->notificationThrowingFunction($shop, $ownershipRequest, $message);
    }

    public function rejectingOwnerShipTransferStatus(OwnershipTransfer $ownershipRequest): void
    {
        // disable shop
        $shop = $ownershipRequest->shop;
        $shop->is_active = false;
        $shop->save();
        // draft products
        Product::where('shop_id', '=', $ownershipRequest->shop_id)->update(['status' => ProductStatus::Draft->value]);
        $message = [
            'message' => 'Sorry! Shop transfer request #'.$ownershipRequest->transaction_identifier.' is rejected. For more details please contact with site admin.',
        ];
        $this->notificationThrowingFunction($shop, $ownershipRequest, $message);
    }

    public function notificationThrowingFunction(Shop $shop, OwnershipTransfer $ownershipRequest, array $message): void
    {
        $previousOwner = User::findOrFail($ownershipRequest->from);
        $newOwner = User::findOrFail($ownershipRequest->to);
        $users = [...$this->getAdminUsers(), $previousOwner, $newOwner];
        if ($users) {
            foreach ($users as $user) {
                Notification::route('mail', [
                    $user->email,
                ])->notify(new TransferredShopOwnershipStatus(
                    $shop,
                    $previousOwner,
                    $newOwner,
                    $message
                ));
            }
        }
    }
}
