@extends('layouts.main-layout')

@section('content')
    <div class="create-container">
        <div class="container">
            <div class="row text-center">
                <div class="col-12">
                    <h1>Nuovo appartamento:</h1>
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('storeApartment') }}" enctype="multipart/form-data">

            @csrf
            @method('POST')

            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

            {{-- title --}}
            <div class="form-input">
                <label for="title">Titolo</label>
                <input id="title" type="text" name="title">
            </div>
            {{-- description --}}
            <div class="form-input">
                <label for="description">Descrizione</label>
                {{-- <input id="description" type="text" name="description"> --}}
                <textarea id="description" name="description" rows="5"></textarea>
            </div>

            <div class="row">
                {{-- rooms --}}
                <div class="form-input">
                    <label for="rooms_number">Stanze</label>
                    <input id="rooms_number" type="number" name="rooms_number" min="1" oninput="validity.valid||(value='')">
                </div>
                {{-- beds --}}
                <div class="form-input">
                    <label for="beds_number">Letti</label>
                    <input id="beds_number" type="number" name="beds_number" min="1" oninput="validity.valid||(value='')">
                </div>
            </div>

            <div class="row">
                {{-- bathrooms --}}
                <div class="form-input">
                    <label for="bathrooms_number">Bagni</label>
                    <input id="bathrooms_number" type="number" name="bathrooms_number" min="1" oninput="validity.valid||(value='')">
                </div>
                {{-- area --}}
                <div class="form-input">
                    <label for="area">m²</label>
                    <input id="area" type="number" name="area" min="1" oninput="validity.valid||(value='')">
                </div>
            </div>

            <div class="row">
                {{-- address --}}
                <div class="form-input">
                    <label for="address">Indirizzo</label>
                    <input id="address" type="text" name="address">
                </div>
                {{-- city --}}
                <div class="form-input">
                    <label for="city">Città</label>
                    <input id="city" type="text" name="city">
                </div>
            </div>

            <div class="row">
                {{-- country --}}
                <div class="form-input">
                    <label for="country">Nazione</label>
                    <input id="country" type="text" name="country">
                </div>
                {{-- postal_code --}}
                <div class="form-input">
                    <label for="postal_code">CAP</label>
                    <input id="postal_code" type="text" name="postal_code">
                </div>
            </div>

            {{-- cover image --}}
            <div class="form-input cover-img">
                <label for="cover_image">Immagine di copertina</label>
                <input id="cover_image" type="file" name="cover_image">
            </div>
            {{-- services --}}
            <h2 class="servizih2">Servizi</h2>
            <div class="services">
                @foreach ($services as $service)
                    <div class="service">
                        <input class="toggle" type="checkbox" id="service_id[]" name="service_id[]" value="{{ $service->id }}">
                        <p for="service_id">{{ $service->name }}</p>
                    </div>
                @endforeach
            </div>

            {{-- BUTTON --}}
            <button type="submit">
                CREA
            </button>

        </form>
    </div>
@endsection
