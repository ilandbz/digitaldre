new Vue({
    el: '#trabajadores',
    data: {
        color: 'rgba(0, 0, 0, 0.71)',
        search: {
            'filter': 1,
            'text_filter': 'Datos del trabajador',
            'datos': null
        },
        send: '',
        page: 1,

        listRequest: [],
        pagination: {
            'total': 0,
            'current_page': 0,
            'per_page': 0,
            'last_page': 0,
            'from': 0,
            'to': 0,
        },
        to_pagination: 0,

        modal: {
            'show': false,
            'size': null,
            'method': null,
            'loading': null,
        },
        id: null,
        seleccion: [],
        errors: [],
        areas:[],
        trabajador: { //SE USA SOLO PARA BUSQUEDA--------------------------
           
            'cargo_text': '--- Seleccione Opción ---',
            'cargo_id': null,
            //'cargo': '',
            //'nombre': null,
            'direccion': '',
            'area': '',
            'persona_text': '--- Seleccione Opción ---',
            'persona_id': null,
            'nombrep': null,
            /*'dni': null,
          
            'telefono': null,
            'telefono2': null,
            'email': null,
            'email2': null,
            'tipopersonal': null,*/
           
        },
    cargos: my_cargos,
    personas: my_personas,
    },
    created() {
        var self = this;
        $(document).ready(function() {
            self.Buscar();
        });
    },
    methods: {
        changePage(page) {
            this.page = page;
            this.pagination.current_page = page;
            this.Buscar(page);
        },
        Load(id, show, text) {
            if (show == 'on') {
                $("#"+id).busyLoad("show", { 
                    spinner: "accordion",
                    text: text,
                    background: this.color
                });
            } else {
                $("#"+id).busyLoad("hide");
                $('.busy-load-container').hide();
            }
        },
        Alert(action, title, message) {
            switch (action) {
                case 'success':
                    toastr.success(message, title, {
                        positionClass: "toast-bottom-right",
                        timeOut: 5e3,
                        closeButton: !0,
                        debug: !1,
                        newestOnTop: !0,
                        progressBar: !1,
                        preventDuplicates: !0,
                        onclick: null,
                        showDuration: "300",
                        hideDuration: "500",
                        extendedTimeOut: "1000",
                        showEasing: "swing",
                        hideEasing: "linear",
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut",
                        tapToDismiss: !1
                    });
                    break;

                case 'error':
                    toastr.error(message, title, {
                        positionClass: "toast-bottom-right",
                        timeOut: 5e3,
                        closeButton: !0,
                        debug: !1,
                        newestOnTop: !0,
                        progressBar: !1,
                        preventDuplicates: !0,
                        onclick: null,
                        showDuration: "300",
                        hideDuration: "1000",
                        extendedTimeOut: "1000",
                        showEasing: "swing",
                        hideEasing: "linear",
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut",
                        tapToDismiss: !1
                    });
                    break;
            
                default:
                    toastr.warning(message, title, {
                        positionClass: "toast-bottom-right",
                        timeOut: 5e3,
                        closeButton: !0,
                        debug: !1,
                        newestOnTop: !0,
                        progressBar: !1,
                        preventDuplicates: !0,
                        onclick: null,
                        showDuration: "300",
                        hideDuration: "1000",
                        extendedTimeOut: "1000",
                        showEasing: "swing",
                        hideEasing: "linear",
                        showMethod: "fadeIn",
                        hideMethod: "fadeOut",
                        tapToDismiss: !1
                    });
                    break;
            }
        },
        Filtrar(text, cod) {
            this.search.text_filter = text;
            this.search.filter = cod;
        },
        Buscar(page) {
            this.Load('my_table', 'on', 'Cargando Datos ...'); 

            urlBuscar = 'trabajadores/buscar?page=' + page;
            axios.post(urlBuscar, {
                filter: this.search.filter,
                search: this.search.datos,
            }).then(response => {
                this.Load('my_table', 'off', null);
                this.listRequest = response.data.trabajadores.data;
                this.to_pagination = response.data.trabajadores.to;
                this.pagination = response.data.pagination;
            }).catch(error => {
                console.log(error)
                this.Load('my_table', 'off', null);

                var action = 'error';
                var title = 'Ops error !!';
                var message = 'No se pudo conectar con el servidor, por favor actualice la página.';

                this.Alert(action, title, message);
            });

        },
        Modal(size, metodo, id, seleccion) {
            this.modal.show = true;
            this.modal.size = size;
            this.modal.method = metodo;
            this.id = id;
            this.color = 'rgba(236, 120, 0, 0.98)'

            switch (metodo) {                
                case 'create':
                    setTimeout(function() {
                        CacheItems();
                    }, 1000);    
                break;

                case 'edit':
                    this.areas=my_areas; //carga areas al editar un registro
                    //this.trabajador.nombre = seleccion.x_nombres;
                    this.trabajador.persona_id = seleccion.persona_id;
                    this.trabajador.persona_text = seleccion.get_persona.x_nombre;
                    //this.trabajador.dni = seleccion.c_dni;
                    //this.trabajador.email = seleccion.x_email;
                    this.trabajador.email2 = seleccion.x_email2;
                    //this.trabajador.telefono = seleccion.x_telefono;
                    this.trabajador.telefono2 = seleccion.x_telefono2;
                    this.trabajador.cargo = seleccion.cargo_id;
                    this.trabajador.unidad = seleccion.unidad_id;
                    this.trabajador.direccion = seleccion.direccion_id;
                    this.trabajador.area = seleccion.area_id;
                    this.trabajador.tipopersonal = seleccion.x_tipopersonal;
                  
                    var self = this;
                    setTimeout(function() {
                        CacheItems();

                        self.FilterAreas(seleccion.area_id);
                    }, 300);
                    break;

                case 'delete':
                    this.trabajador.nombre = seleccion.x_nombres;
                    break;
                    
                default:
                    console.log(metodo);
            }

        },
        CloseModal() {
            this.color = 'rgba(0, 0, 0, 0.71)';
            this.modal = {
                'show': false,
                'size': null,
                'method': null,
                'loading': null,
            };
            this.id = null;
            this.seleccion = [];
            this.errors = [];

            this.trabajador = {
                'persona_text': '--- Seleccione Opción ---',
                'persona_id': null,
                'nombrep': null,
               
                'direccion':'',
                'area':'',
                'cargo': '',
                'unidad':'',
               
                //'dni': null,
                //'nombre': null,
               // 'telefono': null,
                'telefono2': null,
               // 'email': null,
                'email2': null,
                'tipopersonal': null,
            };

            this.dependencias = [];
           
        },
        Store(form) {
            this.Load(form, 'on', 'Guardando Registro ...');

            this.errors = [];
            axios.post('trabajadores/store', {
               // nombre: this.trabajador.nombre,
                persona: this.trabajador.persona_id,
               // dni: this.trabajador.dni,
               //  telefono: this.trabajador.telefono,
                telefono2: this.trabajador.telefono2,
               //  email: this.trabajador.email,
                email2: this.trabajador.email2,
                cargo: this.trabajador.cargo,
                unidad: this.trabajador.unidad,
                direccion: this.trabajador.direccion,
                area: this.trabajador.area,
                tipopersonal: this.trabajador.tipopersonal,

            }).then(response=> {
                this.Load(form, 'off', null);

                var action = response.data.action;
                var title = response.data.title;
                var message = response.data.message;
                this.Alert(action, title, message);

                if (action == 'success') {
                    $('#formularioModal').modal('hide');
                    this.CloseModal();
                    var self = this;
                    setTimeout( function() {
                        self.Buscar(self.page);
                    }, 500);
                }
                console.log(response.data.error);
            }).catch(error => {
                console.log(error)
                this.Load(form, 'off', null);

                if (error.response.status == 422) {
                    this.errors = error.response.data.errors;
                } else {
                    var action = 'error';
                    var title = 'Ops error !!';
                    var message = 'No se pudo conectar con el servidor, por favor actualice la página.';

                    this.Alert(action, title, message);
                }
            });
        },
        Update(form) {
            this.Load(form, 'on', 'Actualizando Registro ...');

            this.errors = [];
            axios.post('trabajadores/update', {
                id: this.id,
                //nombre: this.trabajador.nombre,
                //dni: this.trabajador.dni,
                //telefono: this.trabajador.telefono,
                persona: this.trabajador.persona_id,
                telefono2: this.trabajador.telefono2,
                //email: this.trabajador.email,
                email2: this.trabajador.email2,
                cargo: this.trabajador.cargo,
                unidad: this.trabajador.unidad,
                direccion: this.trabajador.direccion,
                area: this.trabajador.area,
                tipopersonal: this.trabajador.tipopersonal,
                
            }).then(response=> {
                this.Load(form, 'off', null);

                var action = response.data.action;
                var title = response.data.title;
                var message = response.data.message;
                this.Alert(action, title, message);

                if (action == 'success') {
                    $('#formularioModal').modal('hide');
                    this.CloseModal();
                    var self = this;
                    setTimeout( function() {
                        self.Buscar(self.page);
                    }, 500);
                }
                console.log(response.data.error);
            }).catch(error => {
                console.log(error)
                this.Load(form, 'off', null);

                if (error.response.status == 422) {
                    this.errors = error.response.data.errors;
                } else {
                    var action = 'error';
                    var title = 'Ops error !!';
                    var message = 'No se pudo conectar con el servidor, por favor actualice la página.';

                    this.Alert(action, title, message);
                }
            });
        },
        Delete(form) {
            this.Load(form, 'on', 'Eliminando Registro ...');

            this.errors = [];
            axios.post('trabajadores/delete', {
                id: this.id,
            }).then(response=> {
                this.Load(form, 'off', null);

                var action = response.data.action;
                var title = response.data.title;
                var message = response.data.message;
                this.Alert(action, title, message);

                if (action == 'success') {
                    $('#formularioModal').modal('hide');
                    this.CloseModal();
                    var self = this;
                    setTimeout( function() {
                        self.Buscar(self.page);
                    }, 500);
                }
                console.log(response.data.error);
            }).catch(error => {
                console.log(error)
                this.Load(form, 'off', null);

                var action = 'error';
                var title = 'Ops error !!';
                var message = 'No se pudo conectar con el servidor, por favor actualice la página.';

                this.Alert(action, title, message);
            });
        },
        buscararea() { //busca areas al crear un registro
            
            this.errors = [];
            axios.post('trabajadores/buscararea', {
                id: this.trabajador.direccion,
            }).then(response=> {
               
                this.areas=response.data;
            }).catch(error => {
                console.log(error)
                
                var action = 'error';
                var title = 'Ops error !!';
                var message = 'No se pudo conectar con el servidor, por favor actualice la página.';

                this.Alert(action, title, message);
            });
        },

      

        Autofocus(id) {
            setTimeout(function() {
                $('#'+id).focus();
            }, 300);
        },
        SelecCargo(value) {//filtrar cargos
            this.trabajador.cargo_id = value.id;
            this.trabajador.cargo_text = value.x_cargos;
        },
        SelecPersona(value) {
            this.trabajador.persona_id = value.id;
            this.trabajador.persona_text = value.c_dni+': '+value.x_nombre;
        },
        Fecha(doc) {
            let date = new Date(doc);
            let day = this.zeroFill(date.getDate(), 2);
            let month = date.getMonth() + 1;
            let year = date.getFullYear();
            let hour = date.getHours();
            let min = this.zeroFill(date.getMinutes(), 2);

            hour = this.zeroFill(hour, 2);

            if (month < 10) {
                return (`${day}/0${month}/${year}`)
            } else {
                return (`${day}/${month}/${year}`)
            }
        },
       /* zeroFill(number, width)
        {
            width -= number.toString().length;
            if (width > 0) {
                return new Array(width + (/\./.test(number) ? 2 : 1)).join('0') + number;
            }
            return number + "";
        },
        FilterAreas(val) 
        {
            this.trabajador.area = '';
            var input = ' ';
            if (this.trabajador.direccion) {
                var data = $("#direccion option:selected").text();
                var texto = data.split(': ');

                input = texto[0];
            }
            
            FilterItems(input);
        }*/
    }
    
});

