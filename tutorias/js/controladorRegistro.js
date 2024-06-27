$(document).ready(function() {
    $("#grupo").hide();
    $("#grupo_label").hide();
    $("#grupo_label").prop("disabled", true);
    $("#tutor").prop("disabled", true);

    $("#tutoria_select").change(onTutoriaSelect);
    $("#tutor").change(onTutorSelect);
});

function onTutoriaSelect() {
    idTipoTutoria = $("#tutoria_select").val();
    onGeneroSelect(idTipoTutoria);
}

function onGeneroSelect(idTipoTutoria){
    varGenero = $('#genero').val();
    
    $.ajax({
        url: 'php/tutores_disponibles.php',
        type: 'POST',
        data: { id_tipo_tutoria: idTipoTutoria, genero: varGenero },
        dataType: 'json',
        success: function(data) {
            var select = $('#tutor');
            select.empty();
            select.append($('<option>', { 
                value: "",
                text : "Selecciona un tutor",
                disabled: true,
                selected: true
            }));
            $.each(data, function(index, item) {
                select.append($('<option>', { 
                    value: item.id,
                    text : item.nombre + " " + item.apellido_paterno + " " + item.apellido_materno
                }));
            });
            select.prop("disabled", false);
        },
        error: function(error) {
            alert('Error: ', error);
        }
    });  
}


function onTutorSelect() {
    if ($("#tutoria_select").val() == 2) {
        $("#grupo").show();
        $("#grupo_label").show();
        var idTutor = $("#tutor").val();

        $.ajax({
            url: 'php/grupos_disponibles.php',
            type: 'POST',
            data: { id_tutor: idTutor },
            dataType: 'json',
            success: function(data) {
                var select = $('#grupo');
                select.empty();
                select.append($('<option>', { 
                    value: "",
                    text : "Selecciona un grupo",
                    disabled: true,
                    selected: true
                }));
                $.each(data, function(index, item) {
                    select.append($('<option>', { 
                        value: item.id_grupo,
                        text : item.codigo_grupo
                    }));
                });
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    }
}