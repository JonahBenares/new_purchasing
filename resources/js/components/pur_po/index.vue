<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{ Bars3Icon, EyeIcon , MagnifyingGlassIcon} from '@heroicons/vue/24/solid'
	import{ArrowUpOnSquareIcon} from '@heroicons/vue/24/outline'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
</script>
<template>
	<navigation>
        <div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Purchase Order <small>List</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Purchase Order</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="row">
            <div class="col-lg-12 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="flex justify-between">
                            <div class="flex justify-left ">
                                <div class="form-control !w-10 !border-r-0 px-2">
                                    <MagnifyingGlassIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 "></MagnifyingGlassIcon>
                                </div>
                                <input type="text" class="form-control !w-72" placeholder="Search">
                            </div>
                            <span>
                                <div class="d-flex justify-content-between align-items-end flex-wrap space-x-2">
                                    <button type="button" class="btn btn-light !bg-gray-100 px-2 py-2 mt-2 mt-xl-0 !text-center !text-gray-500" title="export">
                                        <ArrowUpOnSquareIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></ArrowUpOnSquareIcon>
                                    </button>
                                    <a href="/pur_po/new" class="btn btn-primary mt-2 mt-xl-0 text-white">
                                        <span>Add New PO</span>
                                    </a>
                                </div>
                            </span>
                        </div>
                        <div class="table-responsive pt-3">
                            <table class="table table-bordered table-hover !border ">
                                <thead>
                                    <tr>
                                        <th class="!text-xs bg-gray-100 uppercase" width="8%"> PO Date</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="15%"> PO No</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="20%"> Supplier</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="20%"> PR #</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="20%"> Mode</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="5%"> Status</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="1%" align="center"> 
                                            <span class="text-center  px-auto">
                                                <Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></Bars3Icon>
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="">
                                        <td class="!align-top !text-xs"> 05/06/24 </td>
                                        <td class="!align-top !text-xs"> Supplier 1 </td>
                                        <td class="!align-top !text-xs"> RFQ-CENPRI-1001 </td>
                                        <td class="!align-top !text-xs"> rfq_it001 </td>
                                        <td class="!align-top !text-xs"> 
                                           Henne Tanan
                                        </td>
                                        <td class="!align-top !text-xs !text-center">
                                            <span class="badge bg-orange-500 text-white !rounded-xl px-2 p-1">PO Issued</span>
                                        </td>
                                        <td class="!align-top !text-xs p-1" align="center">
                                            <a href="/pur_po/view" class="btn btn-xs btn-warning text-white p-1">
                                                <EyeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></EyeIcon>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="!align-top !text-xs"> 05/06/24 </td>
                                        <td class="!align-top !text-xs"> Supplier 2 </td>
                                        <td class="!align-top !text-xs"> RFQ-CENPRI-1001 </td>
                                        <td class="!align-top !text-xs"> rfq_it001 </td>
                                        <td class="!align-top !text-xs"> 
                                           Henne Tanan
                                        </td>
                                        <td class="!align-top !text-xs !text-center">
                                            <span class="badge bg-orange-300 text-white !rounded-xl px-2 p-1">Draft</span>
                                        </td>
                                        <td class="!align-top !text-xs p-1" align="center">
                                            <a href="/pur_po/view" class="btn btn-xs btn-warning text-white p-1">
                                                <EyeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></EyeIcon>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="!align-top !text-xs"> 05/06/24 </td>
                                        <td class="!align-top !text-xs"> Supplier 3 </td>
                                        <td class="!align-top !text-xs"> RFQ-CENPRI-1001 </td>
                                        <td class="!align-top !text-xs"> rfq_it001 </td>
                                        <td class="!align-top !text-xs"> 
                                           Henne Tanan
                                        </td>
                                        <td class="!align-top !text-xs !text-center">
                                            <span class="badge bg-orange-500 text-white !rounded-xl px-2 p-1">PO Issued</span>
                                        </td>
                                        <td class="!align-top !text-xs p-1" align="center">
                                            <a href="/pur_po/view" class="btn btn-xs btn-warning text-white p-1">
                                                <EyeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></EyeIcon>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</navigation>
</template>