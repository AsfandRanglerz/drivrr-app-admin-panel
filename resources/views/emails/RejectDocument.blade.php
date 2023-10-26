@component('mail::message')
# Introduction

The body of your message.

<h4>{{$reason}}</h4>
Therefore, Your document has been rejected.
Thanks,<br>
{{ config('app.name') }}
@endcomponent
