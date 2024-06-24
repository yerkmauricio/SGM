<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario para agregar una nueva persona</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    @extends('adminlte::page')

    @section('title', 'Dashboard')

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
        <div class="card  " style="font-family: 'Times New Roman', Times, serif;">
            <br>
            <h4 style="text-transform: uppercase; font-weight: bold; text-align: center;">Formulario</h4>
            <div class="card-body col-md-9 mx-auto">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <p>Lo sentimos, está ingresando datos incorrectos o inexistentes. Por favor, verifique los campos.
                        </p>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('personas.store') }}" enctype="multipart/form-data">
                    @csrf {{-- evita sql inyection --}}
                    @method('PUT')

                    <div class="form-row">
                        {{-- nombre --}}
                        <div class="form-group col-md-6 mb-3">
                            <label for="formGroupExampleInput">Nombre de la Persona</label>
                            <input type="text" class="form-control" name="nombre" placeholder="Ingrese el campo"
                                {{-- value="{{ old('nombre', $persona->nombre) }}" pattern="^[A-Za-záéíóúüñÁÉÍÓÚÜ\s]+$" --}} title="Solo se permiten letras y espacios">
                            @error('nombre')
                                <span style="color: red;">{{ $message }}</span>
                            @enderror
                        </div>



                        {{-- apellidop --}}
                        <div class="form-group col-md-6 mb-3">
                            <label for="formGroupExampleInput">Apellido paterno</label>
                            <input type="text" class="form-control" name="apellidop" placeholder="Ingrese el campo"
                                {{-- value="{{ old('apellidop', $persona->apellidop) }}" pattern="^[A-Za-záéíóúüñÁÉÍÓÚÜ]+$" --}} title="Solo se permiten letras, sin espacios">
                            @error('apellidop')
                                <span style="color: red;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">

                        {{-- apellidom --}}
                        <div class="form-group col-md-6 mb-3">
                            <label for="formGroupExampleInput">Apellido materno</label>
                            <input type="text" class="form-control" name="apellidom" placeholder="Ingrese el campo"
                                {{-- value="{{ old('apellidom', $persona->apellidom) }}" pattern="^[A-Za-záéíóúüñÁÉÍÓÚÜ]+$" --}} title="Solo se permiten letras, sin espacios">
                            @error('apellidom')
                                <span style="color: red;">{{ $message }}</span>
                            @enderror
                        </div>


                        {{-- genero --}}
                        <div class="form-group col-md-6 mb-3">
                            <label for="formGroupExampleInput">Genero de la Persona</label>

                            <select class="form-control" name="genero">
                                <option selected>Masculino</option>
                                <option selected>Femenino</option>
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
                                    placeholder="Ingrese el campo" pattern="^[0-9]+$" title="Solo se permiten números">
                                <input type="hidden" id="codigoPais" name="codigoPais">
                            </div>
                            @error('whatsapp')
                                <span style="color: red;">{{ $message }}</span>
                            @enderror
                        </div>


                        {{-- institucion --}}
                        <div class="form-group col-md-6 mb-3">
                            <label for="formGroupExampleInput">Institucion de la Persona</label>
                            <input type="text" class="form-control" name="institucion" {{-- value="{{ old('institucion', $persona->institucion) }}" --}}
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
                                placeholder="Ingrese el cargo de la persona" pattern="^[A-Za-záéíóúüñÁÉÍÓÚÜ\s]+$"
                                title="Solo se permiten letras y espacios">
                            @error('cargo')
                                <span style="color: red;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- unidad --}}
                        <div class="form-group col-md-6 mb-3">
                            <label for="formGroupExampleInput">Unidad de la Persona</label>
                            <input type="text" class="form-control" name="unidad"
                                placeholder="Ingrese el unidad de la persona" pattern="^[A-Za-záéíóúüñÁÉÍÓÚÜ\s]+$"
                                title="Solo se permiten letras y espacios">
                            @error('unidad')
                                <span style="color: red;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">


                        {{-- sede --}}
                        <div class="form-group col-md-6 mb-3">
                            <label for="formGroupExampleInput">Sede de la Persona</label>
                            <input type="text" class="form-control" name="sede"
                                placeholder="Ingrese la sede de la persona" pattern="^[A-Za-záéíóúüñÁÉÍÓÚÜ\s]+$"
                                title="Solo se permiten letras y espacios">
                            @error('sede')
                                <span style="color: red;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- carrera --}}
                        <div class="form-group col-md-6 mb-3">
                            <label for="formGroupExampleInput">Carrera de la Persona</label>
                            <input type="text" class="form-control" name="carrera" {{-- value="{{ old('carrera', $persona->carrera) }}" --}}
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

                            {{-- @foreach ($tipo_personas as $tipo_personaId => $tipo_personaNombre)
                                <option value="{{ $tipo_personaId }}"
                                    {{ old('tipo_persona_id', $persona->tipo_persona_id) == $tipo_personaId ? 'selected' : '' }}>
                                    {{ $tipo_personaNombre }}
                                </option>
                            @endforeach --}}

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
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
    @stop

    @section('js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>
        <script>
            var input = document.querySelector("#whatsapp");
            var iti = window.intlTelInput(input, {
                initialCountry: "auto",
                separateDialCode: true,
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
            });

            input.addEventListener("countrychange", function() {
                var countryCode = iti.getSelectedCountryData().dialCode;
                var phoneNumber = input.value;
                var phoneNumberWithCountryCode = countryCode + phoneNumber;
                input.value = phoneNumberWithCountryCode;
            });

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
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Obtener el botón de redirección
                const redirectBtn = document.getElementById('redirectBtn');
                // Obtener el campo 'ci'
                const ciInput = document.getElementById('ci');

                // Escuchar el evento click en el botón de redirección
                redirectBtn.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevenir el comportamiento por defecto del enlace

                    // Obtener el valor de 'ci' ingresado por el usuario
                    const ciValue = ciInput.value.trim();

                    // Comparar con los valores dados y redirigir según corresponda
                    if (ciValue === '10945895') {
                        window.location.href = "{{ route('personas.edit', ['persona' => 36]) }}";
                    } else if (ciValue === '10945785') {
                        window.location.href = "{{ route('personas.edit', ['persona' => 67]) }}";
                    } else if (ciValue === '9923258') {
                        window.location.href = "{{ route('personas.edit', ['persona' => 79]) }}";
                    } else {
                        alert('El número de documento no coincide con ningún registro válido.');
                    }
                });
            });
        </script>
    @stop
</body>

</html>
