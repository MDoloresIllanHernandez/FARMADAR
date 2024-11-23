<template>
    <div class="bg-white">
        <Navbar />
        <div class="relative isolate px-6 pt-14 lg:px-8">
            <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
                <h1> Nueva Reserva</h1>
                <GenericForm 
                :fields="itemFields" 
                :initialData="existingItemData" 
                submitButtonText="Reservar"
                cancelRoute="Buscador" 
                @submit="handleSubmit" />
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
    data() {
        return {
            existingItemData: {},
           /* dataSelect:{
                dataSelect: [
                    {
                        name: "id_prod",
                        label: "Producto",
                        type: "select",
                        data: [], // Aquí llenaremos los datos dinámicamente
                        error: "*Producto requerido",
                        readonly: true,
                    },
                    {
                        name: "id_farm",
                        label: "Farmacia destino",
                        type: "select",
                        data: [], // Aquí llenaremos los datos dinámicamente
                        error: "*Farmacia requerida",
                        readonly: true,
                    },
                    {
                        name: "farm_origen",
                        label: "Farmacia origen",
                        type: "select",
                        data: [], // Aquí llenaremos los datos dinámicamente
                        error: "*Farmacia origen requerida",
                    },
                ]
            },*/
            itemFields: [
                { name: "id_prod", label: "Producto", type: "text", error: "*Producto requerido", readonly: true },
                { name: "id_farm", label: "Farmacia destino", type: "text", error: "*Farmacia requerida", readonly: true },
                { name: "farm_origen", label: "Farmacia origen", type: "text", error: "*Farmacia origen requerida" },
                { name: "fecha", label: "Fecha", type: "date", error: "*Fecha requerida" },
                { name: "hora_inicio", label: "Hora inicio", type: "time", error: "*Hora requerida" },
                { name: "hora_fin", label: "Hora fin", type: "time", max: "20:00", error: "*Hora requerida" },
                { name: "cantidad", label: "Cantidad", type: "number", error: "*Cantidad requerida" },
                { name: "nombre", label: "Nombre del cliente", type: "text", error: "*Nombre del cliente requerido" },
                { name: "otros_datos", label: "Datos de contacto (teléfono/email)", type: "textarea", error: "*Datos de contacto requeridos" },
                { name: "estado", label: "Estado", type: "text", readonly: true, value: "Pendiente" },

            ],
        };

    },
    created() {
        // Obtener los parámetros de la URL
        this.productId = this.$route.params.productId;
        this.farmId = this.$route.params.farmId;

        // Crear un objeto con los datos de la reserva
        this.existingItemData = {
            id_prod: this.productId,
            id_farm: this.farmId,
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

        //Cargar los select de farmacias y productos
        //this.loadSelectData();
    },
    methods: {
        // Método para cargar los datos de los select de farmacias y productos
        /*async loadSelectData() {
            try {
                   // Obtener todas los datos que necesitamos de la api
                    const responseFarmacias = await apiClient.get('/farmacia');
                    const role = sessionStorage.getItem('role');
                    const idFarm = sessionStorage.getItem('id_farm')
                    const responseProductos =  await apiClient.get('/producto', {
                        params: { role, id_farm: idFarm, source: 'producto' },
                        });
              
                    // Asignar los datos a las variables locales
                    this.dataSelect.dataSelect[0].data = responseProductos.data.productos;
                    this.dataSelect.dataSelect[1].data = responseFarmacias.data.farmacias;
                    this.dataSelect.dataSelect[2].data = responseFarmacias.data.farmacias;
              
            } catch (error) {
                console.error('Error al cargar los datos de los select:', error);
            }
        },*/
        // Método para manejar el envío del formulario y añadir la reserva
        async handleSubmit(formData) {
           if (formData.isTrusted) {
                return;
            }
            try {
                const response = await apiClient.post('/reserva', formData)
                if (response.data.result === 'ok') {
                    this.$router.push('/Reservas');
                }
            } catch (error) {
                console.error('Error al añadir la reserva:', error);
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
        }


    }

};

</script>
