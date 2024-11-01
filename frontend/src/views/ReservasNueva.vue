<template>
    <div class="bg-white">
        <Navbar />
        <div class="relative isolate px-6 pt-14 lg:px-8">
            <div class="mx-auto max-w-6xl py-32 sm:py-32 lg:py-32">
                <h1> Nueva Reserva</h1>
                <p>Product ID: {{ productId }}</p>
                <p>Farm ID: {{ farmId }}</p>
            </div>
        </div>

        <Footer />
    </div>
</template>

<script>
import Navbar from './../components/Navbar.vue';
import Footer from './../components/Footer.vue';
export default {
   
    data() {
        return {
            productId: null,
            farmId: null
        };
    },
    created() {
        this.productId = this.$route.params.productId;
        this.farmId = this.$route.params.farmId;
  },
    components: {
        Navbar,
        Footer
    }

}

</script>

