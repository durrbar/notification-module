{{--$order collection is available here--}}

@component('mail::message')
# {{ __('notification::sms.order.orderCreated.customer.subject') }}

{{ __('notification::sms.order.orderCreated.customer.message',['ORDER_TRACKING_NUMBER'=>$order->tracking_number]) }}


@component('mail::button', ['url' => $url ])
    {{__('common.view-order')}}
@endcomponent

{{__('common.thanks')}},<br>
{{ config('app.name') }}
@endcomponent
