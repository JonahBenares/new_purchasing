<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{ Bars3Icon, PencilIcon, MagnifyingGlassIcon} from '@heroicons/vue/24/solid'
	import{ArrowUpOnSquareIcon} from '@heroicons/vue/24/outline'
    import {onMounted, ref} from "vue";
	import { useRouter } from "vue-router";
    import DataTable from 'datatables.net-vue3';
    import DataTablesCore from 'datatables.net-bs5';
	import 'datatables.net-responsive';
	import 'datatables.net-select';
	import 'datatables.net-buttons';
	import 'datatables.net-buttons/js/buttons.html5';
	import 'datatables.net-buttons/js/buttons.print.js';
	import jszip from 'jszip';
	import $ from 'jquery'
    import moment from 'moment'
	DataTablesCore.Buttons.jszip(jszip);
    DataTable.use(DataTablesCore);
    const router = useRouter();
    const options = {
		// dom: 'Bftip',
		dom: "<'row'<'col-sm-8 col-lg-8 mb-2 pr-0 flex justify-end'B ><'col-sm-4 col-lg-4 mb-2 pl-1'f>>"+"<'row'<'col-sm-12 mb-2'tr>>"+"<'row'<'col-sm-6 mb-2'i><'col-sm-6 mb-2'p>>",
		select: true,	
		lengthMenu: [
			[10, 25, 50, -1],
			['10 rows', '25 rows', '50 rows', 'Show all']
		],
		buttons: [
			{
				title:'Vendor',
				extend: 'copy',
				exportOptions: {
					columns: [ 0, 1, 2, 3],
					orthogonal: null
				}
			},
			{
				title:'Vendor',
				extend: 'excel',
				exportOptions: {
                    columns: [ 0, 1, 2, 3],
					orthogonal: null,
				},
				createEmptyCells: true,
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    var clRow = $('row', sheet);
                    clRow[0].children[0].remove(); // clear header cell
                    $( 'row c', sheet ).attr( 's', '25' );
                }
			},
			{
				title:'Vendor',
				extend: 'print',
				exportOptions: {
					columns: [ 0, 1, 2, 3],
					orthogonal: null
				}
			},
			{
				extend: 'pageLength'
			}
		]
	};

    let vendors=ref([]);

    onMounted(async () => {
		getVendors()
	})

    const getVendors = async (page = 1) => {
		let response = await axios.get(`/api/get_all_vendors?page=${page}`);
		vendors.value=response.data.vendorarray
	}

    const EditVendor = (id) => {
		router.push('/vendor/edit/'+id)
	}
</script>
<style>
    td {text-wrap: wrap!important;}
</style>
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
                        <div class="flex justify-between  mt-2 mb-0 absolute z-50 ">
                            <a href="/vendor/new" class="btn btn-primary mt-2 mt-xl-0 text-white">
                                <span>Add New Vendor</span>
                            </a>
                        </div>
                        <div class="pt-3 relative">
                            <DataTable :data="vendors" :options="options" class="display table table-bordered table-hover !border nowrap"  width="200%">
                                <thead>
                                    <tr>
                                        <th class="!text-xs bg-gray-100 uppercase"> Vendor Name</th>
                                        <th class="!text-xs bg-gray-100 uppercase"> Products/Services</th>
                                        <th class="!text-xs bg-gray-100 uppercase"> No of Branches</th>
                                        <!-- <th class="!text-xs bg-gray-100 uppercase"> Address</th>
                                        <th class="!text-xs bg-gray-100 uppercase"> Phone</th>
                                        <th class="!text-xs bg-gray-100 uppercase"> Email</th>
                                        <th class="!text-xs bg-gray-100 uppercase"> Terms</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="4%"> Type</th>
                                        <th class="!text-xs bg-gray-100 uppercase"> Notes</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="3%"> EWT</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="3%"> Vat</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="5%"> Status</th> -->
                                        <th class="!text-xs bg-gray-100 uppercase" width="1%" align="center"> 
                                            <span class="text-center  px-auto">
                                                <Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></Bars3Icon>
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <template #column-3="props">
                                    <button @click="EditVendor(props.rowData.id)" class="btn btn-xs btn-info text-white p-1">
                                        <PencilIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PencilIcon>
                                    </button>
                                </template>
                            </DataTable>
                            <!-- <table class="table table-bordered table-hover !border" width="200%">
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
                            </table> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</navigation>
</template>
<style>
    @import 'datatables.net-dt';
    @import 'datatables.net-buttons-dt';
    @import 'datatables.net-select-dt';
</style>