@extends('layouts.app')

@section('content')
<div class="container">
    <script src="{{ asset('js/stops.js') }}" defer></script>

    <div>
        
        <div id="branchesSelect">
            <form class="form-inline" >
            <div class="form-group" style="float: right">
                    <div class="dropdown" >
                        <button class="dropbtn">Ramales</button>
                        <div class="dropdown-content">
                            <div v-for="branch in branches">
                                <a v-bind:href="'{{ url('/stopsView') }}?branch=' + branch.id"  style=" font-size: 80%">
                                    @{{ branch.name }}
                                </a>
                            </div>  
                        </div>
                    </div>
                </div>
                <div class="form-group">
                <h2 >  &nbsp &nbsp  </h2>
                    <h2 >
                        <a style="text-decoration:none" href="{{ url('/branchesView') }}" id="tituloStops"> Paradas: </a>  
                    </h2>
                </div>
                
            </form>
        </div>
        
        <div id="stops">
            <table class="table table-striped" v-cloak>
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Creacion</th>
                        <th>Modificacion</th>
                        <th>Latitud</th>
                        <th>Longitud</th>
                        <th>
                            <button id="botonCrear" type="button" class="btn btn-success"  data-toggle="modal" v-on:click="crearMapa('map', false)" data-target="#ModalCrearParada" >
                                <i class="material-icons">directions_bus</i>
                            </button>
                        </th>
                    </tr>
                </thead>
                
                <tbody >
                    <tr v-for="stop in stops" >
                        <td>@{{ stop.sequence }}</td>
                        <td>@{{ stop.id }}</td>
                        <td>@{{ stop.name }}</td>
                        <td>@{{ stop.created_at }}</td>
                        <td>@{{ stop.updated_at }}</td>
                        <td>@{{ stop.latitude }}</td>
                        <td>@{{ stop.longitude }}</td>
                        <td>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#ModalEliminarParada" v-on:click="eliminandolo(stop.id)">
                                <i class="material-icons">delete</i>
                            </button>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalEditarParada" v-on:click="editandolo(stop.id)">
                                <i class="material-icons">mode_edit</i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>


            <!-- MODALES -->

            <div id="ModalCrearParada" role="dialog" class="modal">
                <div class="modal-dialog">
                    <div class="modal-content" style="height:580px, width:70%">
                        <div class="modal-header">
                            <h4 class="modal-title">Crear Parada</h4>
                            <button type="button" data-dismiss="modal" class="close">×</button>   
                        </div>
                        <div class="modal-body">
                            <div>
                                <label>Nombre:</label>
                                <input type="text" id="name" placeholder="por favor ingrese el nombre" class="form-control" v-model="stopToCreate.name">
                            </div>
                            <div>
                                <label>Secuencia:</label>
                                <input type="number" id="sequence" placeholder="por favor ingrese el numero de secuencia" class="form-control" v-model="stopToCreate.sequence">
                            </div>
                            <br>
                            <div>
                                <form>
                                    <div class="row">
                                        <div class="col">
                                            <label>Latitud:</label>
                                            <input type="number" id="latitude" placeholder="por favor ingrese la latitud" class="form-control" v-model="stopToCreate.latitude">
                                        </div>
                                        <div class="col">
                                            <label>Longitud:</label>
                                            <input type="number" id="longitude" placeholder="por favor ingrese la longitud" class="form-control" v-model="stopToCreate.longitude">
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div id="map"></div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Cancelar</button>
                        <button type="button" data-dismiss="modal" class="btn btn-success" v-on:click="agregarParada(stopToCreate)">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        
        <div id="ModalEditarParada" role="dialog" class="modal fade in">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Editar Ramal</h4>
                        <button type="button" data-dismiss="modal" class="close">×</button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label>Nombre:</label>
                            <input type="text" id="name" placeholder="por favor ingrese el nombre" class="form-control" v-model="stopToEdit.name">
                        </div>
                        <div>
                            <label>Secuencia:</label>
                            <input type="number" id="sequence" placeholder="por favor ingrese el numero de secuencia" class="form-control" v-model="stopToEdit.sequence">
                        </div>
                        <div>
                                <form>
                                    <div class="row">
                                        <div class="col">
                                            <label>Latitud:</label>
                                            <input type="number" id="latitude" placeholder="por favor ingrese la latitud" class="form-control" v-model="stopToEdit.latitude">
                                        </div>
                                        <div class="col">
                                            <label>Longitud:</label>
                                            <input type="number" id="longitude" placeholder="por favor ingrese la longitud" class="form-control" v-model="stopToEdit.longitude">
                                        </div>
                                    </div>
                                </form>
                                <br>
                                <div id="mapEditStop"></div>
                            </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Cancelar</button>
                        <button type="button" data-dismiss="modal" class="btn btn-success" v-on:click="updateStop(stopToEdit)">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="ModalEliminarParada" role="dialog" class="modal fade in">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Eliminar Parada</h4>
                        <button type="button" data-dismiss="modal" class="close">×</button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label>Usted esta por eliminar la Parada: @{{ Stop_to_Delete.sequence }}</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-default">Cancelar</button>
                        <button type="button" data-dismiss="modal" class="btn btn-success" v-on:click="deleteStop">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBLDSPbOx2b4QKAgdY6qRa4LdBcg1tm-xs">
</script>

@endsection
