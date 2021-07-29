<!DOCTYPE html>
<html>

<head>
    <title>Trafico de aerolineas</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <style>
        .edits {
            background-color: rgb( 255, 235, 220 );
            width: 19rem;
            border-radius: 20px;

        }

        .edit {
            background-color: rgb(71, 201, 101);
            border: none;
            color: white;
            padding: 15px 22px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 6rem;
        }

        .create {
            width: 19rem;
            background-color: rgb( 255, 235, 220 );
            border-radius: 20px;

        }

        .deleteBook {
            background-color: rgb(218, 23, 23);
            border: none;
            color: white;
            padding: 15px 22px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 6rem;
        }

        .bi :hover {
            background-color: rgb(194, 190, 189);

        }

        #createNewAirLine {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 15px 22px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 6rem;

        }

        #tamanio {
            padding: 8px 16px;
            border: 1px solid transparent;
            border-color: transparent transparent rgba(0, 0, 0, 0.1) transparent;
            cursor: pointer;
        }
        #tipo {
            padding: 8px 16px;
            border: 1px solid transparent;
            border-color: transparent transparent rgba(0, 0, 0, 0.1) transparent;
            cursor: pointer;
        }
        #modelHeading{
            background-color: rgb( 194, 194, 194);
            border: none;
            color: white;
            padding: 15px 22px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 6rem;
        }
        #saveBtn{
            background-color: rgb(71, 201, 101);
            border: none;
            color: white;
            padding: 15px 22px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 6rem;
        }
    </style>
</head>

<body>

    <h1>Aerolineas</h1>
    <a class="btn btn-success" href="javascript:void(0)" id="createNewAirLine"> Crear nuevo</a>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Tipo</th>
                <th>Tamaño</th>
                <th width="300px">Action</th>
            </tr>
        </thead>
        <tbody class="bi">
        </tbody>
    </table>
    <div id="camvas" class="camvas">
        <span id="modelHeading"></span>
        <form id="AirLine_form" name="AirLine_form">
            <input type="hidden" name="AirLine_id" id="AirLine_id">
            <div>
                <label for="name">Tipo</label>
                <div>
                    <select id="tipo" name="tipo" style="width:200px;">
                        <option value="Emergencia">Emergencia</option>
                        <option value="Grande">Grande</option>
                        <option value="Pasajero ">Pasajero </option>
                        <option value="Cargo">Cargo </option>
                    </select>
                </div>
            </div>

            <div>
                <label>Tamaño</label>
                <div>

                    <select id="tamanio" name="tamanio" style="width:200px;">
                        <option value="Chico">Chico</option>
                        <option value="Grande">Grande</option>
                    </select>
                </div>
            </div>

            <div>
                <button type="submit" id="saveBtn" value="create">Guardar
                </button>
            </div>
        </form>

    </div>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('aerolineas.index') }}",
                columns: [{
                    data: 'created_at',
                    name: 'tipo'
                },
                {
                    data: 'tipo',
                        name: 'tipo'
                    },
                    {
                        data: 'tamanio',
                        name: 'tamanio'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
            //Triger para crear nuevo
            $('#createNewAirLine').click(function() {
                $("div.camvas").toggleClass("create");
                $('#saveBtn').val("create");
                $('#AirLine_id').val('');
                $('#AirLine_form').trigger("reset");
                $('#modelHeading').html("Crear Nuevo");
            });
            //Triger para editar los datos en la bd
            $('body').on('click', '.editAirLine', function() {
                $("div.camvas").toggleClass("edits");
                let air_id = $(this).data('id');
                $.get("{{ route('aerolineas.index') }}" + '/' + air_id + '/edit', function(data) {
                    let prioridad = data.tamanio;
                    $('#modelHeading').html("Editar");
                    $('#saveBtn').val("edit-book");
                    $('#AirLine_id').val(data.id);
                    $('#tipo').val(data.tipo);
                    $('#tamanio').val(data.tamanio);
                })
            });
            //Triger para guardar los datos en la bd
            $('#saveBtn').click(function(e) {
                e.preventDefault();
                $(this).html('Save');

                $.ajax({
                    data: $('#AirLine_form').serialize(),
                    url: "{{ route('aerolineas.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {

                        $('#AirLine_form').trigger("reset");
                        table.draw();

                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Guardar Cambios');
                    }
                });
            });
            //Triger para eliminar los datos en la bd
            $('body').on('click', '.deleteBook', function() {

                let air_id = $(this).data("id");
                let confir = confirm("Seguro que quiere eliminar!");
                if (confir == true) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ route('aerolineas.store') }}" + '/' + air_id,
                        success: function(data) {
                            table.draw();
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                }
            });

        });
    </script>
</body>

</html>