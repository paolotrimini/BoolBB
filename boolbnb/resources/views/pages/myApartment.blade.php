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

        <div class="my-apartment">

            <div class="stats" id="stats">
                <div class="stats-container">
                    <h1>Statistiche</h1>

                    <select name="" id="" v-on:change="updateChart" v-model="selectedYear">
                        <option v-for="year in totalYears" v-bind:value="year">
                            @{{ year }}

                        </option>
                    </select>

                    <div class="charts" style="position: relative; height:500px; width:60vw">
                        <canvas id="statisticsChart" width="800px height:250px"></canvas>
                        <canvas id="messagesChart" width="800px height:250px"></canvas>
                    </div>
                </div>
            </div>

            <div class="messages">

                <h1>Messaggi ricevuti:</h1>

                <div class="received-msgs">

                    @if (count($messages) > 0)
                        <div class="msg">

                            @foreach ($messages as $message)
                                <div class="msg-row">

                                    <p class="new-msg-from">Messaggio da: {{ $message->email }} </p>
                                    <p> {{ $message->text }} </p>

                                </div>
                            @endforeach

                        </div>
                    @else
                        <p class="no-msg-received">Non hai messaggi da leggere</p>
                    @endif

                </div>
            </div>
        </div>

    </div>

    <script>
        new Vue({
            el: '#stats',
            data: {

                statisticsChart: [],
                messagesChart: [],

                totalYears: [
                    '2019',
                    '2020',
                    '2021',
                ],

                selectedYear: new Date().getFullYear(),
            },
            methods: {

                getChartData: function() {

                    const year = this.selectedYear.toString();

                    axios.post('/api/getChartData/' + '{{ $apartment->id }}' + '/' + year)
                        .then(res => {


                            this.createChart(res.data['statistics'], 'statisticsChart', 'Statistiche', 0);
                            this.createChart(res.data['messages'], 'messagesChart', 'Messaggi', 1);
                        });
                },

                updateChart: function() {

                    const year = this.selectedYear.toString();

                    axios.post('/api/getChartData/' + '{{ $apartment->id }}' + '/' + year)
                        .then(res => {

                            this.statisticsChart.data.datasets[0].data = res.data['statistics'];
                            this.statisticsChart.update();

                            this.messagesChart.data.datasets[0].data = res.data['messages'];
                            this.messagesChart.update();
                        });
                },

                createChart: function(chartData, divId, chartLabel, param) {

                    let ctx = document.getElementById(divId).getContext('2d');
                    let myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno',
                                'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'
                            ],
                            datasets: [{
                                label: chartLabel,
                                data: chartData,
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(255, 206, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(255, 159, 64, 0.2)'
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {

                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    suggestedMin: 0,
                                    suggestedMax: 15,
                                }
                            }
                        }
                    });


                    if (param == 0) {

                        this.statisticsChart = myChart;
                    } else {

                        this.messagesChart = myChart;
                    }
                },

            },

            mounted() {

                this.getChartData();
            }
        })
    </script>
@endsection
