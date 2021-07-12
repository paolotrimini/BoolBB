@extends('layouts.main-layout')

@section('content')

    {{-- JT --}}
    <div class="container-content">
        <div class="margin-content">
            <div class="text-content">
                <h1>Esplora BoolBnB</h1>
            </div>
        </div>
    </div>

    <main>
        {{-- PREMIUM APARTMENTS --}}

        <div class="container-ap">


            <div class="row">

                <h1>IN EVIDENZA</h1>

            </div>

            <div class="row">


                @foreach ($apartments as $apartment)

                    <div class="mini-container @if ($apartment->visible == 1) invisible @endif">

                        <img class="ap-img" src="{{ asset('/storage/images/' . $apartment->cover_image) }}"
                            alt="immagine principale appartamento {{ $apartment->title }}">

                        <a href="{{ route('showApartment', $apartment->id) }}" class="prem-apart">

                            <div class="white-buble">

                                <h2>{{ $apartment->title }}</h2>
    
                                {{-- <div>
    
                                    <span>Numero di stanze:</span>
                                    <span>{{ $apartment->rooms_number }}</span>
    
                                </div> --}}
    
                                <div class="details">

                                    <i class="fas fa-door-open"></i>
                                    <span>{{ $apartment->rooms_number }}</span>
    
                                    <i class="fas fa-bed"></i>
                                    <span>{{ $apartment->beds_number }}</span>

                                    <i class="fas fa-toilet"></i>
                                    <span>{{ $apartment->bathrooms_number }}</span>

                                </div>
                            </div>

                        </a>

                    </div>

                @endforeach



                {{-- WORK WITH US --}}
            </div>

            <div class="row" id="adv-block">

                <h1>SCOPRI COME GUADAGNARE CON NOI</h1>

            </div>

            <div id="adv-body">
                <div>
                    <h2>
                        Metti in vendita il tuo immobile
                    </h2>

                    <p>
                        Vendi a migliaia di persone in tutto il mondo
                        e mettiti in mostra graie alle fantastiche promozioni
                        a tua completa disposizione.
                    </p>
                </div>

                <a href="{{ route('createApartment') }}">
                    <button class="big-button">
                        CARICA IMMOBILE
                    </button>
                </a>
            </div>


        </div>
    </main>

@endsection
