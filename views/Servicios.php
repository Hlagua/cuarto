<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sistema de Gestión de Estudiantes</title>
    <link rel="stylesheet" type="text/css" href="https://www.jeasyui.com/easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="https://www.jeasyui.com/easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="https://www.jeasyui.com/easyui/themes/color.css">
    <script type="text/javascript" src="https://www.jeasyui.com/easyui/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.jeasyui.com/easyui/jquery.easyui.min.js"></script>
</head>

<body>
    <h2>Sistema de Gestión de Estudiantes</h2>

    <table id="dg" title="Gestion de Estudiantes" class="easyui-datagrid" style="width:700px;height:250px"
           url="models/acceder.php"
           toolbar="#toolbar" pagination="true"
           rownumbers="true" fitColumns="true" singleSelect="true">
        <thead>
            <tr>
                <th field="estcedula" width="50">Cedula</th>
                <th field="estnombre" width="50">Nombre</th>
                <th field="estapellido" width="50">Apellido</th>
                <th field="estdireccion" width="50">Direccion</th>
                <th field="esttelefono" width="50">Telefono</th>
            </tr>
        </thead>
    </table>

    <div id="toolbar">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">Agregar estudiante</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()">Editar estudiante</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyUser()">Eliminar estudiante</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="openBuscar()">Buscar</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="limpiarBusqueda()">Limpiar</a>
        <a href="http://localhost/cuarto/reportes/reporte.php" class="easyui-linkbutton" iconCls="icon-print" plain="true" target="_blank">Generar Reporte PDF</a>
        <a href="http://localhost/cuarto/reportes/reporteJasper.php" class="easyui-linkbutton" iconCls="icon-print" plain="true" target="_blank">Generar Reporte Jasper</a>
        
    </div>

    <!-- DIALOGO PARA AGREGAR Y EDITAR ESTUDIANTES -->
    <div id="dlg" class="easyui-dialog" style="width:400px"
         data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
        <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px">
            <h3>Ingrese la informacion del estudiante</h3>
            <div style="margin-bottom:10px">
                <input name="estcedula" class="easyui-textbox" required="true" label="Cedula:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="estnombre" class="easyui-textbox" required="true" label="Nombre:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="estapellido" class="easyui-textbox" required="true" label="Apellido:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="estdireccion" class="easyui-textbox" required="true" label="Direccion:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="esttelefono" class="easyui-textbox" required="true" label="Telefono:" style="width:100%">
            </div>
        </form>
    </div>

    <div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUser()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#dlg').dialog('close')" style="width:90px">Cancel</a>
    </div>

    <!-- DIALOGO PARA BUSCAR CEDULA -->
    <div id="dlgBuscar" class="easyui-dialog" style="width:300px"
         data-options="closed:true,modal:true,border:'thin',buttons:'#dlgBuscar-buttons'">
        <form id="fmBuscar" method="post" style="margin:0;padding:20px 30px">
            <h3>Buscar por cédula</h3>
            <div style="margin-bottom:10px">
                <input id="cedulaBuscar" name="cedulaBuscar" class="easyui-textbox" required="true" label="Cédula:" style="width:100%">
            </div>
        </form>
    </div>

    <div id="dlgBuscar-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-search" onclick="buscarUserDialog()" style="width:90px">Buscar</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#dlgBuscar').dialog('close')" style="width:90px">Cancelar</a>
    </div>


    
    <!-- Script section -->
<script>
var url;

function newUser(){
    $('#dlg').dialog('open').dialog('center').dialog('setTitle','Nuevo estudiante');
    $('#fm').form('clear');
    url = 'models/guardar.php';
}

function editUser(){
    var row = $('#dg').datagrid('getSelected');
    if (row){
        $('#dlg').dialog('open').dialog('center').dialog('setTitle','Editar estudiante');
        $('#fm').form('load',row);
        url = 'models/actualizar.php?estcedula='+row.estcedula;
    }
}

function saveUser(){
    $('#fm').form('submit',{
        url: url,
        iframe: false,
        onSubmit: function(){
            return $(this).form('validate');
        },
        success: function(result){
            result = eval('('+result+')');
            if (result.errorMsg){
                $.messager.show({ title:'Error', msg:result.errorMsg });
            } else {
                $('#dlg').dialog('close');
                $('#dg').datagrid('reload');
            }
        }
    });
}

function openBuscar(){
    $('#fmBuscar').form('clear');
    $('#dlgBuscar').dialog('open').dialog('center').dialog('setTitle','Buscar Estudiante');
}

function buscarUserDialog(){
    var cedula = $('#cedulaBuscar').textbox('getValue');
    if(cedula==''){ return; }

    $('#dlgBuscar').dialog('close');
    $('#dg').datagrid('options').url = 'models/buscar.php';
    $('#dg').datagrid('load',{ estcedula: cedula });
}

function limpiarBusqueda(){
    $('#dg').datagrid('options').url = 'models/acceder.php';
    $('#dg').datagrid('reload');
}

function destroyUser(){
    var row = $('#dg').datagrid('getSelected');
    if (row){
        $.messager.confirm('Confirma','¿Seguro desea eliminar?',function(r){
            if (r){
                $.post('models/eliminar.php',{estcedula:row.estcedula},function(result){
                    if (result.success){
                        $('#dg').datagrid('reload');
                    } else {
                        $.messager.show({ title:'Error', msg:result.errorMsg });
                    }
                },'json');
            }
        });
    }
}
</script>

</body>
</html>
