const searchParams = new URLSearchParams(window.location.search.substring(1));
const branchId = searchParams.get("branch")



function crearMapa(){

    var bsas = {lat: -34.6037, lng: -58.3816};
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: bsas,
        styles: [
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
    });

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

        const points = stops.map( s => ({lat:parseFloat(s.latitude),lng:parseFloat(s.longitude), sequence : s.sequence}))

        console.log(points)

        points.forEach( p => {    
            const marker = new google.maps.Marker({
                position: p,
                map: map,
                draggable: false,
                label: "" + p.sequence
            })
                                          
            markers.push(marker)

        })

        const waypoints =  points.slice(1, -1).map( p => ({ location : p , stopover : false}))

        directionsService.route({
            origin: points[0],
            destination: points[points.length - 1],
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

        axios.get("/branchStops/" + branchId)
        .then((resp)=> {
            updateMarkers(resp.data)
        })
            .catch(error => console.error(error.response ? error.response.data : error))
    




}

const data = {
    branches: []
}

window.addEventListener("load",()=> {
    updateBranches()
 
 new Vue({
   el: '#branchesSelect',
   data: data
   })  
 
})

function updateBranches(){
    axios.get("/branches")
    .then((resp)=> {
      data.branches = resp.data
    })
    .catch((err)=>
      console.error(err.response.data)
    )
    
    }


    crearMapa()