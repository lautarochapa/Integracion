/*___________________________________________________________
Inicializacion de variable Data
___________________________________________________________*/

const data = {
    branches: [],
    stops:[],
    Stop_to_Delete : {},
    stopToCreate : {
        name : "", 
        sequence: 0, 
        latitude: 0, 
        longitude: 0
    },
    stopToEdit : {
        name : "", 
        sequence: 0, 
        latitude: 0, 
        longitude: 0,
        id: 0
    },
}

function updateBranches(){
    axios.get("/branches")
        .then((resp)=> { data.branches = resp.data })
        .catch((err)=> { console.error(err.response.data)})
}
  
function updatePage(id){
    axios.get("/branchStops/" +id)
        .then((resp)=> {
            data.stops = resp.data
            data.stops.sort(function (a, b){
                return (a.sequence - b.sequence)
            })
        })
        .catch(error => console.error(error.response ? error.response.data : error))
}

/*___________________________________________________________
PARAMETROS RECIBIDOS POR URL, si no se recibe nada por parametro redireccionamos al primero obtenido en la busqueda.
___________________________________________________________*/

    const searchParams = new URLSearchParams(window.location.search.substring(1));
    var branchId = searchParams.get("branch")
    
    if(branchId==null){
        axios.get("/branches")
        .then((resp)=> {
            window.location.replace("http://127.0.0.1:8000/stopsView?branch="+resp.data[0].id);
        })
        .catch((err)=>
          console.error(err.response.data)
        )
    }
    nombreRamal =''



/*___________________________________________________________
Inicializacion de la pagina
___________________________________________________________*/

    function actualizarNombrePagina(){
        axios.get("/branches/"+branchId)
        .then((resp)=> {
            
            tituloPag = document.getElementById('tituloStops').innerText += ' ' +resp.data.name
        })
        .catch((err)=>
          console.error(err.response.data)
        )
    
    }
        
    window.addEventListener("load",()=> {
        updateBranches()
        updatePage(branchId)
        actualizarNombrePagina()

        new Vue({
            el: '#branchesSelect',
            data: data
        }) 
        new Vue({
            el: '#stops',
            data: data,
            methods:{
                deleteStop : deleteStop,
                updateStop : updateStop
            }
        })  
    })

 
/*___________________________________________________________
Funciones para mantener las secuencias concistentes
___________________________________________________________*/
   
    function actualizarSecuencias(stop, listaParadas){
        flagActualizarSequence = "False"

        listaParadas.forEach(parada => {
            if(parada.sequence == stop.sequence){
                flagActualizarSequence = "True"
            }
        });

        if(flagActualizarSequence == "True"){
            listaParadas.forEach(parada => {
                if(parada.sequence >= stop.sequence){
                    parada.sequence += 1
                    updateSequenceStop(parada) 
                }
            });
            flagActualizarSequence = "False"
        }
    }

    function updateSequenceStop(stop){
        axios.put("/stops/" + stop.id, {name:stop.name, sequence:stop.sequence, latitude:stop.latitude, longitude:stop.longitude})
            .then((resp)=>{
                updatePage(branchId);
                data.stopToEdit = ""
            })
            .catch((err)=> console.error(err.response.data))
        data.stopToEdit = {};
    }


/*___________________________________________________________
 AGREGAR PARADA
___________________________________________________________*/

    function agregarParada(stop){
        console.log(stop)
        actualizarSecuencias(stop, data.stops)
        axios.post("/stops/",{branch:branchId, name:stop.name, sequence:stop.sequence, latitude:stop.latitude, longitude:stop.longitude})
            .then((resp)=>{
                updatePage(branchId)
                data. stopToCreate = {
                    name : "", 
                    sequence: 0, 
                    latitude: 0, 
                    longitude: 0
                }  
            })
            .catch((err)=> console.error(err.response.data))
    }

/*___________________________________________________________
ELIMINAR PARADA
___________________________________________________________*/
  
    function eliminandolo(id){
        axios.get("/stops/" + id)
            .then((resp)=> data.Stop_to_Delete = resp.data)
            .catch((err)=> console.error(err.response.data))
    }

    function deleteStop(){
        axios.delete("/stops/" + data.Stop_to_Delete.id)
            .then((resp)=> updatePage(branchId))
            .catch((err)=> console.error(err.response.data))
        data.Stop_to_Delete = {};
    }

/*___________________________________________________________
 EDITAR PARADA
___________________________________________________________*/

    function editandolo(id){
        crearMapa('mapEditStop', true)
        axios.get("/stops/" + id)
            .then((resp)=> data.stopToEdit = resp.data)
            .catch((err)=> console.error(err.response.data))
    }

    function updateStop(stop){
        actualizarSecuencias(stop, data.stops)
        axios.put("/stops/" + stop.id, {name:stop.name, sequence:stop.sequence, latitude:stop.latitude, longitude:stop.longitude})
            .then((resp)=>{
                updatePage(branchId);
                data.stopToEdit = ""})
            .catch((err)=> console.error(err.response.data))
        data.stopToEdit = {};
    }


