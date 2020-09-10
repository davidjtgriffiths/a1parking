@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('New Ticket') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <p>Click the button to get your coordinates.</p>

                    <button onclick="getLocation()">Try It</button>

                    <p id="gps"></p>



                    {{ __('Front Registration Plate') }}
<br>
                    <video id="player" controls autoplay></video>
                    <br>
<button id="capture">Capture</button>
<canvas id="canvas" width=320 height=240></canvas>

                </div>
            </div>
        </div>
    </div>
</div>






@endsection
