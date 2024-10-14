<script setup >
import { ref } from 'vue';
import { useRouter } from "vue-router";
</script>

<template>
    <div class="row hidden">
        <div class="col-lg-12">
            <div class="flex justify-center space-x-2 mr-32">
                <span>
                    <img src="../../images/logo_cenpri.png" class="w-32" alt="">
                </span>
                <span class="text-lg mt-1 font-bold" >CENTRAL NEGROS POWER RELIABILITY INC.</span>
            </div>
            <div class="text-center">
                <p class="leading-tight text-sm m-0 ">Office: 88 Corner Rizal-Mabini Sts., Bacolod City</p>
                <p class="leading-tight text-sm m-0 ">Plant Site: Purok San Jose, Barangay Calumangan, Bago City</p>
            </div>
            <div class="flex justify-center space-x-5">
                <p class="leading-tight text-sm mb-0">TIN No. 008-691-287-000</p>
                <p class="leading-tight text-sm mb-0">Tel. No.: (034) 435-1932/476-7382</p>
                <p class="leading-tight text-sm mb-0">Telefax: (034) 435-1932</p>
            </div>
        </div>
    </div>
    
    <slot/>
</template>