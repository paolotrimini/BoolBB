@extends('layouts.main-layout')

@section('content')
    <div class="box-container">
        <div id="search">
            <div class="box-app-map">
                {{-- appartamenti --}}
                <div class="my-apartments">
                    <div class="box-service">
                        {{-- servizi --}}
                        <div class="serv-top">
                            <div v-for="service in allServices" class="my-services">
                                <input v-on:change="filterApartments" type="checkbox" :name="service.name" :value="service.id"
                                    v-model="filterServices">
                                <label for="" style="margin-right:10px">@{{ service . name }}</label>
                            </div>
                        </div>
                        {{-- camere - letti - raggio --}}
                        <div class="serv-bottom">
                            <div class="center">
                                <label for="rooms">Camere</label>
                                <input v-on:change="filterApartments" type="number" name="rooms" id="rooms" v-model="rooms"
                                    onkeydown="return false" min="1" class="inp">
                            </div>
                            <div class="center">
                                <label for="beds">Letti</label>
                                <input v-on:change="filterApartments" type="number" name="beds" id="beds" v-model="beds"
                                    onkeydown="return false" min="1" class="inp">
                            </div>
                            <div class="center">
                                <label for="radius">Raggio @{{ this.radius }} (km)</label>
                                <input v-on:change="filterApartments" type="range" id="radius" name="radius" v-model="radius"
                                min="0" max="20">
                            </div>
                        </div>
                    </div>
                    <div class="box-title">
                        <h2>Appartamenti:</h2>
                    </div>
                    <div v-for="apartment in currentApartments" v-if="apartment.visible == 0" class="spc-apart">
                        <div class="myline">
                        </div>
                        <a :href="'/apartment/' + apartment.id" class="box-list-apt">
                            <div id="img-box">
                                <img :src="'/storage/images/' + apartment.cover_image">
                            </div>
                            <div class="ap-description">
                                <ul class="box-apts">
                                    <li><h3>@{{ apartment.title }}</h3></li>
                                    <li><i class="fas fa-map-marker-alt"></i>  @{{ apartment.address}}</li>
                                    <li><i class="fas fa-city"></i>  @{{ apartment.city}}</li>
                                    <li>
                                        <ul class="box-options">
                                            <li><i class="fas fa-door-open"></i>  @{{ apartment.rooms_number}}</li>
                                            <li><i class="fas fa-bed"></i>  @{{ apartment.beds_number}}</li>
                                            <li><i class="fas fa-toilet"></i>  @{{ apartment.bathrooms_number}}</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- tom tom map --}}
                <div id="map" class="box-map">
                    <div id="mymap">
                    </div>
                </div>
            </div>
        </div>
    </div>   

    {{-- Vue Js --}}
    <script>

    
        new Vue({

            el: '#search',

            data: {

                allApartments: [],
                currentApartments: [],
                allServices: [],
                filterServices: [],
                filterData: [],
                tomTom: [],
                coord: [],
                radius: 20,
                lat: 0,
                lon: 0,
                beds: '1',
                rooms: '1',
                //visibility: 'display: none',
            },

            methods: {

                filterApartments: function() {

                    const searchString = new URL(location.href).searchParams.get('searchString');
                    let bedsRooms = [];
                    bedsRooms.push(this.beds, this.rooms);


                    if (this.filterServices.length > 0) {

                        axios.post('/api/filterApartments/' + searchString + '/' + this.filterServices + '/' +
                                bedsRooms)
                            .then(res => {

                                this.currentApartments = res.data;
                                //console.log(res.data);
                                this.getMap(searchString)

                                this.getRadius();
                            });


                    } else {

                        let filterServices = 'noServices';

                        axios.post('/api/filterApartments/' + searchString + '/' + filterServices + '/' +
                                bedsRooms)
                            .then(res => {

                                this.currentApartments = res.data;
                                //console.log(res.data);
                                this.getMap(searchString)

                                this.getRadius();
                            });

                    }


                },

                getRadius: function() {

                    let newCurrentApartments = [];

                    this.currentApartments.forEach(currentApartment => {

                        // console.log(currentApartment);

                        const point0 = [this.lon, this.lat]

                        const point1 = [currentApartment.longitude, currentApartment.latitude]

                        let kilometers = turf.distance(point0, point1);

                        if (kilometers <= parseInt(this.radius)) {

                            newCurrentApartments.push(currentApartment);
                        }
                    });

                    this.currentApartments = newCurrentApartments;
                },

                getMap: function(searchString) {

                    // TOM TOM 
                    axios.get('https://api.tomtom.com/search/2/geocode/%20' + searchString +
                            '%20it.JSON?key=e221oCcENGoXZRDyweSTg7PnYGiEXO82', {
                                headers: ''
                            })
                        .then(res => {

                            this.tomTom = res.data.results[0];

                            this.lat = this.tomTom.position.lat;
                            this.lon = this.tomTom.position.lon;


                            // Tom Tom Map

                            //console.log(this.lat, this.lon);

                            var APIKEY = "XPOiPra9khmu2grECjX15gw5Cdy98fSX"
                            var CITY = [this.lon, this.lat] // [longitudine, latitudine]
                            //var ROMA1 = [12.48536, 41.88697]


                            var map = tt.map({
                                key: APIKEY,
                                container: 'mymap',
                                center: CITY,
                                zoom: 11,
                                style: 'tomtom://vector/1/basic-main'
                            });


                            // Bandiere + popup Appartamenti su mappa
                            for (i = 0; i < this.currentApartments.length; i++) {

                                var appLatLon = [this.currentApartments[i].longitude, this
                                    .currentApartments[i].latitude
                                ]
                                var appTitle = [this.currentApartments[i].title]
                                var appAddress = [this.currentApartments[i].address]

                                //console.log(this.allApartments);
                                // console.log(this.currentApartments);

                                var marker1 = new tt.Marker().setLngLat(appLatLon).addTo(map);

                                var popup = new tt.Popup({
                                    offset: popupOffsets
                                }).setHTML("<strong>" + this.currentApartments[i].title + "</strong>" +
                                    "<br>" + this.currentApartments[i].address);
                                marker1.setPopup(popup).togglePopup();

                            }

                            // bandiere su mappa
                            // var marker = new tt.Marker().setLngLat(CITY).addTo(map);


                            var popupOffsets = {
                                top: [0, 0],
                                bottom: [0, -70],
                                'bottom-right': [0, -70],
                                'bottom-left': [0, -70],
                                left: [25, -35],
                                right: [-25, -35]
                            }

                            // popup nomi appartamenti
                            // var popup = new tt.Popup({offset: popupOffsets}).setHTML("CITY");
                            // marker.setPopup(popup).togglePopup();



                            //controlli mappa    
                            map.addControl(new tt.NavigationControl());

                        });

                },

                navbar: function() {
                    if (this.visibility == 'display: none') {

                        // console.log(this.visibility);

                        this.visibility = 'display: flex';

                        console.log(this.visibility);

                    } else {
                        this.visibility = 'display: none';

                        console.log(this.visibility);
                    }
                }

            },

            mounted() {


                const searchString = new URL(location.href).searchParams.get('searchString');

                axios.get('/api/getApartments/' + searchString)
                    .then(res => {

                        this.allApartments = res.data;
                        this.currentApartments = this.allApartments;
                        // console.log(this.currentApartments, this.allApartments);

                        this.getMap(searchString)

                    });

                axios.get('/api/getServices/')
                    .then(res => {

                        this.allServices = res.data;
                    });

            }, // fine Mounted()

            /* computed: {

                gethref: function() {
                    return '/apartment/' + this.filterServices
                }
            }, */

        });
    </script>

@endsection