/*___________________________________________________________
CREACION DEL MAPA
___________________________________________________________*/

    function crearMapa(map, Isdraggable){
        var bsas = {lat: -34.6037, lng: -58.3816};
        var map = new google.maps.Map(document.getElementById(map), {
            zoom: 12,
            center: bsas,
            styles: EstilosMapa
        })

        map.addListener("click", (e) => {
            const latLng = e.latLng
            data.stopToCreate.latitude = latLng.lat()
            data.stopToCreate.longitude = latLng.lng()
        })

        var directionsDisplay = new google.maps.DirectionsRenderer;
        var directionsService = new google.maps.DirectionsService;
                    
        directionsDisplay.setMap(map);
                    
        let markers = []
                    
        function updateMarkers (stops){
            markers.forEach(m=> m.setMap(null))
            markers = []
            stops.sort(function (a, b){
                return (a.sequence - b.sequence)
            })
            const points = stops.map( s => ({lat:parseFloat(s.latitude),lng:parseFloat(s.longitude), sequence : s.sequence, id : s.id}))
            points.forEach( p => {    
                const marker = new google.maps.Marker({
                    position: p,
                    map: map,
                    draggable: Isdraggable,
                    label: "" + p.sequence
                })
                console.log(marker.position)
                
                marker.addListener("dragend",()=> {
                    if (p.id == data.stopToEdit.id){
                        data.stopToEdit.latitude = marker.position.lat()
                        data.stopToEdit.longitude = marker.position.lng()
                        points.forEach( p =>{
                            if (data.stopToEdit.sequence == p.sequence){
                                console.log("iguales")
                                p.lat = marker.position.lat()
                                p.lng = marker.position.lng()
                            }
                        })

                    }else{
                        marker.position = {lat: p.lat ,lng : p.lng} 
                    }
                    
                                    armarCamino(points, directionsService, directionsDisplay)
                                })
                                                              
                                markers.push(marker)
                    
                            })

                            armarCamino(points, directionsService, directionsDisplay)
                    
                          


                    }
                    updateMarkers(data.stops)
                }


                function armarCamino(puntos, directionsService, directionsDisplay){
                    const waypoints =  puntos.slice(1, -1).map( p => ({ location : p , stopover : false}))
        
                    directionsService.route({
                        origin: puntos[0],
                        destination: puntos[puntos.length - 1],
                        waypoints: waypoints,
                        optimizeWaypoints: true,
                        travelMode: 'DRIVING'
                      },function(response, status) {
                        if (status === 'OK') {
                          directionsDisplay.setDirections(response);
                          directionsDisplay.setOptions({
                                suppressMarkers: true
                            });
                        } else {
                            console.error(response);
                        }
                    })
                }


EstilosMapa = [
    {elementType: 'geometry', stylers: [{color: '#242f3e'}]},
    {elementType: 'labels.text.stroke', stylers: [{color: '#242f3e'}]},
    {elementType: 'labels.text.fill', stylers: [{color: '#746855'}]},
    {
      featureType: 'administrative.locality',
      elementType: 'labels.text.fill',
      stylers: [{color: '#d59563'}]
    },
    {
      featureType: 'poi',
      elementType: 'labels.text.fill',
      stylers: [{color: '#d59563'}]
    },
    {
      featureType: 'poi.park',
      elementType: 'geometry',
      stylers: [{color: '#263c3f'}]
    },
    {
      featureType: 'poi.park',
      elementType: 'labels.text.fill',
      stylers: [{color: '#6b9a76'}]
    },
    {
      featureType: 'road',
      elementType: 'geometry',
      stylers: [{color: '#38414e'}]
    },
    {
      featureType: 'road',
      elementType: 'geometry.stroke',
      stylers: [{color: '#212a37'}]
    },
    {
      featureType: 'road',
      elementType: 'labels.text.fill',
      stylers: [{color: '#9ca5b3'}]
    },
    {
      featureType: 'road.highway',
      elementType: 'geometry',
      stylers: [{color: '#746855'}]
    },
    {
      featureType: 'road.highway',
      elementType: 'geometry.stroke',
      stylers: [{color: '#1f2835'}]
    },
    {
      featureType: 'road.highway',
      elementType: 'labels.text.fill',
      stylers: [{color: '#f3d19c'}]
    },
    {
      featureType: 'transit',
      elementType: 'geometry',
      stylers: [{color: '#2f3948'}]
    },
    {
      featureType: 'transit.station',
      elementType: 'labels.text.fill',
      stylers: [{color: '#d59563'}]
    },
    {
      featureType: 'water',
      elementType: 'geometry',
      stylers: [{color: '#17263c'}]
    },
    {
      featureType: 'water',
      elementType: 'labels.text.fill',
      stylers: [{color: '#515c6d'}]
    },
    {
      featureType: 'water',
      elementType: 'labels.text.stroke',
      stylers: [{color: '#17263c'}]
    }
  ]