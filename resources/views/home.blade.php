@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                {{-- <div class="card-header">{{ __('New Ticket') }}</div> --}}

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- <div class="toast">
                      <div class="toast-header">
                        Status
                      </div>
                      <div class="toast-body">
                          Ready for next ticket.
                      </div>
                    </div> --}}

                    {{-- OK here goes the image capture! --}}

                    <div class="row">

                      <div class="col-xs-5">

                        <div class="camera">
                          <video id="video">Video stream not available.</video>
                        </div>
                      </div>

                      <div class="col-xs-2">




                      </div>
                      <div class="col-xs-5" style="margin-left:auto; margin-right:0;">
                        <canvas id="canvas"  style="outline: 1px dashed grey;">
                          <div class="output">
                            <img id="photo" alt="The screen capture will appear in this box.">
                          </div>
                        </canvas>

                      </div>
                    </div>



<div class="row">
<div class="col-xs-12" style="margin-left:auto; margin-right:0;">
                    <button id="startbutton">Take photo</button>
</div>
</div>
                    <br>
                    <p>Use photo for:</p>
                    <br>



<div class="row">

<div class="col-xs-3 col text-center">
  <button id="frontbutton">Front</button>
</div>

<div class="col-xs-3 col text-center">
  <button id="rearbutton">Rear</button>
</div>

<div class="col-xs-3 col text-center">
  <button id="dashbutton">Dash</button>
</div>


<div class="col-xs- col text-center3">
  <button id="locationbutton">Location</button>
</div>
</div>
<br>





<div class="row">
  <div class="col-xs-5">

                    <canvas id="frontcanvas"  style="outline: 1px dashed grey;">
                    <div class="output">
                      <img id="fphoto" alt="The screen capture will appear in this box.">
                    </div>
                    </canvas>

</div>
                    <div class="col-xs-2">




                    </div>

  <div class="col-xs-5" style="margin-left:auto; margin-right:0;">


                    <canvas id="rearcanvas"  style="outline: 1px dashed grey;">
                    <div class="output">
                      <img id="rphoto" alt="The screen capture will appear in this box.">
                    </div>
                    </canvas>
</div>
</div>

<div class="row">
  <div class="col-xs-5">

                    <canvas id="dashcanvas"  style="outline: 1px dashed grey;">
                    <div class="output">
                      <img id="dphoto" alt="The screen capture will appear in this box.">
                    </div>
                    </canvas>
</div>
                    <div class="col-xs-2">




                    </div>

  <div class="col-xs-5" style="margin-left:auto; margin-right:0;">


                    <canvas id="locationcanvas"  style="outline: 1px dashed grey;">
                    <div class="output">
                      <img id="lphoto" alt="The screen capture will appear in this box.">
                    </div>
                    </canvas>

</div>
</div>


                    {{-- Put a simple form in here with reg_no field and all other not null hiddenMode --}}

                    <form action="{{ route('ticketissues.store') }}" method="post">
                      {{ csrf_field() }}
                      <input type="hidden" id="officer_id" name="officer_id" value="{{Auth::id()}}">
                      <input type="hidden" id="gps_lat" name="gps_lat">
                      <input type="hidden" id="gps_lon" name="gps_lon">
                      <input type="hidden" id="front_image" name="front_image">
                      <input type="hidden" id="rear_image" name="rear_image">
                      <input type="hidden" id="dash_image" name="dash_image">
                      <input type="hidden" id="location_image" name="location_image">

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

  var width = 320 / 2.2;    // We will scale the photo width to this
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

    frontbutton = document.getElementById('frontbutton');
    rearbutton = document.getElementById('rearbutton');
    dashbutton = document.getElementById('dashbutton');
    locationbutton = document.getElementById('locationbutton');

    frontcanvas = document.getElementById('frontcanvas');
    rearcanvas = document.getElementById('rearcanvas');
    dashcanvas = document.getElementById('dashcanvas');
    locationcanvas = document.getElementById('locationcanvas');

    fphoto = document.getElementById('fphoto');
    rphoto = document.getElementById('rphoto');
    dphoto = document.getElementById('dphoto');
    lphoto = document.getElementById('lphoto');

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

        frontcanvas.setAttribute('width', width);
        frontcanvas.setAttribute('height', height);

        rearcanvas.setAttribute('width', width);
        rearcanvas.setAttribute('height', height);

        dashcanvas.setAttribute('width', width);
        dashcanvas.setAttribute('height', height);

        locationcanvas.setAttribute('width', width);
        locationcanvas.setAttribute('height', height);
        streaming = true;
      }
    }, false);

    startbutton.addEventListener('click', function(ev){
      takepicture();
      ev.preventDefault();
    }, false);



    frontbutton.addEventListener('click', function(ev){
      usefront();
      ev.preventDefault();
    }, false);

    rearbutton.addEventListener('click', function(ev){
      userear();
      ev.preventDefault();
    }, false);

    dashbutton.addEventListener('click', function(ev){
      usedash();
      ev.preventDefault();
    }, false);

    locationbutton.addEventListener('click', function(ev){
      uselocation();
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

  function usefront() {
    var context = frontcanvas.getContext('2d');
    if (width && height) {
      frontcanvas.width = width;
      frontcanvas.height = height;
      context.drawImage(canvas, 0, 0, width, height);

      var data = frontcanvas.toDataURL('image/png');
      document.forms[1].elements["front_image"].value = frontcanvas.toDataURL('image/jpg');
      fphoto.setAttribute('src', data);
    } else {
      clearphoto();
    }
  }

  function userear() {
    var context = rearcanvas.getContext('2d');
    if (width && height) {
      rearcanvas.width = width;
      rearcanvas.height = height;
      context.drawImage(canvas, 0, 0, width, height);

      var data = rearcanvas.toDataURL('image/png');
      document.forms[1].elements["rear_image"].value = rearcanvas.toDataURL('image/jpg');
      rphoto.setAttribute('src', data);
    } else {
      clearphoto();
    }
  }

  function usedash() {
    var context = dashcanvas.getContext('2d');
    if (width && height) {
      dashcanvas.width = width;
      dashcanvas.height = height;
      context.drawImage(canvas, 0, 0, width, height);

      var data = dashcanvas.toDataURL('image/png');
      document.forms[1].elements["dash_image"].value = dashcanvas.toDataURL('image/jpg');
      dphoto.setAttribute('src', data);
    } else {
      clearphoto();
    }
  }

  function uselocation() {
    var context = locationcanvas.getContext('2d');
    if (width && height) {
      locationcanvas.width = width;
      locationcanvas.height = height;
      context.drawImage(canvas, 0, 0, width, height);

      var data = locationcanvas.toDataURL('image/png');
      document.forms[1].elements["location_image"].value = locationcanvas.toDataURL('image/jpg');
      rphoto.setAttribute('src', data);
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
