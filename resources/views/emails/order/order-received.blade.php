{{--$order collection is available here--}}

@component('mail::message')
@if($receiver == 'admin')
    # {{ __('notification::sms.order.orderCreated.admin.subject') }}

    {{ __('notification::sms.order.orderCreated.admin.message',['ORDER_TRACKING_NUMBER'=>$order->tracking_number,'customer_name'=>$customer]) }}
@else
    # {{ __('notification::sms.order.orderCreated.storeOwner.subject') }}

    {{ __('notification::sms.order.orderCreated.storeOwner.message',['ORDER_TRACKING_NUMBER'=>$order->tracking_number,'customer_name'=>$customer]) }}
@endif



@component('mail::button', ['url' => $url ])
    {{__('common.view-order')}}
@endcomponent

{{__('common.thanks')}},<br>
{{ config('app.name') }}
@endcomponent