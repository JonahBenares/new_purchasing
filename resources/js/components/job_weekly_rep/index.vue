<script setup>
	import navigation from '@/layouts/navigation_hidden.vue';
	import{ Bars3Icon, PencilIcon, XMarkIcon} from '@heroicons/vue/24/solid'
	import{ArrowUpOnSquareIcon} from '@heroicons/vue/24/outline'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
    import moment from 'moment'
	
    const tableWeekly = ref(false)
    const filterDet = ref(false)
    const filters = ref(true)
	const hideValue = ref(true)
	const showTable = () => {
		tableWeekly.value = !tableWeekly.value
		filterDet.value = !filterDet.value
		filters.value = !hideValue.value
	}
    const removeFilter = () => {
		tableWeekly.value = !hideValue.value
		filterDet.value = !hideValue.value
		filters.value = !filters.value
	}
    const pr_det = ref(false)
    const pr_det2 = ref(false)
</script>

<template>
	<navigation>
        
        <div class="sticky  top-0 left-0 z-50 bg-white p-3 shadow-md">
            <div class="row  ">
                <div class="col-lg-12">
                    <div class=" flex justify-between  px-2 ">
                        <div class="flex">
                            <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JOR WEEKLY RECOMMENDATION <small>List</small></h3>
                        </div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">JOR Weekly Recom</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="hidden" :class="{ show:filters }">
                <div class="row mt-3">							
                    <div class="col-lg-8 offset-lg-2">
                        <div class="form-group">
                        <!-- <label class="text-gray-500 " for="">Filter</label> -->
                        <input type="file" name="img[]" class="file-upload-default">
                        <div class="input-group">
                            <select class="form-control !w-14">
                                <option value="">--Select Point Person--</option>
                                <option value="">Prency Marie</option>
                                <option value="">Kervic Grace</option>
                                <option value="">Maylen Joy</option>
                            </select>
                            <input type="text" class="form-control" onfocus="(this.type='date')" placeholder="Date From">
                            <input type="text" class="form-control" onfocus="(this.type='date')" placeholder="Date To">
                            <select class="form-control !w-14">
                                <option value="">--Select Status--</option>
                            </select>
                            <span class="input-group-append">
                                <button class="btn btn-primary" type="button" @click="showTable()">Select</button>
                            </span>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <hr class="border-dashed"> -->
            <div class="hidden" :class="{ show:filterDet }">
                <br>
                <div class="flex justify-between space-x-2  px-2 "  >
                    <div>
                        <p class="font-bold m-0 leading-none uppercase text-base leading-none">
                            <span >Sept 2 2024 - Sept 3 2024 -  </span>
                            <span class="badge bg-success text-white py-1 rounded text-xs">Status 1</span>
                        </p> 
                        <p for="" class="m-0 text-sm">Kervic Grace Uy</p>
                    </div>
                    <div class="space-x-2">
                        <button class="btn btn-danger btn-sm" @click="removeFilter()">Remove Filter</button>
                        <button class="btn btn-primary btn-sm">Export</button>
                    </div>
                </div>
            </div>
        </div>
		<div class="row">
            <div class="col-lg-12 stretch-card">
                
                <div class="pt-0 hidden mb-10"  :class="{ show:tableWeekly }">
                    <div class="">
                        <table class="table-bordered text-xs" width="150%">
                            <thead>
                                <tr class="text-xs bg-gray-100 font-bold">
                                    <td class="p-1" width="8%">Point Person</td>	
                                    <td class="p-1" width="10%">Purpose</td>
                                    <td class="p-1" width="10%">End Use</td>
                                    <td class="p-1" width="5%">JOR No.</td>
                                    <td class="p-1" width="5%">Requestor</td>
                                    <td class="p-1 text-center" width="5%">JOR Qty</td>
                                    <td class="p-1 text-center" width="4%">Recom Qty</td>
                                    <td class="p-1 text-center" width="3%">UOM</td>
                                    <td class="p-1" width="10%">Description</td>
                                    <td class="p-1" width="3%">Status</td>
                                    <td class="p-1">Supplier</td>
                                    <td class="p-1" width="6%">Payment Term</td>
                                    <td class="p-1" width="3%">Currency</td>
                                    <td class="p-1 text-center" width="4%">UP</td>
                                    <td class="p-1 text-center" width="4%">Total</td>
                                    <td class="p-1" width="7%">Remarks</td>	  
                                    <td class="p-1" align="center" width="1%">
                                        <Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></Bars3Icon>
                                    </td>	  
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="p-1 align-top">
                                        Kervic Grace Uy
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-1 align-top text-center">JOR Qty</td>
                                    <td class="p-1 align-top text-center">Recom Qty</td>
                                    <td class="p-1 align-top text-center">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-primary text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-warning text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                        <div>
                                            <span class="badge bg-success text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-1 text-center align-top">
                                        Currency
                                    </td>
                                    <td class="p-1 align-top text-right">U/P</td>
                                    <td class="p-1 align-top text-right">Total</td>
                                    <td class="p-1 align-top">Remarks</td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-1 align-top">
                                        Kervic Grace Uy
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-1 align-top text-center">JOR Qty</td>
                                    <td class="p-1 align-top text-center">Recom Qty</td>
                                    <td class="p-1 align-top text-center">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-1 text-center align-top">
                                        Currency
                                    </td>
                                    <td class="p-1 align-top text-right">U/P</td>
                                    <td class="p-1 align-top text-right">Total</td>
                                    <td class="p-1 align-top">Remarks</td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-1 align-top">
                                        Kervic Grace Uy
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-1 align-top text-center">JOR Qty</td>
                                    <td class="p-1 align-top text-center">Recom Qty</td>
                                    <td class="p-1 align-top text-center">UOM</td>
                                    <td class="p-1 align-top break-all ">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like reada</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-1 text-center align-top">
                                        Currency
                                    </td>
                                    <td class="p-1 align-top text-right">U/P</td>
                                    <td class="p-1 align-top text-right">Total</td>
                                    <td class="p-1 align-top">Remarks</td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-1 align-top">
                                        Kervic Grace Uy
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-1 align-top text-center">JOR Qty</td>
                                    <td class="p-1 align-top text-center">Recom Qty</td>
                                    <td class="p-1 align-top text-center">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-1 text-center align-top">
                                        Currency
                                    </td>
                                    <td class="p-1 align-top text-right">U/P</td>
                                    <td class="p-1 align-top text-right">Total</td>
                                    <td class="p-1 align-top">Remarks</td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-1 align-top">
                                        Kervic Grace Uy
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-1 align-top text-center">JOR Qty</td>
                                    <td class="p-1 align-top text-center">Recom Qty</td>
                                    <td class="p-1 align-top text-center">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-1 text-center align-top">
                                        Currency
                                    </td>
                                    <td class="p-1 align-top text-right">U/P</td>
                                    <td class="p-1 align-top text-right">Total</td>
                                    <td class="p-1 align-top">Remarks</td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-1 align-top">
                                        Kervic Grace Uy
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-1 align-top text-center">JOR Qty</td>
                                    <td class="p-1 align-top text-center">Recom Qty</td>
                                    <td class="p-1 align-top text-center">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-1 text-center align-top">
                                        Currency
                                    </td>
                                    <td class="p-1 align-top text-right">U/P</td>
                                    <td class="p-1 align-top text-right">Total</td>
                                    <td class="p-1 align-top">Remarks</td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-1 align-top">
                                        Kervic Grace Uy
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-1 align-top text-center">JOR Qty</td>
                                    <td class="p-1 align-top text-center">Recom Qty</td>
                                    <td class="p-1 align-top text-center">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-1 text-center align-top">
                                        Currency
                                    </td>
                                    <td class="p-1 align-top text-right">U/P</td>
                                    <td class="p-1 align-top text-right">Total</td>
                                    <td class="p-1 align-top">Remarks</td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-1 align-top">
                                        Kervic Grace Uy
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-1 align-top text-center">JOR Qty</td>
                                    <td class="p-1 align-top text-center">Recom Qty</td>
                                    <td class="p-1 align-top text-center">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-1 text-center align-top">
                                        Currency
                                    </td>
                                    <td class="p-1 align-top text-right">U/P</td>
                                    <td class="p-1 align-top text-right">Total</td>
                                    <td class="p-1 align-top">Remarks</td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-1 align-top">
                                        Kervic Grace Uy
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-1 align-top text-center">JOR Qty</td>
                                    <td class="p-1 align-top text-center">Recom Qty</td>
                                    <td class="p-1 align-top text-center">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-1 text-center align-top">
                                        Currency
                                    </td>
                                    <td class="p-1 align-top text-right">U/P</td>
                                    <td class="p-1 align-top text-right">Total</td>
                                    <td class="p-1 align-top">Remarks</td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-1 align-top">
                                        Kervic Grace Uy
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-1 align-top text-center">JOR Qty</td>
                                    <td class="p-1 align-top text-center">Recom Qty</td>
                                    <td class="p-1 align-top text-center">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-1 text-center align-top">
                                        Currency
                                    </td>
                                    <td class="p-1 align-top text-right">U/P</td>
                                    <td class="p-1 align-top text-right">Total</td>
                                    <td class="p-1 align-top">Remarks</td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-1 align-top">
                                        Kervic Grace Uy
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-1 align-top text-center">JOR Qty</td>
                                    <td class="p-1 align-top text-center">Recom Qty</td>
                                    <td class="p-1 align-top text-center">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-1 text-center align-top">
                                        Currency
                                    </td>
                                    <td class="p-1 align-top text-right">U/P</td>
                                    <td class="p-1 align-top text-right">Total</td>
                                    <td class="p-1 align-top">Remarks</td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-1 align-top">
                                        Kervic Grace Uy
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-1 align-top text-center">JOR Qty</td>
                                    <td class="p-1 align-top text-center">Recom Qty</td>
                                    <td class="p-1 align-top text-center">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-1 text-center align-top">
                                        Currency
                                    </td>
                                    <td class="p-1 align-top text-right">U/P</td>
                                    <td class="p-1 align-top text-right">Total</td>
                                    <td class="p-1 align-top">Remarks</td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-1 align-top">
                                        Kervic Grace Uy
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-1 align-top text-center">JOR Qty</td>
                                    <td class="p-1 align-top text-center">Recom Qty</td>
                                    <td class="p-1 align-top text-center">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-1 text-center align-top">
                                        Currency
                                    </td>
                                    <td class="p-1 align-top text-right">U/P</td>
                                    <td class="p-1 align-top text-right">Total</td>
                                    <td class="p-1 align-top">Remarks</td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                    
                
            </div>
        </div>
        
	</navigation>
</template>