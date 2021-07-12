@extends('layouts.main-layout')

@section('content')
    <div class="edit-container">
        <div class="container">
            <div class="row text-center">
                <div class="col-12">
                    <h1>Modifica appartamento:</h1>
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('updateApartment', $apartment->id) }}" enctype="multipart/form-data">

            @csrf
            @method('POST')

            <input type="hidden" name="user_id" value="{{ $user->id }}">

            {{-- title --}}
            <div class="form-input">
                <label for="title">Title</label>
                <div>
                    <input id="title" type="text" name="title" value="{{ $apartment->title }}">
                </div>
            </div>
            {{-- description --}}
            <div class="form-input">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="5" value="{{ $apartment->description }}">{{ $apartment->description }}</textarea>
            </div>
            {{-- rooms number --}}
            <div class="row">
                <div class="form-input">
                    <label for="rooms_number">Rooms number</label>
                    <div>
                        <input id="rooms_number" type="number" oninput="validity.valid||(value='')" min="1"
                            name="rooms_number" value="{{ $apartment->rooms_number }}">
                    </div>
                </div>
                {{-- beds number --}}
                <div class="form-input">
                    <label for="beds_number">Beds number</label>
                    <div>
                        <input id="beds_number" type="number" oninput="validity.valid||(value='')" min="1"
                            name="beds_number" value="{{ $apartment->beds_number }}">
                    </div>
                </div>
            </div>
            {{-- bathrooms number --}}
            <div class="row">
                <div class="form-input">
                    <label for="bathrooms_number">Bathrooms number</label>
                    <div>
                        <input id="bathrooms_number" type="number" oninput="validity.valid||(value='')" min="1"
                            name="bathrooms_number" value="{{ $apartment->bathrooms_number }}">
                    </div>
                </div>
                {{-- area --}}
                <div class="form-input">
                    <label for="area">Area</label>
                    <div>
                        <input id="area" type="number" oninput="validity.valid||(value='')" min="1" name="area"
                            value="{{ $apartment->area }}">
                    </div>
                </div>
            </div>
            {{-- address --}}
            <div class="row">
                <div class="form-input">
                    <label for="address">Address</label>
                    <div>
                        <input id="address" type="text" name="address" value="{{ $apartment->address }}">
                    </div>
                </div>
                {{-- city --}}
                <div class="form-input">
                    <label for="city">City</label>
                    <div>
                        <input id="city" type="text" name="city" value="{{ $apartment->city }}">
                    </div>
                </div>
            </div>
            {{-- country --}}
            <div class="row">
                <div class="form-input">
                    <label for="country">Country</label>
                    <div>
                        <input id="country" type="text" name="country" value="{{ $apartment->country }}">
                    </div>
                </div>
                {{-- postal_code --}}
                <div class="form-input">
                    <label for="postal_code">Postal Code</label>
                    <div>
                        <input id="postal_code" type="text" name="postal_code" value="{{ $apartment->postal_code }}">
                    </div>
                </div>
            </div>
            {{-- cover image --}}
            <div class="form-input cover-img">
                <h2>Immagine di copertina</h2>
                <div>
                    <input id="cover_image" type="file" name="cover_image">
                </div>
            </div>
            {{-- vidibility --}}
            {{-- <div class="vis-if">

                <div>
                    <label for="visible">Rendi invisibile</label>
                    <input type="radio" id="visible" name="visible" value="1" @if ($apartment->visible == 1) checked @endif>
                </div>

                <div>
                    <label for="visible">Rendi visibile</label>
                    <input type="radio" id="visible" name="visible" value="0" @if ($apartment->visible == 0) checked @endif>
                </div>

            </div> --}}
            {{-- services --}}
            <h2 class="servizih2">Servizi</h2>
            <div class="services">

                @foreach ($services as $service)

                    <div class="service">
                        <input type="checkbox" id="service_id[]" name="service_id[]" value="{{ $service->id }}"
                        
                        @foreach ($apartment->services as $apartmentService) 
                            @if ($apartmentService->id==$service->id)
                                checked
                            @endif
                        @endforeach
                        >
                        <p for="service_id[]">{{ $service->name }}</p>
                    </div>

                @endforeach
            </div>

            {{-- BUTTON --}}
            <div>
                <button type="submit">
                    UPDATE
                </button>
            </div>
        </form>
    </div>
@endsection
