<div>
    <h3>Activity "{{$activityName}}" on project "{{$projectName}}" has surpassed {{$threshold}}% of the allocated hours.</h3>
    <p>Hours booked: {{$hours}}h {{$minutes}}m out of {{$hoursBudget}} ({{round($hoursBooked / $hoursBudget * 100, 2)}}%)</p>
</div>
