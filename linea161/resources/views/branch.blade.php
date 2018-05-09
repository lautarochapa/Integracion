@extends('layouts.app')

@section('content')
<div class="container" id="branches">
    <script src="{{ asset('js/branches.js') }}" defer></script>
    <h2>
        Ramales:
    </h2>
    
    <table class="table table-striped" v-cloak>
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Creacion</th>
                <th>Modificacion</th>
                <th>Frecuencia</th>
                <th>Valor</th>
                <th>Paradas</th>
                <th>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#ModalCrearRamales" id="BotonCrearRamales">
                        <i class="material-icons">directions_bus</i>
                    </button>
                </th>
            </tr>
        </thead>
        <tbody >
            <tr v-for="branch in branches" >
                <td>@{{ branch.id }}</td>
                <td>@{{ branch.name }}</td>
                <td>@{{ branch.created_at }}</td>
                <td>@{{ branch.updated_at }}</td>
                <td>@{{ branch.frequency }}</td>
                <td>@{{ branch.km_value }}</td>
                <td>
                    <a v-bind:href="'{{ url('/stopsView') }}?branch=' + branch.id"  >
                        <button type="button" class="btn btn-primary" >
                            <i class="material-icons">edit_location</i>
                        </button>
                    </a>
                </td>
                <td>
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#ModalEliminarRamales" v-on:click="eliminandolo(branch.id)">
                        <i class="material-icons">delete</i>
                    </button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ModalEditarRamales" v-on:click="editandolo(branch.id)">
                        <i class="material-icons">mode_edit</i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- MODALES -->
    
    <div id="ModalCrearRamales" role="dialog" class="modal fade in">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Crear Ramal</h4>
                    <button type="button" data-dismiss="modal" class="close">×</button>
                </div>
                <div class="modal-body">
                    <div>
                        <label>Nombre:</label>
                        <input type="text" id="nombre" placeholder="Por favor ingrese el Nombre" class="form-control" v-model="newBranch_name">
                    </div>
                    <div>
                        <label>Frecuencia:</label>
                        <input type="number" id="frequency" placeholder="Por favor ingrese la Frecuencia" class="form-control" v-model="newBranch_frequency">
                    </div>
                    <div>
                        <label>Valor:</label>
                        <input type="number" id="km_value" placeholder="Por favor ingrese el valor del kilometro" class="form-control" v-model="newBranch_km">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Cancelar</button>
                    <button type="button" data-dismiss="modal" class="btn btn-success" v-on:click="agregarRamal(newBranch_name, newBranch_frequency, newBranch_km)">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="ModalEditarRamales" role="dialog" class="modal fade in">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar Ramal</h4>
                    <button type="button" data-dismiss="modal" class="close">×</button>
                </div>
                <div class="modal-body">
                    <div>
                        <label>Nombre:</label>
                        <input type="text"  class="form-control" v-model="Branch_to_edit.name">
                    </div>
                    <div>
                        <label>Frecuencia:</label>
                        <input type="number" class="form-control" v-model="Branch_to_edit.frequency">
                    </div>
                    <div>
                        <label>Valor:</label>
                        <input type="number" class="form-control" v-model="Branch_to_edit.km_value">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Cancelar</button>
                    <button type="button" data-dismiss="modal" class="btn btn-success" v-on:click="updateRamal(Branch_to_edit.name, Branch_to_edit.frequency, Branch_to_edit.km_value)">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="ModalEliminarRamales" role="dialog" class="modal fade in">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Eliminar Ramal</h4>
                    <button type="button" data-dismiss="modal" class="close">×</button>
                </div>
                <div class="modal-body">
                    <div>
                        <label>Usted esta por eliminar el Ramal: @{{Branch_to_delete.name}}</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Cancelar</button>
                    <button type="button" data-dismiss="modal" class="btn btn-success" v-on:click="deleteRamal">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection