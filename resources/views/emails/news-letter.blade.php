@component('mail::message')

@component('mail::table')
|               |                              |
| ------------- | ---------------------------- |
| Name          | {{$body['name']}}            |
| Phone         | {{$body['phone']}}           |
| Doctor Name   | {{$body['doctor_name']}}     |
| Date          | {{$body['date']}}            |
| ------------- | ---------------------------- |
|               |                              |
@endcomponent

@endcomponent
