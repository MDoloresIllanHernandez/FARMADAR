<template>
    <div class="bg-white">
        <Navbar />
        <div class="relative isolate px-6 pt-14 lg:px-8">
            <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
                <h1> Nueva Reserva</h1>
                <GenericForm 
                    :calledFrom="'reservasNueva'"
                    :productSelect="productoSeleccionado"
                    :fields="itemFields" 
                    :initialData="existingItemData"
                    :dataSelect="computedDataSelect" 
                    submitButtonText="Reservar"
                    cancelRoute="Buscador"
                    @submit="handleSubmit"
                    @errorForm="mostrarError" 
                />
            </div>
        </div>
        <Footer />
    </div>
</template>

<script>
import Navbar from './../components/Navbar.vue';
import Footer from './../components/Footer.vue';
import GenericForm from './../components/GenericForm.vue';
import apiClient from '../scripts/axios.js';


export default {
    components: { Navbar, Footer, GenericForm },
    props: {
        farmacias: {
            type: Array,
            required: true,
        },
        productos: {
            type: Array,
            required: true,
        },
    },
  
     data() {
        const farm_origen = sessionStorage.getItem('id_farm');
        const userRole = sessionStorage.getItem('role');
       
        return {
            existingItemData: {},
            cantidadMaxima: 0,
            itemFields: [
                { name: "id_prod", label: "Producto", type: "text", error: "*Producto requerido", readonly: true },
                { name: "fecha", label: "Fecha", type: "date", error: "*Fecha requerida" },
                { name: "hora_inicio", label: "Hora inicio", type: "time", error: "*Hora requerida" },
                { name: "hora_fin", label: "Hora fin", type: "time", max: "20:00", error: "*Hora requerida" },
                { name: "cantidad", label: "Cantidad", type: "number", error: `*Cantidad requerida (máximo no definido)`, max: 0 },
                { name: "nombre", label: "Nombre del cliente", type: "text", error: "*Nombre del cliente requerido" },
                { name: "otros_datos", label: "Datos de contacto (teléfono/email)", type: "textarea", error: "*Datos de contacto requeridos" },
                { name: "estado", label: "Estado", type: "text", readonly: true, value: "Pendiente" },

            ],
            
            requiredFields: ["id_prod", "id_farm", "farm_origen", "fecha", "hora_inicio", "hora_fin", "cantidad", "nombre", "otros_datos", "estado"],
        };

      },
    computed: {
    productoSeleccionado() {
        return this.productos.find(producto => producto.id === this.$route.params.productId);
        
    },
    farmaciasOrigen() {
        const idFarm = sessionStorage.getItem('id_farm');
        return this.farmacias.filter(it=>it.id!=this.$route.params.farmId).map(farmacia => ({
            ...farmacia,
            selected: farmacia.id === idFarm,
        }));
    },
    farmaciasDestino() {
        return this.farmacias.map(farmacia => ({
            ...farmacia,
            selected: farmacia.id === this.$route.params.farmId,
        }));
    },
    computedDataSelect() {
            return [
                {
                    name: "farm_origen",
                    label: "Farmacia origen",
                    type: "select",
                    error: "*Farmacia requerida",
                    readonly: sessionStorage.getItem('role')=='superadmin'?false:true,
                    data: this.farmaciasOrigen,
                },
                {
                    name: "id_farm",
                    label: "Farmacia destino",
                    type: "select",
                    error: "*Farmacia requerida",
                    readonly: true,
                    data: this.farmaciasDestino,
                },
            ];
        },
    },

    created: async function () {
        //Ajustar el campo farm_origen según el rol
        if (this.role !== 'superadmin') {
            this.itemFields[2].readonly = true;
        }
        // Obtener los parámetros de la URL
        this.productId = this.$route.params.productId;
        this.farmId = this.$route.params.farmId;
         
        // Crear un objeto con los datos de la reserva
        this.existingItemData = {
            id_prod: this.productId,
            id_farm: this.farmId,
            farm_origen: this.farm_origen,
            estado: 'Pendiente'
        };
        // Establecer la fecha actual
        const today = new Date().toISOString().split('T')[0];
        this.existingItemData.fecha = today;

        //Establecer la hora actual
        const now = new Date();
        const currentTime = now.toTimeString().split(' ')[0].substring(0, 5);
        this.existingItemData.hora_inicio = currentTime;

        //Establecer la hora final
        this.existingItemData.hora_fin = this.calculateEndTime(now);

        // Obtener la cantidad máxima en stock (ejemplo dinámico)
        try {
            this.cantidadMaxima = await this.getStock(); // Espera a que se resuelva la promesa

            // Actualizar dinámicamente el campo "cantidad"
            const cantidadField = this.itemFields.find(field => field.name === "cantidad");
            if (cantidadField) {
                cantidadField.max = this.cantidadMaxima; // Actualiza el límite máximo
                cantidadField.error = `*Cantidad requerida (máximo: ${this.cantidadMaxima})`; // Actualiza el mensaje de error
            }
        } catch (error) {
            console.error("Error al obtener la cantidad máxima en stock:", error);
        }
        
    },
    methods: {
         filterFarmacias(role, userFarm){

            if(role === 'admin' || role === 'usu'){
                return this.farmacias.filter(farmacia => farmacia.id === userFarm);
            }
            return this.farmacias;
    },
        // Método para mostrar un error
        mostrarError(errorField) {
            this.$swal.fire({
                icon: "error",
                title: `El campo ${errorField} es obligatorio`,
                showConfirmButton: true,
            });
        },
        // Método para buscar farmacias
        async searchFarmacias() {
        try {
            const pharmaciesResponse = await apiClient.get('/farmacia');
            if (pharmaciesResponse.data.result === 'ok' && pharmaciesResponse.data.farmacias) {
            this.farmacias = pharmaciesResponse.data.farmacias;
            }
        } catch (error) {
            console.error('Error al obtener las farmacias:', error);
        }
        },
        // Método para manejar el envío del formulario y añadir la reserva
        async handleSubmit(formData) {
            if (formData.isTrusted) {
                return;
            }
            let error = false;
            let errorField = "";
            this.requiredFields.forEach((field) => {
                if (formData[field] == null || (typeof(formData[field])!='number' && formData[field].trim() === "")) {
                error = true;
                errorField=field
                }
            });
            if (error) {
                this.$swal.fire({
                    icon: "error",
                    title: `El campo ${errorField} es obligatorio`,
                    showConfirmButton: true,
                    });
                return;
            }
            try {
                // Realizar la reserva
                const response = await apiClient.post('/reserva', formData);

                if (response.data.result === 'ok') {
                    // Llamar al endpoint PUT para actualizar el stock
                    const stockResponse = await apiClient.put('/stock', {
                        id: formData.id_prod,
                        id_farm: formData.id_farm,
                        newStock: this.cantidadMaxima - formData.cantidad // Stock actualizado
                    });

                    if (stockResponse.data.result === 'ok') {
                        this.$swal.fire({
                            icon: 'success',
                            title: 'Reserva realizada correctamente',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        this.$router.push('/Reservas');
                    } else {
                        throw new Error('No se pudo actualizar el stock.');
                    }
                } else {
                    throw new Error(response.data.message);
                }
            } catch (error) {
                this.$swal.fire({
                    icon: 'error',
                    text: `${error.response?.data?.details}`,
                    showConfirmButton: true,
                });
            }
        },

        // Método para calcular la hora final, 2 horas después de la hora de inicio y antes de las 20:00
        calculateEndTime(startTime) {
            // Crear una nueva fecha con la hora de inicio
            const endTime = new Date(startTime);
            // Añadir 2 horas a la hora de inicio
            endTime.setHours(endTime.getHours() + 2);

            // Si la hora final es después de las 20:00, establecerla a las 20:00
            if (endTime.getHours() > 20 || (endTime.getHours() === 20 && endTime.getMinutes() > 0)) {
                endTime.setHours(20, 0, 0, 0);
            }
            // Devolver la hora final en formato HH:MM
            return endTime.toTimeString().split(' ')[0].substring(0, 5);
        },

        //Método para traer el stock de un producto en una farmacia
        async getStock() {
            try {
                const response = await apiClient.get(`/stock?id=${this.productId}&id_farm=${this.farmId}`);

                if (response.data.result === 'ok') {
                    this.stock = response.data.stock;
                    return this.stock;
                }
            } catch (error) {
                console.error('Error al obtener el stock:', error);
                return 0;
            }
        }


    }

};

</script>
