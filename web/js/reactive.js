$( document ).ready(function() {
  
    $("#crearUsuario").validate({
        rules: {
            clave:  { required: true},
            nombre:  { required: true},
            apellido:  { required: true},
            correo:  { required: true, email: true },
            rol:  { required: true},
            claver: {equalTo: "#clave"},
        },
        messages: {
            nombre: "Debe ingresar el nombre",
            apellido: "Debe ingresar el apellido",
            correo: {
                    required: "Debe ingresar un correo electronico",
                    email: "Debe Ingresar un correo valido"},
            clave: "Debe ingresar la Contraseña",
            rol: "Debe elegir si es Administrador o Usuario",
            claver: "La Contraseña no coincide",
        },
        submitHandler: function(form){
            form.submit();
        } 
    });
 
    $("#registrarProducto").validate({
        rules: {
            nombre: { required: true},
            tipo: { required: true},
            codigo: { required:true},
            observacionCodigo: {required:true, minlength: 7},
            cantidad: { required: true, digits: true},
        },
        messages: {
            nombre: "El Nombre es requerido.",
            tipo: "Debe elegir un tipo (Perecedero/No Perecedero).",
            codigo : "Debe elegir una opción (Cumple/No Cumple/No Aplica).",
            observacionCodigo : {
                required : "Debe ingresar una Observación/Código de Barra.",
                minlength: "La Observación debe tener al menos 7 Caracteres."},
            cantidad : "Debe introducir la cantidad de producto.",
        },
        submitHandler: function(form){
            form.submit();
        }
    });

    var chart = AmCharts.makeChart("chartdiv", {
        "type": "serial",
        "theme": "light",
        "dataProvider": JSON.parse($("#productos").val()),
        "valueAxes": [{
            "stackType": "regular",
            "axisAlpha": 0.3,
            "gridAlpha": 0
        }],
        "graphs": [{
            "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
            "fillAlphas": 0.8,
            "labelText": "[[value]]",
            "lineAlpha": 0.3,
            "type": "column",
            "color": "#000000",
            "valueField": "cantidad"
        }],
        "categoryField": "codigo",
        "categoryAxis": {
            "gridPosition": "start",
            "axisAlpha": 0,
            "gridAlpha": 0,
            "position": "left"
        },
        "export": {
            "enabled": true
         }

    });

    var chart2 = AmCharts.makeChart("chartdiv2", {
      "type": "pie",
      "theme": "none",
      "dataProvider": JSON.parse($("#productosTipo").val()),
      "titleField": "tipo",
      "valueField": "cantidad",
      "labelRadius": 5,

      "radius": "42%",
      "innerRadius": "60%",
      "labelText": "[[title]]",
      "export": {
        "enabled": true
      }
    });
});

/**
 * Function that gets the data of the profile in case
 * thar it has already saved in localstorage. Only the
 * UI will be update in case that all data is available
 *
 * A not existing key in localstorage return null
 *
 */
function getLocalProfile(callback){
    var profileImgSrc      = localStorage.getItem("PROFILE_IMG_SRC");
    var profileName        = localStorage.getItem("PROFILE_NAME");
    var profileReAuthEmail = localStorage.getItem("PROFILE_REAUTH_EMAIL");

    if(profileName !== null
            && profileReAuthEmail !== null
            && profileImgSrc !== null) {
        callback(profileImgSrc, profileName, profileReAuthEmail);
    }
}

/**
 * Main function that load the profile if exists
 * in localstorage
 */
function loadProfile() {
    if(!supportsHTML5Storage()) { return false; }
    // we have to provide to the callback the basic
    // information to set the profile
    getLocalProfile(function(profileImgSrc, profileName, profileReAuthEmail) {
        //changes in the UI
        $("#profile-img").attr("src",profileImgSrc);
        $("#profile-name").html(profileName);
        $("#reauth-email").html(profileReAuthEmail);
        $("#inputEmail").hide();
        $("#remember").hide();
    });
}

/**
 * function that checks if the browser supports HTML5
 * local storage
 *
 * @returns {boolean}
 */
function supportsHTML5Storage() {
    try {
        return 'localStorage' in window && window['localStorage'] !== null;
    } catch (e) {
        return false;
    }
}

/**
 * Test data. This data will be safe by the web app
 * in the first successful login of a auth user.
 * To Test the scripts, delete the localstorage data
 * and comment this call.
 *
 * @returns {boolean}
 */
function testLocalStorageData() {
    if(!supportsHTML5Storage()) { return false; }
    localStorage.setItem("PROFILE_IMG_SRC", "//lh3.googleusercontent.com/-6V8xOA6M7BA/AAAAAAAAAAI/AAAAAAAAAAA/rzlHcD0KYwo/photo.jpg?sz=120" );
    localStorage.setItem("PROFILE_NAME", "César Izquierdo Tello");
    localStorage.setItem("PROFILE_REAUTH_EMAIL", "oneaccount@gmail.com");
}

function cambiaCodigo(){

  if ($('input:radio[id=codigo0]:checked').val()){
      $('#etiquetacodigo').html('Código de Barra');
      $('#observacionCodigo').prop('readonly', false);
      $('#observacionCodigo').prop('placeholder', 'Código de Barra');
  }
  else
    if ($('input:radio[id=codigo1]:checked').val()){
        $('#etiquetacodigo').html('Observación');
        $('#observacionCodigo').prop('readonly', false);
        $('#observacionCodigo').prop('placeholder', 'Observación');
    } 
    else{
          $('#etiquetacodigo').html('Código de Barra/Observación');
          $('#observacionCodigo').prop('readonly', false);
          $('#observacionCodigo').val('No Aplica');
      }                     
}

function cambiaTipo(){

  if ($('input:radio[id=tipo0]:checked').val()){
      $('#fechaVencimiento').prop('readonly', false);
  }
  else
    if ($('input:radio[id=tipo1]:checked').val()){
        $('#fechaVencimiento').prop('readonly', true);
        $('input[id=fechaVencimiento]').val('');
    } 
}