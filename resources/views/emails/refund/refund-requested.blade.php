@component('mail::message')

@if($receiver == 'admin')
# {{ __('notification::sms.order.refundRequested.admin.subject') }}

{{ __('notification::sms.order.refundRequested.admin.message',['ORDER_TRACKING_NUMBER'=>$order->tracking_number]) }}

@component('mail::button', ['url' => $url ])
{{__('common.view-order')}}
@endcomponent
@else
# {{ __('notification::sms.order.refundRequested.customer.subject') }}

{{ __('notification::sms.order.refundRequested.customer.message',['ORDER_TRACKING_NUMBER'=>$order->tracking_number]) }}

@component('mail::button', ['url' => $url ])
{{__('common.view-order')}}
@endcomponent
@endif



{{__('common.thanks')}},<br>
{{ config('app.name') }}
@endcomponent
