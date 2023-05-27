new Vue({
    el: '#areasu',
    data: {
        color: 'rgba(0, 0, 0, 0.71)',
        search: {
            'filter': 1,
            'text_filter': 'Datos del Area',
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

        areau: {
            //'direccion': '',
            'direccion_text': '--- Seleccione Opción ---',
            'direccion_id': null,
            'nombre': null,
          
        },
        direcciones: my_direcciones,
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

            urlBuscar = 'areasu/buscar?page=' + page;
            axios.post(urlBuscar, {
                filter: this.search.filter,
                search: this.search.datos,
            }).then(response => {
                this.Load('my_table', 'off', null);
                this.listRequest = response.data.areasu.data;
                this.to_pagination = response.data.areasu.to;
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
                    this.areau.direccion = seleccion.direccion_id;
                    this.areau.nombre = seleccion.x_areas;                 
                    break;

                case 'delete':
                    this.areau.nombre = seleccion.x_areas;
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

            this.areau = {
                'direccion': '',
                'nombre': null,
               
            };

            this.dependencias = [];
        },
        Store(form) {
            this.Load(form, 'on', 'Guardando Registro ...');

            this.errors = [];
            axios.post('areasu/store', {
                direccion: this.areau.direccion,
                nombre: this.areau.nombre,
               
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
            axios.post('areasu/update', {
                id: this.id,
                direccion: this.areau.direccion,
                //clase: this.bien.clase,
                nombre: this.areau.nombre,
                /*codigo: this.bien.codigo,
                resolucion: this.bien.resolucion,*/
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
            axios.post('areasu/delete', {
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

        SelecDireccion(value) {
            this.areau.direccion_id = value.id;
            this.areau.direccion_text = value.x_direcciones;
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
    input = document.getElementById("direccion_search");
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
