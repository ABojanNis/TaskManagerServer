@component('mail::message')
<h1>You have been assigned a task</h1>

<h2>{{ $task->title }}</h2>
<p>{{ $task->task }}</p>

Click on button to login in application. Then you can see your task and mark it as completed or failed.
@component('mail::button', ['url' => env('FRONT_APP_URL')])
Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
