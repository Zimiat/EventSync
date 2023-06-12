@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="card mb-3">
      <div class="card-body">
        <h1 class="card-title">Maak een event aan</h1>

        <form method="POST" action="{{ route('calendar.events') }}">
          @csrf
          <div class="form-group mb-3">
            <div class="card mb-3">
              <div class="card-header">Geef het event een naam:</div>
                <div class="card-body">
                  <input type="text" name="event_name" id="event_name" class="form-control" required placeholder="Typ hier de naam van het event">
                </div>
            </div>
          </div>

          <!-- Calendar Component -->
          <div class="form-group">
            <div class="card mb-3">
              <div class="card-header">{{ __('Kies een datum:') }}</div>
              <div class="card-body">
                <div class="calendar-container" id="calendarContainer">
                  @include('components.calendar')
                </div>
              </div>
            </div>
          </div>

          <!-- Map Component -->
          <div class="form-group">
            <div class="card mb-3">
              <div class="card-header">{{ __('Kies een locatie:') }}</div>
              <div class="card-body">
                <form>
                  <div class="row">
                    <div class="col-md-4 mb-3">
                      <label for="streetInput">Straat:</label>
                      <input id="streetInput" class="form-control" type="text" placeholder="Geef een straatnaam">
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="zipInput">Postcode:</label>
                      <input id="zipInput" class="form-control" type="text" placeholder="Geef een postcode">
                    </div>
                    <div class="col-md-4 mb-3">
                      <label for="cityInput">Stad:</label>
                      <input id="cityInput" class="form-control" type="text" placeholder="Geef een stad">
                    </div>
                  </div>
                  <div class="form-group">
                    <button class="btn btn-primary" onclick="geocodeAddress()">Zoek</button>
                  </div>
                </form>
                @include('components.mapComp')
              </div>
            </div>
          </div>

          <div id="friends-container" class="friends-container">
            <div class="card mb-3">
              <div class="card-header">{{ __('Nodig vrienden uit:') }}</div>
              <div class="card-body">
                <ul>
                  <li>friend 1</li>
                  <li>friend 2</li>
                  <li>friend 3</li>
                </ul>
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-primary" onclick="test()">Create Event</button>
        </form>
      </div>
    </div>
  </div>
  <!-- Add ol.css manually -->
  <link rel="stylesheet" href="{{ asset('css/ol.css') }}">
@endsection

<script>
  var lat;
  var long;
  var event_date;

  // Event listener: capture emitted MAP event
  window.addEventListener('coordinates-updated', function (event) {
    lat = event.detail.latitude;
    long = event.detail.longitude;
    console.log(lat, long);
  });

  // Event listener: capture emitted CALENDAR event
  window.addEventListener('date-updated', function (event) {
    event_date = event.detail.date;
    console.log(event_date);
  });

  function test() {
    console.log("Form input:");

    // Retrieve input values
    var eventName = document.getElementById('event_name').value;
    var street = document.getElementById('streetInput').value;
    var zipCode = document.getElementById('zipInput').value;
    var city = document.getElementById('cityInput').value;

    // Log the input values
    console.log("$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$");
    console.log("event_name: " + eventName);
    console.log("--------------------------------");
    console.log("event_date:", event_date);
    console.log("event_lat + event_long:", lat, " + ", long);
    console.log("event address details:");
    console.log("street: " + street);
    console.log("zip code: " + zipCode);
    console.log("city: " + city);
    console.log("--------------------------------");

    // Create a new event object
    var newEvent = {
      event_name: eventName,
      event_date: event_date,
      lat: lat,
      long: long
    };

    // Make an AJAX request to save the new event
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '{{ route('calendar.events') }}');
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}'); // Include CSRF token
    xhr.onload = function () {
      if (xhr.status === 200) {
        if (xhr.responseText) {
          var response = JSON.parse(xhr.responseText);
          console.log("New event created:", response);
          // Reset the form or perform any other desired actions
        } else {
          console.log("Empty response");
          // Handle the empty response appropriately
        }
      } else {
        console.log("Error creating new event:", xhr.responseText);
        // Handle the error appropriately
      }
    };
    xhr.send(JSON.stringify(newEvent));
  }
</script>