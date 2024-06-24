@extends('adminlte::page')

@section('title', 'Editar')

@section('content_header')
<div class="card" style="font-family: 'Times New Roman', Times, serif;">
    <div class="card-body" style="text-align: center;">
        <strong>
            <h1 style="text-transform: uppercase; font-weight: bold;">Formulario para agregar una nueva persona</h1>
        </strong>
        <br><br>
      {{-- ci --}}
      <div class="form-group col-md-6 mb-3">
        <label for="formGroupExampleInput">Documento nacional de identidad</label>
        <input type="text" class="form-control" id="ci" name="ci" value="{{ old('ci') }}"
            placeholder="Ingrese el Documento nacional de identidad de la persona" pattern="^\d{7,10}$"
            title="Solo se permiten números enteros de 7 a 10 dígitos">
        @error('ci')
            <span style="color: red;">{{ $message }}</span>
        @enderror
    </div>

    {{-- Botón con redirección --}}
    <a class="btn btn-success float-right" id="redirectBtn" href="#">
        <i class="fas fa-warehouse"></i>
        Solicitud: SIRP
    </a>
    </div>
</div>
@stop

@section('content')

    <div class="card" style="font-family: 'Times New Roman', Times, serif;">
        <br>
        <h4 style="text-transform: uppercase; font-weight: bold; text-align: center;">Formulario</h4>

        <h6><span style="color: red; text-transform: uppercase;">  autocompletacon el SIRP</span></h6>

        <div class="card-body col-md-9 mx-auto">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <p>Lo sentimos, está ingresando datos incorrectos o inexistentes. Por favor, verifique los campos y no
                        olvide subir la foto.</p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('personas.update', $persona) }}" enctype="multipart/form-data">
                @csrf {{-- evita sql inyection --}}
                @method('PUT')

                <div class="form-row">
                    {{-- nombre --}}
                    <div class="form-group col-md-6 mb-3">
                        <label for="formGroupExampleInput">Nombre de la Persona</label>
                        <input type="text" class="form-control" name="nombre"
                            value="{{ old('nombre', $persona->nombre) }}" pattern="^[A-Za-záéíóúüñÁÉÍÓÚÜ\s]+$"
                            title="Solo se permiten letras y espacios">
                        @error('nombre')
                            <span style="color: red;">{{ $message }}</span>
                        @enderror
                    </div>



                    {{-- apellidop --}}
                    <div class="form-group col-md-6 mb-3">
                        <label for="formGroupExampleInput">Apellido paterno</label>
                        <input type="text" class="form-control" name="apellidop"
                            value="{{ old('apellidop', $persona->apellidop) }}" pattern="^[A-Za-záéíóúüñÁÉÍÓÚÜ]+$"
                            title="Solo se permiten letras, sin espacios">
                        @error('apellidop')
                            <span style="color: red;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-row">

                    {{-- apellidom --}}
                    <div class="form-group col-md-6 mb-3">
                        <label for="formGroupExampleInput">Apellido materno</label>
                        <input type="text" class="form-control" name="apellidom"
                            value="{{ old('apellidom', $persona->apellidom) }}" pattern="^[A-Za-záéíóúüñÁÉÍÓÚÜ]+$"
                            title="Solo se permiten letras, sin espacios">
                        @error('apellidom')
                            <span style="color: red;">{{ $message }}</span>
                        @enderror
                    </div>


                    {{-- genero --}}
                    <div class="form-group col-md-6 mb-3">
                        <label for="formGroupExampleInput">Genero de la Persona</label>
                        <select class="form-control" name="genero">
                            <option value="1" @if (old('genero', $persona->genero) == '1') selected @endif>Masculino</option>
                            <option value="0" @if (old('genero', $persona->genero) == '0') selected @endif>Femenino</option>
                        </select>
                        @error('genero')
                            <span style="color: red;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-row">


                    {{-- whatsapp --}}
                    <div class="form-group col-md-6 mb-3">
                        <label for="formGroupExampleInput">Whatsapp de la Persona</label>
                        <div class="input-group">
                            <input type="tel" class="form-control" id="whatsapp" name="whatsapp"
                                value="{{ old('whatsapp', $persona->whatsapp) }}"placeholder="Ingrese el whatsapp del persona"
                                pattern="^[0-9]+$" title="Solo se permiten números">
                            <input type="hidden" id="codigoPais" name="codigoPais">
                        </div>
                        @error('whatsapp')
                            <span style="color: red;">{{ $message }}</span>
                        @enderror
                    </div>


                    {{-- institucion --}}
                    <div class="form-group col-md-6 mb-3">
                        <label for="formGroupExampleInput">Institucion de la Persona</label>
                        <input type="text" class="form-control" name="institucion"
                            value="{{ old('institucion', $persona->institucion) }}"
                            placeholder="Ingrese la institucion de la persona" pattern="^[A-Za-záéíóúüñÁÉÍÓÚÜ\s]+$"
                            title="Solo se permiten letras y espacios">
                        @error('institucion')
                            <span style="color: red;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    {{-- cargo --}}
                    <div class="form-group col-md-6 mb-3">
                        <label for="formGroupExampleInput">Cargo de la Persona</label>
                        <input type="text" class="form-control" name="cargo"
                            value="{{ old('cargo', $persona->cargo) }}" placeholder="Ingrese el cargo de la persona"
                            pattern="^[A-Za-záéíóúüñÁÉÍÓÚÜ\s]+$" title="Solo se permiten letras y espacios">
                        @error('cargo')
                            <span style="color: red;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- unidad --}}
                    <div class="form-group col-md-6 mb-3">
                        <label for="formGroupExampleInput">Unidad de la Persona</label>
                        <input type="text" class="form-control" name="unidad"
                            value="{{ old('unidad', $persona->unidad) }}" placeholder="Ingrese el unidad de la persona"
                            pattern="^[A-Za-záéíóúüñÁÉÍÓÚÜ\s]+$" title="Solo se permiten letras y espacios">
                        @error('unidad')
                            <span style="color: red;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-row">


                    {{-- sede --}}
                    <div class="form-group col-md-6 mb-3">
                        <label for="formGroupExampleInput">Sede de la Persona</label>
                        <input type="text" class="form-control" name="sede" value="{{ old('sede', $persona->sede) }}"
                            placeholder="Ingrese la sede de la persona" pattern="^[A-Za-záéíóúüñÁÉÍÓÚÜ\s]+$"
                            title="Solo se permiten letras y espacios">
                        @error('sede')
                            <span style="color: red;">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- carrera --}}
                    <div class="form-group col-md-6 mb-3">
                        <label for="formGroupExampleInput">Carrera de la Persona</label>
                        <input type="text" class="form-control" name="carrera"
                            value="{{ old('carrera', $persona->carrera) }}"
                            placeholder="Ingrese la carrera de la persona" pattern="^[A-Za-záéíóúüñÁÉÍÓÚÜ\s]+$"
                            title="Solo se permiten letras y espacios">
                        @error('carrera')
                            <span style="color: red;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                {{-- tipo_persona --}}
                <div class="form-group col-md-6 mb-3">
                    <label for="tipo_persona">Tipo de Persona:</label>
                    <select name="tipo_persona_id" class="form-control">
                        <option value="" selected disabled>Seleccione el tipo de persona</option>

                        @foreach ($tipo_personas as $tipo_personaId => $tipo_personaNombre)
                            <option value="{{ $tipo_personaId }}"
                                {{ old('tipo_persona_id', $persona->tipo_persona_id) == $tipo_personaId ? 'selected' : '' }}>
                                {{ $tipo_personaNombre }}
                            </option>
                        @endforeach

                    </select>
                    @error('tipo_persona_id')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="text-align: center;">
                    <button type="submit" class="btn btn-primary float-center">
                        {{-- <i class="fas fa-edit"></i> --}}
                        CREAR
                    </button>
                </div>
            </form>

        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
@stop

@section('js')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var imagePreview = document.getElementById('imagePreview');
                imagePreview.src = reader.result;
                imagePreview.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

@stop
