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
{{-- Camera script --}}

<script  type="application/javascript">
  const player = document.getElementById('player');
  const canvas = document.getElementById('canvas');
  const context = canvas.getContext('2d');
  const captureButton = document.getElementById('capture');

  const constraints = {
    video: true,
  };

  captureButton.addEventListener('click', () => {
    // Draw the video frame to the canvas.
    context.drawImage(player, 0, 0, canvas.width, canvas.height);
    var dataURL = canvas.toDataURL(); //this var is a blob!!!!
  });

  // Attach the video stream to the video element and autoplay.
  navigator.mediaDevices.getUserMedia(constraints)
    .then((stream) => {
      player.srcObject = stream;
    });
</script>


{{-- GPS script --}}
    <script  type="application/javascript">
    var x = document.getElementById("gps");

    function getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
      } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
      }
    }

    function showPosition(position) {
      x.innerHTML = "Latitude: " + position.coords.latitude +
      "<br>Longitude: " + position.coords.longitude;
    }
    </script>


@endsection
