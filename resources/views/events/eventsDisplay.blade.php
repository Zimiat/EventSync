@extends('layouts.app')

@section('content')
<div class="container">
  <h2>Your Events</h2>
  @if ($ownedEvents->isEmpty())
    <p><i><b>Currently you don't have any organized events. Invite some friends, Sync up and create memories!</b></i></p>
  @else
    @foreach ($ownedEvents as $ownedevent)
    <div class="card mb-3">
      <div class="card-header">
        {{"Event Name: " . $ownedevent->name}}
      </div>
      <div class="container text-center">
        <div class="row justify-content-start">
          <div class="col-4">
            {{"Location: " . $ownedevent->address . ', ' . $ownedevent->zipcode . ' ' . $ownedevent->city}}
          </div>
          <div class="col-4">
            {{"Date: " . $ownedevent->date}}
          </div>
          <div class="col-4">

            <form method="POST" action="{{ route('Event', ['id' => $ownedevent->id]) }}">
              @csrf
              @method('GET')
              <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                  <button type="submit" class="btn btn-success" href="{{route('Event', ['id' => $ownedevent->id])}}">info</button>
                </div>
              </div>
            </form>
            
            <form method="POST" action="{{ route('event.delete', ['id' => $ownedevent->id]) }}">
              @csrf
              @method('DELETE')
              <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                  <button type="submit" class="btn btn-danger" onclick="confirmDelete(event)">Verwijder event</button>
                </div>
              </div>
            </form>
            
          </div>
        </div>
      </div>
    </div>
    @endforeach
  @endif

  <div class="d-flex justify-content-center mt-4">
    {!! $ownedEvents->links('pagination::bootstrap-5') !!}
  </div>
</div>

<hr class="my-5">

<div class="container">
  @if ($events->isEmpty())
  <h2>Invited Events</h2>
    <p><i><b>Currently you aren't part of any events. Don't wait on friends, create your own and invite them!</b></i></p>
  @else
    <h2>Invited Events</h2>
    @foreach ($events as $event)
    <div class="card">
      <div class="card-header">
        {{"Event Name: " . $event->name}}
      </div>
      <div class="container text-center">
        <div class="row justify-content-start">
          <div class="col-4">
            {{"Location: " . $event->address . ', ' . $event->zipcode . ' ' . $event->city}}
          </div>
          <div class="col-4">
            {{"Date: " . $event->date}}
          </div>
          <div class="col-4">

            <form method="POST" action="{{ route('Event', ['id' => $event->id]) }}">
              @csrf
              @method('GET')
              <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                  <button type="submit" class="btn btn-success" href="{{route('Event', ['id' => $event->id])}}">info</button>
                </div>
              </div>
            </form>
            
            <form method="POST" action="{{ route('event.pivot.delete', ['id' => $event->id]) }}">
              @csrf
              @method('DELETE')
              <div class="form-group row mb-0">
                <div class="col-md-8 offset-md-4">
                  <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your event?')">Verwijder event</button>
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
    @endforeach
  @endif

  <div class="d-flex justify-content-center mt-4">
    {!! $events->links('pagination::bootstrap-5') !!}
  </div>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  function confirmDelete(event) {
    event.preventDefault(); // Prevent the default form submission
    
    if (confirm('Are you sure you want to delete your event?')) {
      // Make AJAX request to send cancellation
      $.ajax({
        url: "{{ route('send-cancellations') }}",
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
          type: 'create'
        },
        type: "POST",
        success: function (data) {
          // Handle success response
          console.log("Cancellation sent:", data);

          // Show toastr popup
          toastr.success('Cancellation sent successfully');

          // Redirect to home page after delay
          setTimeout(function () {
            // window.location.href = "{{ route('home') }}";
          }, 2000); // Delay in milliseconds
        },
        error: function (xhr, status, error) {
          // Handle error response
          console.log("Error sending Cancellation:", error);

          // Show toastr popup for error
          toastr.error('Error sending Cancellation');
        }
      });
    }
  }
</script>