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

                    <div class="toast">
                      <div class="toast-header">
                        Status
                      </div>
                      <div class="toast-body">
                          Ready for next ticket.
                      </div>
                    </div>

                    {{-- OK here goes the image capture! --}}

                    <div class="camera">
                      <video id="video">Video stream not available.</video>
                      <button id="startbutton">Take photo</button>
                    </div>

                    <canvas id="canvas">
                    <div class="output">
                      <img id="photo" alt="The screen capture will appear in this box.">
                    </div>
                    </canvas>

                    {{-- Put a simple form in here with reg_no field and all other not null hiddenMode --}}

                    <form action="{{ route('ticketissues.store') }}" method="post">
                      {{ csrf_field() }}
                      <input type="hidden" id="officer_id" name="officer_id" value="{{Auth::id()}}">
                      <input type="hidden" id="gps_lat" name="gps_lat">
                      <input type="hidden" id="gps_lon" name="gps_lon">
                      <input type="hidden" id="front_image" name="front_image">

                      <div class="form-row">
                        <div class="form-group col-md-12">
                          <label for="regNo">Registration Number</label>
                          <input type="string" name="reg_no" class="form-control" id="regNo" required /></input>
                        </div>
                      </div>


                      <input type="submit" value="Save" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="application/javascript">


$(document).ready(function(){
  $('.toast').toast({delay: 2000});
  $('.toast').toast('show');
});

function success(pos) {
  var id;
  var crd = pos.coords;
  document.forms[1].elements["gps_lat"].value = crd.latitude
  document.forms[1].elements["gps_lon"].value = crd.longitude
  navigator.geolocation.clearWatch(id);
}

getloc = navigator.geolocation.watchPosition(success);


// Video scripts

(function() {
  // The width and height of the captured photo. We will set the
  // width to the value defined here, but the height will be
  // calculated based on the aspect ratio of the input stream.

  var width = 320;    // We will scale the photo width to this
  var height = 0;     // This will be computed based on the input stream

  // |streaming| indicates whether or not we're currently streaming
  // video from the camera. Obviously, we start at false.

  var streaming = false;

  // The various HTML elements we need to configure or control. These
  // will be set by the startup() function.

  var video = null;
  var canvas = null;
  var photo = null;
  var startbutton = null;

  function startup() {
    video = document.getElementById('video');
    canvas = document.getElementById('canvas');
    photo = document.getElementById('photo');
    startbutton = document.getElementById('startbutton');

    navigator.mediaDevices.getUserMedia({video: true, audio: false})
    .then(function(stream) {
      video.srcObject = stream;
      video.play();
    })
    .catch(function(err) {
      console.log("An error occurred: " + err);
    });

    video.addEventListener('canplay', function(ev){
      if (!streaming) {
        height = video.videoHeight / (video.videoWidth/width);

        // Firefox currently has a bug where the height can't be read from
        // the video, so we will make assumptions if this happens.

        if (isNaN(height)) {
          height = width / (4/3);
        }

        video.setAttribute('width', width);
        video.setAttribute('height', height);
        canvas.setAttribute('width', width);
        canvas.setAttribute('height', height);
        streaming = true;
      }
    }, false);

    startbutton.addEventListener('click', function(ev){
      takepicture();
      ev.preventDefault();
    }, false);

    clearphoto();
  }

  // Fill the photo with an indication that none has been
  // captured.

  function clearphoto() {
    var context = canvas.getContext('2d');
    context.fillStyle = "#AAA";
    context.fillRect(0, 0, canvas.width, canvas.height);

    var data = canvas.toDataURL('image/png');
    photo.setAttribute('src', data);

    document.forms[1].elements["front_image"].value = data;
  }

  // Capture a photo by fetching the current contents of the video
  // and drawing it into a canvas, then converting that to a PNG
  // format data URL. By drawing it on an offscreen canvas and then
  // drawing that to the screen, we can change its size and/or apply
  // other changes before drawing it.

  function takepicture() {
    var context = canvas.getContext('2d');
    if (width && height) {
      canvas.width = width;
      canvas.height = height;
      context.drawImage(video, 0, 0, width, height);

      var data = canvas.toDataURL('image/png');
      photo.setAttribute('src', data);
    } else {
      clearphoto();
    }
  }

  // Set up our event listener to run the startup process
  // once loading is complete.
  window.addEventListener('load', startup, false);
})();

</script>



@endsection
