new Vue({
    el: '#informacion',
    data: {
        color: 'rgba(0, 0, 0, 0.71)',
        perfil: my_perfil,
        user: {
            'nombres': null,
            'dni': null,
            'telefono': null,
            'email': null,
            'username': null,
            'password': null,
            'password_confirmation': null,
        },
        errors: [],
        modal: {
            'show': false,
            'size': null,
            'method': null,
            'loading': null,
        },
        id: null,
        show_password: false,
        show_rpassword: false,
    },
    created() {
        
    },
    methods: {
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
                        positionClass: "toast-top-right",
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
        
        Modal(size, metodo, id, seleccion) {
            this.modal.show = true;
            this.modal.size = size;
            this.modal.method = metodo;
            this.id = id;
            this.color = 'rgba(236, 120, 0, 0.98)'

            switch (metodo) {
                case 'datos':
                    this.user.nombres = this.perfil.x_nombres;
                    this.user.dni = this.perfil.c_dni;
                    this.user.telefono = this.perfil.c_telefono;
                    this.user.email = this.perfil.x_email;
                    this.user.username = this.perfil.username;
                    break;
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
        },
        Perfil(form) {
            this.Load(form, 'on', 'Guardando Registro ...');

            this.errors = [];

            axios.post('home/perfil', {
                nombres: this.user.nombres,
                dni: this.user.dni,
                telefono: this.user.telefono,
                email: this.user.email,
                tipo: this.user.tipo,
                username: this.user.username,
                password: this.user.password,
                password_confirmation: this.user.password_confirmation,
            }).then(response=> {
                this.Load(form, 'off', null);

                var action = response.data.action;
                var title = response.data.title;
                var message = response.data.message;
                this.Alert(action, title, message);

                if (action == 'success') {
                    $('#formularioModal').modal('hide');
                    this.CloseModal();
                    this.perfil = response.data.user;
                }
            }).catch(error => {
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

        Show(tipo) {
            switch (tipo) {
                case 1:
                    $('#password').attr('type', 'text');
                    this.show_password = true;
                    break;
                    
                default:
                    $('#rpassword').attr('type', 'text');
                    this.show_rpassword = true;
                    break;
            }
        },
        Hide(tipo) {
            switch (tipo) {
                case 1:
                    $('#password').attr('type', 'password');
                    this.show_password = false;
                    break;
            
                default:
                    $('#rpassword').attr('type', 'password');
                    this.show_rpassword = false;
                    break;
            }
        },
        Fecha(date) {
            if (date) {
                var fecha = date.split('-');
                return fecha[2]+'/'+fecha[1]+'/'+fecha[0];
            }
        },
        zeroFill(number, width) {
            width -= number.toString().length;
            if (width > 0) {
                return new Array(width + (/\./.test(number) ? 2 : 1)).join('0') + number;
            }
            return number + "";
        }
    },
    watch: {
        
    }
});