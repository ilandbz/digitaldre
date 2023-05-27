new Vue({
    el: '#resoluciones',
    data: {
        color: 'rgba(0, 0, 0, 0.71)',
        search: {
            'filter': 1,
            'text_filter': 'Número de la resolucion',
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

        fileName: null,
        fileContent: null,
        

        resolucion: { 
           
            'resoluciontipo_text': '--- Seleccione Opción ---',//SE USA SOLO PARA BUSQUEDA--------------------------
            'resoluciontipo_id': null,
           
            'dependencia_text': '--- Seleccione Opción ---',//SE USA SOLO PARA BUSQUEDA--------------------------
            'dependencia_id': null,
           
            'persona_text': '--- Seleccione Opción ---',
            'persona_id': null,
            'nombrep': null,
            'fecha': null,
            'numero': null,
            'resoluciontipo': null,
            'dependencia': null,
            'asunto': null,
            'visto': null,
            'estado': null,
        },
    resoluciontipos: my_resoluciontipos,
    dependencias: my_dependencias,
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
        Filtrar(text, cod) 
        {
            this.search.text_filter = text;
            this.search.filter = cod;
        },
       
        Buscar(page) 
        {
            this.Load('my_table', 'on', 'Cargando Datos ...'); 

            urlBuscar = 'resoluciones/buscar?page=' + page;
            axios.post(urlBuscar, {
                filter: this.search.filter,
                search: this.search.datos,
            }).then(response => {
                this.Load('my_table', 'off', null);
                this.listRequest = response.data.resoluciones.data;
                console.log(response.data.resoluciones.data);
                this.to_pagination = response.data.resoluciones.to;
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
            
                  
                    this.resolucion.estado = seleccion.x_estado;
                    this.resolucion.resoluciontipo = seleccion.resoluciontipo_id;
                    this.resolucion.dependencia = seleccion.dependencia_id;
                    this.resolucion.numero = seleccion.x_numero;
                    this.resolucion.fecha = seleccion.x_fecha;  
                    this.resolucion.visto = seleccion.x_visto;          
                    this.resolucion.asunto = seleccion.x_asunto;
                    this.resolucion.archivo = seleccion.x_archivo;
                    break;

                case 'delete':
                    this.resolucion.numero = seleccion.x_numero;
                    break;
                case 'loked':
                   /* this.resolucion.persona_id = seleccion.persona_id;
                    this.resolucion.persona_text = seleccion.get_persona.x_nombre;*/
                    this.resolucion.numero = seleccion.x_numero; 
                    this.resolucion.asunto = seleccion.x_asunto;
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

            this.resolucion = {
                'persona_text': '--- Seleccione Opción ---',
                'persona_id': null,
                'dependencia': null,
                'nombrep': null,
                'fecha': null,
                'numero': null,
                'visto': null,
                'resoluciontipo': null,
                'asunto': null,
                'estado': null,
                
            };
           
        },
        Archivo($event) {
            this.fileName = $event.target.files[0].name; 
            this.fileContent = $event.target.files[0];
        },
        Store(form) {
            
            this.Load(form, 'on', 'Guardando Registro ...');

            let formData  = new FormData();
            formData.append('archivo', this.fileContent);
            formData.append('estado', this.resolucion.estado);
            formData.append('resoluciontipo', this.resolucion.resoluciontipo);
            formData.append('dependencia', this.resolucion.dependencia);
            formData.append('numero', this.resolucion.numero);
            formData.append('fecha', this.resolucion.fecha);
            formData.append('visto', this.resolucion.visto);
            formData.append('asunto',  this.resolucion.asunto);
          

            axios.post('resoluciones/store', 
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }

            ).then(response=> {
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
            axios.post('resoluciones/update', {
                id: this.id,
                //persona: this.resolucion.persona_id,
                estado: this.resolucion.estado,
                resoluciontipo: this.resolucion.resoluciontipo,
                dependencia: this.resolucion.dependencia,
                numero: this.resolucion.numero,
                fecha: this.resolucion.fecha,
                asunto: this.resolucion.asunto,
                visto: this.resolucion.visto,
                
                
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
            axios.post('resoluciones/delete', {
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
        Enviar(form) {
            this.Load(form, 'on', 'Enviando Registro ...');

            this.errors = [];
            axios.post('resoluciones/enviar', {
                id: this.id,
                persona: this.resolucion.persona_id,
                //estado: this.resolucion.estado,
                //resoluciontipo: this.resolucion.resoluciontipo,
                numero: this.resolucion.numero,
                //fecha: this.resolucion.fecha,
                asunto: this.resolucion.asunto,
                //archivo: this.resolucion.archivo,
                
                
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

        Autofocus(id) {
            setTimeout(function() {
                $('#'+id).focus();
            }, 300);
        },
        SelecResoluciontipo(value) {//filtrar cargos
            this.resolucion.resoluciontipo_id = value.id;
            this.resolucion.resoluciontipo_text = value.x_resoluciontipos;
        },
        SelecDependencia(value) {//filtrar cargos
            this.resolucion.dependencia_id = value.id;
            this.resolucion.dependencia_text = value.x_nombre;
        },
       SelecPersona(value) {
            this.resolucion.persona_id = value.id;
            this.resolucion.persona_text = value.c_dni+': '+value.x_nombre+'- '+value.x_email;
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
        zeroFill(number, width)
        {
            width -= number.toString().length;
            if (width > 0) {
                return new Array(width + (/\./.test(number) ? 2 : 1)).join('0') + number;
            }
            return number + "";
        },
    }
    
    
});
function myFunctiond() {
    var input, filter, div, a, i, txtValue;
    input = document.getElementById("dependencia_search");
    filter = input.value.toUpperCase();
    div = document.getElementById("myDropdownd");
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


function myFunction() {
    var input, filter, div, a, i, txtValue;
    input = document.getElementById("resoluciontipo_search");
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

