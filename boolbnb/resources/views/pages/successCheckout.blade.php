@extends('layouts.main-layout')

@section('content')

    <main>

        <div class="title">
            <h1>GRAZIE PER L'ACQUISTO</h1>
        </div>

        <div class="card-container">

            <div class="card">

                <h1 class="elm">{{ $apartment -> title }}</h1>
                <h3 class="elm">Pacchetto: {{ $sponsorship -> duration }}h - {{ $sponsorship -> price }}€</h3>
                <p class="elm">Data inizio: {{ $newStartDate }} | Data fine: {{ $newEndDate }}</p>
            
                <a class="dashButtonCheck" href="{{ route('dashboard', $apartment -> user_id) }}">Dashboard</a>

            </div>

        </div>

    </main>


@endsection
