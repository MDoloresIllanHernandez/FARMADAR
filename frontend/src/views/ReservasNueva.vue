<template>
    <div class="bg-white">
        <Navbar />
        <div class="relative isolate px-6 pt-14 lg:px-8">
            <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
                <h1> Nueva Reserva</h1>
                <GenericForm :fields="itemFields" 
                :initialData="existingItemData" 
                submitButtonText="Reservar"
                cancelRoute="Buscador"
                @submit="handleItemSubmit" />
            </div>
        </div>

        <Footer />
    </div>
</template>

<script>
import Navbar from './../components/Navbar.vue';
import Footer from './../components/Footer.vue';
import GenericForm from './../components/GenericForm.vue';

export default {

    components: { Navbar, Footer, GenericForm },
    data() {
        return {
            productId: null,
            farmId: null,

            itemFields: [
                { name: "id_producto", label: "Id del producto", type: "text", readonly: true },
                { name: "id_farm", label: "Id de la farmacia", type: "text", readonly: true },
                { name: "fecha", label: "Fecha", type: "date", error: "*Fecha requerida" },
                { name: "hora_inicio", label: "Hora inicio", type: "time", error: "*Hora requerida" },
                { name: "hora_fin", label: "Hora fin", type: "time", max:"20:00", error: "*Hora requerida" },
                { name: "cantidad", label: "Cantidad", type: "number", error: "*Cantidad requerida" },
                { name: "cliente", label: "Datos del cliente(nombre, teléfono)", type: "texarea", error: "*Datos del cliente requerido" },

            ],
            existingItemData: {
                id_producto: null,
                id_farm: null,
                fecha: null,
                hora_inicio: null,
                hora_fin: null,
                cantidad: null,
                cliente: null

            }
        };
    },
    created() {
        // Obtener los parámetros de la URL
        this.productId = this.$route.params.productId;
        this.farmId = this.$route.params.farmId;

        // Establecer la fecha actual
        const today = new Date().toISOString().split('T')[0];
        this.existingItemData.fecha = today;

        //Establecer la hora actual
        const now = new Date();
        const currentTime = now.toTimeString().split(' ')[0].substring(0, 5);
        this.existingItemData.hora_inicio = currentTime;

        //Establecer la hora final
        this.existingItemData.hora_fin = this.calculateEndTime(now);

        // Asignar los valores de productId y farmId
        this.existingItemData.id_producto = this.productId;
        this.existingItemData.id_farm = this.farmId;
    },
    methods: {
        handleItemSubmit(formData) {
            // Lógica para manejar el envío del formulario de producto
            console.log("Producto enviado:", formData);
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