/*var ddlText, ddlValue, ddl;
function CacheItems() {
    ddlText = new Array();
    ddlValue = new Array();
    ddl = document.getElementById("area");
    for (var i = 0; i < ddl.options.length; i++) {
        ddlText[ddlText.length] = ddl.options[i].text;
        ddlValue[ddlValue.length] = ddl.options[i].value;
    }
}

function FilterItems(value) {
    ddl.options.length = 0;
    for (var i = 0; i < ddlText.length; i++) {
        if (i == 0) {
            AddItem('--- Seleccione Opción ---', '');
        }

        if (ddlText[i].toLowerCase().indexOf(value) != -1 || ddlText[i].toUpperCase().indexOf(value) != -1) {
            AddItem(ddlText[i], ddlValue[i]);
        }
    }
}
function AddItem(text, value) {
    var opt = document.createElement("option");
    opt.text = text;
    opt.value = value;
    ddl.options.add(opt);
}
*/
function myFunction() {
    var input, filter, div, a, i, txtValue;
    input = document.getElementById("cargo_search");
    filter = input.value.toUpperCase();
    div = document.getElementById("myDropdown");
    a = div.getElementsByTagName("a");
    for (i = 0; i < a.length; i++) 
    {
      txtValue = a[i].textContent || a[i].innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) 
      {
        a[i].style.display = "";
      } 
      else 
      {
        a[i].style.display = "none";
      }
    }
}
function myFunctionp() {
    var input, filter, div, a, i, txtValue;
    input = document.getElementById("persona_search");
    filter = input.value.toUpperCase();
    div = document.getElementById("myDropdownp");
    a = div.getElementsByTagName("a");
    for (i = 0; i < a.length; i++) {
      txtValue = a[i].textContent || a[i].innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        a[i].style.display = "";
      } else {
        a[i].style.display = "none";
      }
    }
}

