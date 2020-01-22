@extends('layouts.app')

@section('content')
@foreach ($tasks as $task)


 <div id="post" class="cover box{{$loop->iteration}} posts">

     <div class="content">

         <h2>{{$task->title}}</h2>

         <p>{{$task->description}}</p>

     </div>

 </div>

@endforeach
@endsection
