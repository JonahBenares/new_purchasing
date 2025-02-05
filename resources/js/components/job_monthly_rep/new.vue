<script setup>
	import navigation from '@/layouts/navigation_hidden.vue';
	import{ Bars3Icon, CheckIcon, XMarkIcon} from '@heroicons/vue/24/solid'
	import{ArrowUpOnSquareIcon} from '@heroicons/vue/24/outline'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
    import moment from 'moment'

	const successAlert = ref(false)
	const itemsModal = ref(false)
    const tableWeekly = ref(false)
    const filterDet = ref(false)
    const filters = ref(true)
	const hideValue = ref(true)
    const dd = () =>{
        successAlert.value = !successAlert.value
    }

    const openModalChangeItems = () =>{
        itemsModal.value = !itemsModal.value
    }
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
    const closeModal = () => {
		successAlert.value = !hideValue.value
		itemsModal.value = !hideValue.value
	}

    const jor_det = ref(false)
</script>

<template>
	<navigation>
        
        <div class="sticky  top-0 left-0 z-50 bg-white p-3 shadow-md">
            <div class="row  ">
                <div class="col-lg-12">
                    <div class=" flex justify-between  px-2 ">
                        <div class="flex">
                            <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JOR Monthly Budget <small>Encode</small></h3>
                        </div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">JOR Monthly Budget</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="hidden" :class="{ show:filters }">
                <div class="row mt-3">							
                    <div class="col-lg-6 offset-lg-3">
                        <div class="form-group">
                            <label class="text-gray-500 " for="">Filter</label>
                            <input type="file" name="img[]" class="file-upload-default">
                            <div class="input-group">
                                <input type="text" class="form-control" onfocus="(this.type='month')" placeholder="Choose Month">
                                <select class="form-control file-upload-info">
                                    <option value="">Site</option>
                                    <option value="">Bacolod</option>
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
                            <span >September 01-31, 2024</span>
                        </p> 
                        <p for="" class="m-0 text-sm">Bacolod</p>
                    </div>
                    <div class="space-x-1">
                        <button class="btn btn-danger btn-sm" @click="removeFilter()">Remove Filter</button>
                        <span class="border-r mx-3"></span>
                        <button class="btn btn-info btn-sm" @click="openModalChangeItems()">Add Labor</button>
                        <button class="btn btn-primary btn-sm" @click="dd()">Save All</button>
                    </div>
                </div>
            </div>
        </div>
		<div class="row">
            <div class="col-lg-12 stretch-card">
                <div class="pt-0 hidden mb-1"  :class="{ show:tableWeekly }">
                    <div class="">
                        <table class="table-bordered text-xs" width="150%">
                            <thead>
                                <tr class="text-xs bg-gray-100 font-bold">
                                    <td class="p-1" width="10%">Point Person</td>	
                                    <td class="p-1" width="10%">Purpose</td>
                                    <td class="p-1" width="10%">End Use</td>
                                    <td class="p-1" width="5%">JOR No.</td>
                                    <td class="p-1" width="5%">Requestor</td>
                                    <td class="p-1 text-center" width="4%">Qty</td>
                                    <td class="p-1 text-center" width="3%">UOM</td>
                                    <td class="p-1" width="12%">Description</td>
                                    <td class="p-1" width="3%">Status</td>
                                    <td class="p-1" width="4%">Actual Cost</td>
                                    <td class="p-1" width="3%">Variance</td>
                                    <td class="p-1">Supplier</td>
                                    <td class="p-1" width="10%">Term</td>
                                    <td class="p-1" width="7%">Remarks</td>	  
                                    <td class="p-1" align="center" width="1%">
                                        <Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></Bars3Icon>
                                    </td>	  
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="p-0 align-top">
                                        <select name="" class="w-full p-1 bg-yellow-100" id="">
                                            <option value="">Select Employee</option>
                                        </select>
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-0 align-top">
                                        <input type="text" class="text-center p-1 w-full bg-yellow-100">
                                    </td>
                                    <td class="p-1 text-center align-top">UOM</td>
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
                                    <td class="p-1 text-center align-top">
                                        Actual Cost
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Variance
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-0 align-top"><textarea name="" id="" rows="1" class="bg-yellow-100 w-full p-1"></textarea></td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-0 align-top">
                                        <select name="" class="w-full p-1 bg-yellow-100" id="">
                                            <option value="">Select Employee</option>
                                        </select>
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-0 align-top">
                                        <input type="text" class="text-center p-1 w-full bg-yellow-100">
                                    </td>
                                    <td class="p-1 text-center align-top">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Actual Cost
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Variance
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-0 align-top"><textarea name="" id="" rows="1" class="bg-yellow-100 w-full p-1"></textarea></td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-0 align-top">
                                        <select name="" class="w-full p-1 bg-yellow-100" id="">
                                            <option value="">Select Employee</option>
                                        </select>
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-0 align-top">
                                        <input type="text" class="text-center p-1 w-full bg-yellow-100">
                                    </td>
                                    <td class="p-1 text-center align-top">UOM</td>
                                    <td class="p-1 align-top break-all ">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like reada</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Actual Cost
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Variance
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-0 align-top"><textarea name="" id="" rows="1" class="bg-yellow-100 w-full p-1"></textarea></td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-0 align-top">
                                        <select name="" class="w-full p-1 bg-yellow-100" id="">
                                            <option value="">Select Employee</option>
                                        </select>
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-0 align-top">
                                        <input type="text" class="text-center p-1 w-full bg-yellow-100">
                                    </td>
                                    <td class="p-1 text-center align-top">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Actual Cost
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Variance
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-0 align-top"><textarea name="" id="" rows="1" class="bg-yellow-100 w-full p-1"></textarea></td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-0 align-top">
                                        <select name="" class="w-full p-1 bg-yellow-100" id="">
                                            <option value="">Select Employee</option>
                                        </select>
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-0 align-top">
                                        <input type="text" class="text-center p-1 w-full bg-yellow-100">
                                    </td>
                                    <td class="p-1 text-center align-top">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Actual Cost
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Variance
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-0 align-top"><textarea name="" id="" rows="1" class="bg-yellow-100 w-full p-1"></textarea></td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-0 align-top">
                                        <select name="" class="w-full p-1 bg-yellow-100" id="">
                                            <option value="">Select Employee</option>
                                        </select>
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-0 align-top">
                                        <input type="text" class="text-center p-1 w-full bg-yellow-100">
                                    </td>
                                    <td class="p-1 text-center align-top">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Actual Cost
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Variance
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-0 align-top"><textarea name="" id="" rows="1" class="bg-yellow-100 w-full p-1"></textarea></td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-0 align-top">
                                        <select name="" class="w-full p-1 bg-yellow-100" id="">
                                            <option value="">Select Employee</option>
                                        </select>
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-0 align-top">
                                        <input type="text" class="text-center p-1 w-full bg-yellow-100">
                                    </td>
                                    <td class="p-1 text-center align-top">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Actual Cost
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Variance
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-0 align-top"><textarea name="" id="" rows="1" class="bg-yellow-100 w-full p-1"></textarea></td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-0 align-top">
                                        <select name="" class="w-full p-1 bg-yellow-100" id="">
                                            <option value="">Select Employee</option>
                                        </select>
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-0 align-top">
                                        <input type="text" class="text-center p-1 w-full bg-yellow-100">
                                    </td>
                                    <td class="p-1 text-center align-top">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Actual Cost
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Variance
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-0 align-top"><textarea name="" id="" rows="1" class="bg-yellow-100 w-full p-1"></textarea></td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-0 align-top">
                                        <select name="" class="w-full p-1 bg-yellow-100" id="">
                                            <option value="">Select Employee</option>
                                        </select>
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-0 align-top">
                                        <input type="text" class="text-center p-1 w-full bg-yellow-100">
                                    </td>
                                    <td class="p-1 text-center align-top">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Actual Cost
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Variance
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-0 align-top"><textarea name="" id="" rows="1" class="bg-yellow-100 w-full p-1"></textarea></td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-0 align-top">
                                        <select name="" class="w-full p-1 bg-yellow-100" id="">
                                            <option value="">Select Employee</option>
                                        </select>
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-0 align-top">
                                        <input type="text" class="text-center p-1 w-full bg-yellow-100">
                                    </td>
                                    <td class="p-1 text-center align-top">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Actual Cost
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Variance
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-0 align-top"><textarea name="" id="" rows="1" class="bg-yellow-100 w-full p-1"></textarea></td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-0 align-top">
                                        <select name="" class="w-full p-1 bg-yellow-100" id="">
                                            <option value="">Select Employee</option>
                                        </select>
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-0 align-top">
                                        <input type="text" class="text-center p-1 w-full bg-yellow-100">
                                    </td>
                                    <td class="p-1 text-center align-top">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Actual Cost
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Variance
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-0 align-top"><textarea name="" id="" rows="1" class="bg-yellow-100 w-full p-1"></textarea></td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-0 align-top">
                                        <select name="" class="w-full p-1 bg-yellow-100" id="">
                                            <option value="">Select Employee</option>
                                        </select>
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-0 align-top">
                                        <input type="text" class="text-center p-1 w-full bg-yellow-100">
                                    </td>
                                    <td class="p-1 text-center align-top">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Actual Cost
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Variance
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-0 align-top"><textarea name="" id="" rows="1" class="bg-yellow-100 w-full p-1"></textarea></td>	  
                                    <td align="center">
                                        <button class="btn btn-xs btn-danger p-1">
                                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                        </button>
                                    </td>	  
                                </tr>
                                <tr>
                                    <td class="p-0 align-top">
                                        <select name="" class="w-full p-1 bg-yellow-100" id="">
                                            <option value="">Select Employee</option>
                                        </select>
                                    </td>	
                                    <td class="p-1 align-top">Purpose</td>
                                    <td class="p-1 align-top">End Use</td>
                                    <td class="p-1 align-top">JOR No.</td>
                                    <td class="p-1 align-top">Requestor</td>
                                    <td class="p-0 align-top">
                                        <input type="text" class="text-center p-1 w-full bg-yellow-100">
                                    </td>
                                    <td class="p-1 text-center align-top">UOM</td>
                                    <td class="p-1 align-top">Description</td>
                                    <td class="p-1 text-center">
                                        <div>
                                            <span class="badge bg-danger text-white py-1 rounded text-xs">Status 1</span>
                                        </div>
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Actual Cost
                                    </td>
                                    <td class="p-1 text-center align-top">
                                        Variance
                                    </td>
                                    <td class="p-1 align-top">
                                        Supplier
                                    </td>
                                    <td class="p-1 align-top">Payment Term</td>
                                    <td class="p-0 align-top"><textarea name="" id="" rows="1" class="bg-yellow-100 w-full p-1"></textarea></td>	  
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
        <Transition
            enter-active-class="transition ease-out !duration-1000"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-500"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-500"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal p-0 !bg-transparent" :class="{ show:successAlert }">
				<div @click="closeModal" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-green-500 border-8 bg-green-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
							<div class="p-2 text-white">
								<CheckIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 "></CheckIcon>
							</div>
						</div>
					</div>
					<div class="py-5 rounded-t-3xl"></div>
					<div class="modal_s_items pt-0 !px-8 pb-4">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="text-center">
									<h2 class="mb-2  font-bold text-green-400">Success!</h2>
									<h5 class="leading-tight">You have successfully saved a Weekly Recommendation.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<a href="/job_monthly_report/new" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</Transition>
        <Transition
            enter-active-class="transition ease-out !duration-1000"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-500"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-500"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal pt-4 px-3" :class="{ show:itemsModal }">
				<div @click="closeModal" class="w-full h-full fixed"></div>
				<div class="modal__content w-8/12">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">Add Labor</span>
							<a href="#" class="text-gray-600" @click="closeModal">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<hr class="mt-0">
					<div class="modal_s_items ">
						<div class="pt-1">
                            <div class="row">							
                                <div class="col-lg-6 offset-lg-3 col-md-3">
                                    <div class="form-group">
                                    <label class="text-gray-500 m-0" for="">Choose JOR Number</label>
                                    <input type="file" name="img[]" class="file-upload-default">
                                    <div class="input-group col-xs-12">
                                        <select class="form-control file-upload-info">
                                            <option value="">JOR-CENPRI24-1001</option>
                                            <option value="">JOR-CENPRI24-1002</option>
                                        </select>
                                        <span class="input-group-append">
                                            <button class="btn btn-primary" type="button" @click="jor_det =!jor_det">Select</button>
                                        </span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="border-dashed">
                            <div v-show="jor_det">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Job Order Request: </span>
                                        <span class="text-sm text-gray-700">Bacolod</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Prepared Date: </span>
                                        <span class="text-sm text-gray-700">01/16/24</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <span class="text-sm text-gray-700 font-bold pr-1">JOR Number: </span>
                                        <span class="text-sm text-gray-700">JOR-BCD24-1209</span>
                                    </div>
                                    <div class="col-lg-6">
                                        <span class="text-sm text-gray-700 font-bold pr-1">New JOR Number: </span>
                                        <span class="text-sm text-gray-700">JOR-CENPRI24-1002</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Department: </span>
                                        <span class="text-sm text-gray-700">IT Department</span>
                                    </div>
                                    <div class="col-lg-4">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Process Code: </span>
                                        <span class="text-sm text-gray-700">0912</span>
                                    </div>
                                    <div class="col-lg-2">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Urgency: </span>
                                        <span class="text-sm text-gray-700">X</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <span class="text-sm text-gray-700 font-bold pr-1">End-Use: </span>
                                        <span class="text-sm text-gray-700">IT Department</span>
                                    </div>
                                    <div class="col-lg-12">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Purpose: </span>
                                        <span class="text-sm text-gray-700">Replace damage monitor, mouse and keyboard</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Project/Activity: </span>
                                        <span class="text-sm text-gray-700">Sample Project</span>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="w-full table-bordered !text-xs mt-3">
                                            <thead>
                                                <tr class="bg-gray-100">
                                                    <td class="p-1 uppercase text-center" width="2%">
                                                        <input type="checkbox">
                                                    </td>
                                                    <td class="p-1 uppercase text-center" width="2%">#</td>
                                                    <td class="p-1 uppercase" width="">Scope Of Works</td>
                                                    <td class="p-1 uppercase text-center" width="10%">Qty</td>
                                                    <td class="p-1 uppercase text-center" width="10%">UOM</td>
                                                </tr>
                                            </thead>   
                                            <tbody>
                                                <tr>
                                                    <td class="p-1 text-center">
                                                        <input type="checkbox">
                                                    </td>
                                                    <td class="p-1 text-center align-top">1</td>
                                                    <td class="p-1 align-top">
                                                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur
                                                    </td>
                                                    <td class="p-1 align-top text-center">23</td>
                                                    <td class="p-1 align-top text-center">lot</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table class="w-full table-bordered !text-xs mb-3">
                                            <thead>
                                                <tr class="bg-gray-100">
                                                    <td class="p-1 uppercase text-center" width="2%">
                                                        <input type="checkbox">
                                                    </td>
                                                    <td class="p-1 uppercase text-center" width="7%">Qty</td>
                                                    <td class="p-1 uppercase text-center" width="7%">UOM</td>
                                                    <td class="p-1 uppercase" width="20%">PN No.</td>
                                                    <td class="p-1 uppercase" width="">Item Description</td>
                                                    <td class="p-1 uppercase" width="10%">WH Stocks</td>
                                                    <td class="p-1 uppercase" width="15%">Date Needed</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="p-1 text-center">
                                                        <input type="checkbox">
                                                    </td>
                                                    <td class="p-1 text-center">5</td>
                                                    <td class="p-1 text-center">pc/s</td>
                                                    <td class="p-1">PN-0991-001</td>
                                                    <td class="p-1">Monitor</td>
                                                    <td class="p-1"></td>
                                                    <td class="p-1">08/25/24</td>
                                                </tr>
                                                <tr>
                                                    <td class="p-1 text-center">
                                                        <input type="checkbox">
                                                    </td>
                                                    <td class="p-1 text-center">5</td>
                                                    <td class="p-1 text-center">pc/s</td>
                                                    <td class="p-1">PN-0991-222</td>
                                                    <td class="p-1">Mouse</td>
                                                    <td class="p-1"></td>
                                                    <td class="p-1">08/25/24</td>
                                                </tr>
                                                <tr>
                                                    <td class="p-1 text-center">
                                                        <input type="checkbox">
                                                    </td>
                                                    <td class="p-1 text-center">5</td>
                                                    <td class="p-1 text-center">pc/s</td>
                                                    <td class="p-1">PN-0991-333</td>
                                                    <td class="p-1">Keyboard</td>
                                                    <td class="p-1"></td>
                                                    <td class="p-1">08/25/24</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            
                                <br>
                                <div class="row my-2"> 
                                    <div class="col-lg-12 col-md-12">
                                        <div class="flex justify-center space-x-2">
                                            <button type="submit" @click="openModel()" class="btn btn-info mr-2 w-44">Add Labor</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
						
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
</template>