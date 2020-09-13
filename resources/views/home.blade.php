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


                                          <div id='results'>
                     <div>
                       <video autoplay></video>
                       <button id='getUserMediaButton'>Get User Media</button>
                     </div>
                     <div>
                       <canvas id='grabFrameCanvas'></canvas>
                       <button id='grabFrameButton' disabled>Grab Frame</button>
                     </div>
                     <div>
                       <canvas id='takePhotoCanvas'></canvas>
                       <button id='takePhotoButton' disabled>Take Photo</button>
                     </div>
                    </div>

                    {{-- Put a simple form in here with reg_no field and all other not null hiddenMode --}}


                    <form action="{{ route('ticketissues.store') }}" method="post">
                      {{ csrf_field() }}
                      <input type="hidden" id="officer_id" name="officer_id" value="{{Auth::id()}}">
                      <input type="hidden" id="gps_lat" name="gps_lat">
                      <input type="hidden" id="gps_lon" name="gps_lon">

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

function success(pos) {
  var id;
  var crd = pos.coords;
  document.forms[1].elements["gps_lat"].value = crd.latitude
  document.forms[1].elements["gps_lon"].value = crd.longitude
  navigator.geolocation.clearWatch(id);
}

getloc = navigator.geolocation.watchPosition(success);

var imageCapture;

function onGetUserMediaButtonClick() {
  navigator.mediaDevices.getUserMedia({video: true})
  .then(mediaStream => {
    document.querySelector('video').srcObject = mediaStream;

    const track = mediaStream.getVideoTracks()[0];
    imageCapture = new ImageCapture(track);
  })
  .catch(error => console.log(error));
}

function onGrabFrameButtonClick() {
  imageCapture.grabFrame()
  .then(imageBitmap => {
    const canvas = document.querySelector('#grabFrameCanvas');
    drawCanvas(canvas, imageBitmap);
  })
  .catch(error => console.log(error));
}


function onTakePhotoButtonClick() {
  imageCapture.takePhoto()
  .then(blob => createImageBitmap(blob))
  .then(imageBitmap => {
    const canvas = document.querySelector('#takePhotoCanvas');
    drawCanvas(canvas, imageBitmap);
  })
  .catch(error => console.log(error));
}

/* Utils */

function drawCanvas(canvas, img) {
  canvas.width = getComputedStyle(canvas).width.split('px')[0];
  canvas.height = getComputedStyle(canvas).height.split('px')[0];
  let ratio  = Math.min(canvas.width / img.width, canvas.height / img.height);
  let x = (canvas.width - img.width * ratio) / 2;
  let y = (canvas.height - img.height * ratio) / 2;
  canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
  canvas.getContext('2d').drawImage(img, 0, 0, img.width, img.height,
      x, y, img.width * ratio, img.height * ratio);
}

document.querySelector('video').addEventListener('play', function() {
  document.querySelector('#grabFrameButton').disabled = false;
  document.querySelector('#takePhotoButton').disabled = false;
});

</script>



@endsection
