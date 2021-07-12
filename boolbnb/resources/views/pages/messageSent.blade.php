@extends('layouts.main-layout')

@section('content')
    <main>
        <div class="alert-container">
            <div class="msg-alert">
                <h1>Messaggio correttamente inviato</h1>
                <a href="{{ route('showApartment', $apartment->id) }} ">Clicca qui per tornare all'appartamento</a>
            </div>
        </div>
    </main>
@endsection
