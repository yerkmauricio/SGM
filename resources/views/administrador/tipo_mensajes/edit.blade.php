@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="card" style="font-family: 'Times New Roman', Times, serif;">
        <div class="card-body">
            <h1>{{ ucfirst('modificar al tipo de mensaje:') }}&nbsp;{{ ucfirst($tipo_mensaje->nombre) }}</h1>
        </div>
    </div>
@stop

@section('content')

    <div class="card" style="font-family: 'Times New Roman', Times, serif;">
        <div class="card-body card-body col-md-9 mx-auto">
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
            <form method="POST" action="{{ route('tipo_mensajes.update', $tipo_mensaje) }}" enctype="multipart/form-data">
                @csrf {{-- evita sql inyection --}}
                @method('PUT')


                {{-- nombre --}}
                <div class="form-group">
                    <label for="formGroupExampleInput">Nombre del tipo de mensaje</label>
                    <input type="text" class="form-control" name="nombre"
                        value="{{ old('nombre', $tipo_mensaje->nombre) }}"
                        pattern="^(?!.*([A-Za-záéíóúüñÑ])\1{3})[A-Za-záéíóúüñÑ\s\d¡!¿?]+"
                        title="Solo se permiten letras y espacios, sin repetir 4 veces seguidas">
                    @error('nombre')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- descripcion
                <div class="form-group">
                    <label for="formGroupExampleInput">Contenido</label>
                    <input type="text" class="form-control" name="descripcion"
                        value="{{ old('descripcion', $tipo_mensaje->descripcion) }}"
                        pattern="^(?!.*(.)\1{3})[A-Za-záéíóúüñÑ0-9\s¡!¿?]+"
                        title="Solo se permiten letras, números, espacios, ! y ? sin repetir 4 veces seguidas">
                    @error('descripcion')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>--}}

                {{-- descripcion --}}
                <div class="form-group">
                    <label for="formGroupExampleInput">Contenido del mensaje</label>
                    <!-- Reemplaza el input de texto con Summernote -->
                    <textarea id="summernote" class="form-control" name="descripcion"
                        placeholder="Ingrese el contenido del tipo de mensaje">{{ old('descripcion', $tipo_mensaje->descripcion) }}</textarea>
                    @error('descripcion')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- estado --}}
                <div class="form-group">
                    <label for="formGroupExampleInput">Estado del tipo de mensaje</label>
                    <select class="form-control" name="estado">
                        <option value="1" @if (old('estado', $tipo_mensaje->estado) == '1') selected @endif>activo</option>
                        <option value="0" @if (old('estado', $tipo_mensaje->estado) == '0') selected @endif>inactivo</option>
                    </select>
                    @error('estado')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- foto --}}
                <div class="form-group">
                    <label>Foto del tipo de mensaje</label>
                    <input type="file" class="form-control-file" name="foto" accept=".jpg, image/jpeg"
                        onchange="previewImage(event)">
                    @error('foto')
                        <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Imagen seleccionada:</label>
                    <img id="imagePreview" src="#" alt="Imagen seleccionada"
                        style="max-width: 200px; display: none;">
                </div>





                <div style="text-align: center;">
                    <button type="submit" class="btn btn-primary float-center">
                        <i class="fas fa-edit"></i>
                        Actualizar tipo de mensaje
                    </button>
                </div>
            </form>

        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">

    <link href="https://cdn.jsdelivr.net/npm/summernote@latest/dist/summernote-bs5.css" rel="stylesheet">
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
    <script src="https://cdn.jsdelivr.net/npm/summernote@latest/dist/summernote-bs5.js"></script>
    <script>
        $(document).ready(function() {
            // Obtener el nombre del usuario autenticado
            var usuarioNombre = '{{ ucfirst(auth()->user()->name) }}';
            var usuarioApellido = '{{ ucfirst(auth()->user()->apellidopaterno) }}';

            // Inicializar Summernote
            $('#summernote').summernote({
                tabsize: 2,
                height: 200,
                toolbar: [
                    ['font', ['bold', 'clear']],
                    //['fontname', ['fontname']],
                    ['para', ['ul']],
                    //['insert', ['link']],
                    ['view', ['fullscreen']],
                ],
                icons: {
                    'align': '<i class="custom-icon-align"></i>',
                    'italic': '<i class="custom-icon-italic"></i>',
                },
                // callbacks: {
                //     onPaste: function(e) {
                //         var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData)
                //             .getData('Text');
                //         e.preventDefault();
                //         document.execCommand('insertText', false, bufferText);
                //     },
                //     onInit: function() {
                //         // Deshabilitar la edición de los spans no editables
                //         $('.non-editable').attr('contenteditable', 'false');
                //     },
                //     onKeydown: function(e) {
                //         // Prevenir la eliminación del texto no editable
                //         if (e.keyCode === 8 || e.keyCode === 46) { // Backspace o Delete
                //             var selection = window.getSelection();
                //             if (selection.rangeCount > 0) {
                //                 var range = selection.getRangeAt(0);
                //                 if (range.startContainer.parentElement.classList.contains(
                //                         'non-editable') ||
                //                     range.endContainer.parentElement.classList.contains('non-editable')
                //                 ) {
                //                     e.preventDefault();
                //                 }
                //             }
                //         }
                //     },
                //     onKeyup: function(e) {
                //         // Prevenir la edición del texto no editable
                //         var nonEditableElements = $('.non-editable');
                //         nonEditableElements.each(function() {
                //             $(this).attr('contenteditable', 'false');
                //         });
                //     }
                // }
            });

            // // Función para actualizar el contenido del editor
            // function actualizarContenido() {
            //     var tipoMensaje = $('input[name="nombre"]').val().toUpperCase();
            //     var contenido = '<p class="non-editable">' + tipoMensaje + '</p><p class="non-editable">' +
            //         usuarioNombre + ' ' + usuarioApellido + '</p><p><br></p>';
            //     $('#summernote').summernote('code', contenido);
            // }

            // // Evento de cambio en el campo de "Nombre del tipo de mensaje"
            // $('input[name="nombre"]').on('input', function() {
            //     actualizarContenido();
            // });

            // // Llamar a la función para inicializar el contenido al cargar la página
            // actualizarContenido();


            // $('form').on('submit', function() {
            //     var summernoteContent = $('#summernote').summernote('code');
            //     var cleanedContent = $('<div>').html(summernoteContent).find('.non-editable').remove().end()
            //         .html();
            //     $('#summernote').summernote('code', cleanedContent);
            // });



        });
    </script>

@stop
