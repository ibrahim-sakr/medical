@component('mail::message')

@component('mail::table')
|                       |                                  |
| --------------------- | -------------------------------- |
| Name                  | {{$body['name']}}                |
| Phone                 | {{$body['phone']}}               |
| Date Of Surgery       | {{$body['datOfSurgery']}}        |
| Doctor Name           | {{$body['doctorName']}}          |
| weight Before Surgery | {{$body['weightBeforeSurgery']}} |
| Height Before Surgery | {{$body['heightBeforeSurgery']}} |
| Current Weight        | {{$body['currentWeight']}}       |
| Current Height        | {{$body['currentHeight']}}       |
| --------------------- | -------------------------------- |
|                       |                                  |
@endcomponent

@endcomponent
