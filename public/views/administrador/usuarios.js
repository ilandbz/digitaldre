new Vue({
    el: '#usuarios',
    data: {
        color: 'rgba(0, 0, 0, 0.71)',
        search: {
            'filter': 1,
            'text_filter': 'Datos del Trabajador',
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

        user: {
            'sede': '',
            'dependencia': '',
            'rol': '',
            'nombres': null,
            'dni': null,
            'email': null,
            'telefono': null,
            'username': null,
            'password': null,
        },
        show_password: false,

        user_menus: [],
        show_submenu: 0,
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

            urlBuscar = 'usuarios/buscar?page=' + page;
            axios.post(urlBuscar, {
                filter: this.search.filter,
                search: this.search.datos,
            }).then(response => {
                this.Load('my_table', 'off', null);
                this.listRequest = response.data.usuarios.data;
                this.to_pagination = response.data.usuarios.to;
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
                    this.user.sede = seleccion.sede_id;
                    //this.user.dependencia = seleccion.dependencia_id;
                    this.user.rol = seleccion.rol_id;
                    this.user.nombres = seleccion.x_nombres;
                    this.user.dni = seleccion.c_dni;
                    this.user.email = seleccion.x_email;
                    this.user.telefono = seleccion.x_telefono;
                    this.user.username = seleccion.username;

                    var self = this;
                    setTimeout(function() {
                        CacheItems();

                        self.FilterDependencias(seleccion.dependencia_id);
                    }, 300);
                    break;

                case 'delete':
                    this.user.nombres = seleccion.x_nombres;
                    break;
                
                case 'activar':
                    this.user.nombres = seleccion.x_nombres;
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

            this.user = {
                'sede': '',
                'dependencia': '',
                'rol': '',
                'nombres': null,
                'dni': null,
                'email': null,
                'telefono': null,
                'username': null,
                'password': null,
            }
            this.show_password = false;

            this.user_menus = [];
            this.show_submenu = 0;
        },
        Store(form) {
            this.Load(form, 'on', 'Guardando Registro ...');

            this.errors = [];
            axios.post('usuarios/store', {
                sede: this.user.sede,
                dependencia: this.user.dependencia,
                rol: this.user.rol,
                nombres: this.user.nombres,
                dni: this.user.dni,
                email: this.user.email,
                telefono: this.user.telefono,
                username: this.user.username,
                password: this.user.password,
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
            axios.post('usuarios/update', {
                id: this.id,
                sede: this.user.sede,
                dependencia: this.user.dependencia,
                rol: this.user.rol,
                nombres: this.user.nombres,
                dni: this.user.dni,
                email: this.user.email,
                telefono: this.user.telefono,
                username: this.user.username,
                password: this.user.password,
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
            axios.post('usuarios/delete', {
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
        Activar(form) {
            this.Load(form, 'on', 'Activando Cuenta ...');

            this.errors = [];
            axios.post('usuarios/activar', {
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

        Tipo(num) {
            switch (num) {
                case 'A':
                    return 'ADMINISTRADOR';
                    break;

                case 'O':
                    return 'OPERADOR';
                    break;
            
                default:
                    return 'CONSULTAS';
                    break;
            }
        },
        Show(tipo) {
            $('#password').attr('type', 'text');
            this.show_password = true;
        },
        Hide(tipo) {
            $('#password').attr('type', 'password');
            this.show_password = false;
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

        FilterDependencias(val) {
            this.user.dependencia = '';
            var input = ' ';
            if (this.user.sede) {
                var data = $("#sede option:selected").text();
                var texto = data.split(': ');

                input = texto[0];
            }
            if (val > 0) {
                this.user.dependencia = val;
            }

            FilterItems(input);
        }
    }
});

var ddlText, ddlValue, ddl;
function CacheItems() {
    ddlText = new Array();
    ddlValue = new Array();
    ddl = document.getElementById("dependencia");
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