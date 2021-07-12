@extends('layouts.main-layout')

@section('content')

    <div class="container-flat">

        <div class="flat-description margins">
            <h1> {{ $apartment->title }} </h1>
            <h2> {{ $apartment->address }} | {{ $apartment->city }}</h2>
        </div>

        <div class="flat-block margins">

            <div class="flat-img">
                {{-- <span>Qui dentro ci va l'immagine principale</span> --}}
                <img src="{{ asset('/storage/images/' . $apartment->cover_image) }}" alt="">
            </div>

            <div class="container-text">
                <div class="flat-text" id="flat-text-id">
                    <h2>Descrizione</h2>
                    <span> {{ $apartment->description }} </span>

                    <hr>

                    <ul>
                        <li>
                            <span class="rooms-details">Stanze:</span> <span
                                class="rooms-details-number">{{ $apartment->rooms_number }}</span>
                        </li>
                        <li>
                            <span class="rooms-details">Letti:</span> <span
                                class="rooms-details-number">{{ $apartment->beds_number }}</span>

                        </li>
                        <li>
                            <span class="rooms-details">Bagni:</span> <span
                                class="rooms-details-number">{{ $apartment->bathrooms_number }}</span>

                        </li>
                        <li>
                            <span class="rooms-details">mq:</span> <span
                                class="rooms-details-number">{{ $apartment->area }}</span>

                        </li>
                    </ul>

                    <hr>

                    <h2 class="servizi">Servizi:</h2>
                    <ul class="flex-order">

                        <li>
                            @foreach ($apartment->services as $service)

                                {{ $service->name }}
                                <i class=" {{ $service->icon }} "></i>
                                @if (!$loop->last)
                                    |
                                @endif

                            @endforeach
                        </li>

                    </ul>

                </div>
            </div>
        </div>

        <div class="flat-form">
            <h3>Desideri maggiori informazioni? <br> Contatta {{ $apartment->title }}</h3>

            <form method="POST" action="{{ route('storeMessage', $apartment->id) }}">

                @csrf
                @method('POST')

                <div class="form-group row">
                    <input id="email" type="text" class="form-control" name="email" value="" required
                        placeholder="Inserisci qui la tua email">
                </div>

                <div class="form-group row">
                    <textarea id="text" class="form-control" name="text" value="" required
                        placeholder="Inserisci qui la tua richiesta..."></textarea>
                </div>

                <input type="hidden" name="apartment_id" value="{{ $apartment->id }}">

                <button type="submit">Invia</button>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
        </div>

        @php
            $ip = $_SERVER['REMOTE_ADDR'];
        @endphp

        <div id="sendIp"></div>

    </div>

    <script>
        new Vue({

            el: '#sendIp',

            mounted() {
                axios.post('/api/getViews/' + '{{ $ip }}' + '/' + '{{ $apartment->id }}')
            }
        });
    </script>

@endsection
