<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{ Bars3Icon, PencilIcon, MagnifyingGlassIcon} from '@heroicons/vue/24/solid'
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
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Vendor <small>List</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Vendor List</li>
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
                                    <!-- <button type="button" class="btn btn-light !bg-gray-100 btn-icon d-none d-md-block ">
                                    <i class="mdi mdi-download text-muted"></i>
                                    </button> -->
                                    <!-- <button type="button" class="btn btn-light !bg-gray-100 btn-icon mt-2 mt-xl-0">
                                    <i class="mdi mdi-clock-outline text-muted"></i>
                                    </button> -->
                                    <button type="button" class="btn btn-light !bg-gray-100 px-2 py-2 mt-2 mt-xl-0 !text-center !text-gray-500" title="export">
                                        <ArrowUpOnSquareIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></ArrowUpOnSquareIcon>
                                    </button>
                                    <a href="/vendor/new" class="btn btn-primary mt-2 mt-xl-0 text-white">
                                        <span>Add New Vendor</span>
                                    </a>
                                </div>
                            </span>
                        </div>
                        <div class="overflow-x-scroll pt-3 relative">
                            <table class="table table-bordered table-hover !border" width="200%">
                                <thead>
                                    <tr>
                                        <th class="!text-xs bg-gray-100 uppercase"> Vendor Name</th>
                                        <th class="!text-xs bg-gray-100 uppercase"> Products/Services</th>
                                        <th class="!text-xs bg-gray-100 uppercase"> Address</th>
                                        <th class="!text-xs bg-gray-100 uppercase"> Phone</th>
                                        <th class="!text-xs bg-gray-100 uppercase"> Email</th>
                                        <th class="!text-xs bg-gray-100 uppercase"> Terms</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="4%"> Type</th>
                                        <th class="!text-xs bg-gray-100 uppercase"> Notes</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="3%"> EWT</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="3%"> Vat</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="5%"> Status</th>
                                        <th class="!text-xs bg-gray-100 uppercase !sticky !right-0" width="1%" align="center"> 
                                            <span class="text-center  px-auto">
                                                <Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></Bars3Icon>
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="">
                                        <td class="!text-xs"> unit </td>
                                        <td class="!text-xs"> unit </td>
                                        <td class="!text-xs"> unit </td>
                                        <td class="!text-xs"> unit </td>
                                        <td class="!text-xs"> unit </td>
                                        <td class="!text-xs"> unit </td>
                                        <td class="!text-xs"> unit </td>
                                        <td class="!text-xs"> unit </td>
                                        <td class="!text-xs"> unit </td>
                                        <td class="!text-xs"> unit </td>
                                        <td class="!text-xs"> unit </td>
                                        <td class="!text-xs p-1 !sticky !right-0 bg-white" align="center">
                                            <a href="/vendor/edit" class="btn btn-xs btn-info text-white p-1">
                                                <PencilIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PencilIcon>
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