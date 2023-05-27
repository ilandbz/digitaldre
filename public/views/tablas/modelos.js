new Vue({
    el: '#modelos',
    data: {
        color: 'rgba(0, 0, 0, 0.71)',
        search: {
            'filter': 1,
            'text_filter': 'Nombre del modelo',
            'datos': ''
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

        modelo: {
            'bien_text': '--- Seleccione Opción ---',
            'bien_id': null,
            'nombre': null,
            'persona_text': '--- Seleccione Opción ---',
            'persona_id': null,
            'nombrep': null,
        },
        bienes: my_bienes,
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

            urlBuscar = 'modelos/buscar?page=' + page;
            axios.post(urlBuscar, {
                filter: this.search.filter,
                search: this.search.datos,
            }).then(response => {
                this.Load('my_table', 'off', null);
                this.listRequest = response.data.modelos.data;
                this.to_pagination = response.data.modelos.to;
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
                    break;

                case 'edit':
                    this.modelo.bien_id = seleccion.bien_id;
                    this.modelo.bien_text = seleccion.get_bien.x_nombre;
                    this.modelo.persona_id = seleccion.persona_id;
                    this.modelo.persona_text = seleccion.get_persona.x_nombre;
                    this.modelo.nombre = seleccion.x_nombre;
                    break;

                case 'delete':
                    this.modelo.nombre = seleccion.x_nombre;
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

            this.modelo = {
                'bien_text': '--- Seleccione Opción ---',
                'bien_id': null,
                'nombre': null,
                'persona_text': '--- Seleccione Opción ---',
                'persona_id': null,
                'nombrep': null,
            };

            this.dependencias = [];
        },
        Store(form) {
            this.Load(form, 'on', 'Guardando Registro ...');

            this.errors = [];
            axios.post('modelos/store', {
                bien: this.modelo.bien_id,
                persona: this.modelo.persona_id,
                nombre: this.modelo.nombre
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
            axios.post('modelos/update', {
                id: this.id,
                bien: this.modelo.bien_id,
                persona: this.modelo.persona_id,
                nombre: this.modelo.nombre
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
            axios.post('modelos/delete', {
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

        Autofocus(id) {
            setTimeout(function() {
                $('#'+id).focus();
            }, 300);
        },
        SelecBien(value) {
            this.modelo.bien_id = value.id;
            this.modelo.bien_text = value.c_codigo+': '+value.x_nombre;
        },
        SelecPersona(value) {
            this.modelo.persona_id = value.id;
            this.modelo.persona_text = value.c_dni+': '+value.x_nombre;
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
        zeroFill(number, width) {
            width -= number.toString().length;
            if (width > 0) {
                return new Array(width + (/\./.test(number) ? 2 : 1)).join('0') + number;
            }
            return number + "";
        },
    }
});

function myFunction() {
    var input, filter, div, a, i, txtValue;
    input = document.getElementById("bien_search");
    filter = input.value.toUpperCase();
    div = document.getElementById("myDropdown");
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