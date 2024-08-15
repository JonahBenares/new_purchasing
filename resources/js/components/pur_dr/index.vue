<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{ Bars3Icon, EyeIcon , MagnifyingGlassIcon} from '@heroicons/vue/24/solid'
	import{ArrowUpOnSquareIcon} from '@heroicons/vue/24/outline'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
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
    const data = [
        ['2024-08-15','DR1001','POPE19-1000-1001','Purchase Request',''],
        ['2024-08-16','DR1002','PEIC20-1176-1283','Direct Purchase',''],
        ['2024-08-16','DR1003','PSPE20-1224-1358','Purchase Request',''],
        ['2024-08-17','DR1004','PENV19-1045-1344','Repeat Order',''],
        ['2024-08-20','DR1005','PWHC19-1173-1398','Purchase Request',''],
    ];
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
				title:'Delivery Receipt',
				extend: 'copy',
				exportOptions: {
					columns: [ 0, 1, 2, 3],
					orthogonal: null
				}
			},
			{
				title:'Delivery Receipt',
				extend: 'excel',
				exportOptions: {
                    columns: [ 0, 1, 2, 3],
					orthogonal: null,
                    format: {
                        body: function (data, row, column, node) {
                            if (column === 0 ){
                               return moment.utc(data).format('MMMM DD, YYYY');
                            }else if(column === 6){
								data = data.replace(/&gt;/g, '>')
                                   .replace(/&lt;/g, '<')
                                   .replace(/&amp;/g, '&')
                                   .replace(/&quot;/g, '"')
                                   .replace(/&#163;/g, '£')
                                   .replace(/&#39;/g, '\'')
                                   .replace(/&#10;/g, '\n');
								//replace html tags with one space
								data = data.replace(/<[^>]*>/g, ' ');
								//replace multiple spaces and tabs etc with one space
								return data.replace(/\s\s+/g, ' ');
							}else{
                                return data;
                            }
                        }
                    }
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
				title:'Delivery Receipt',
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
		// buttons: ['copy','excel','csv','pageLength']
	};
</script>
<template>
	<navigation>
        <div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Delivery Receipt <small>List</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Delivery Receipt</li>
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
                                <!-- <div class="form-control !w-10 !border-r-0 px-2">
                                    <MagnifyingGlassIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 "></MagnifyingGlassIcon>
                                </div>
                                <input type="text" class="form-control !w-72" placeholder="Search"> -->
                            </div>
                            <span>
                                <div class="d-flex justify-content-between align-items-end flex-wrap space-x-2">
                                    <!-- <button type="button" class="btn btn-light !bg-gray-100 px-2 py-2 mt-2 mt-xl-0 !text-center !text-gray-500" title="export">
                                        <ArrowUpOnSquareIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></ArrowUpOnSquareIcon>
                                    </button> -->
                                    <a href="/pur_dr/new" class="btn btn-primary mt-2 mt-xl-0 text-white">
                                        <span>Add New DR</span>
                                    </a>
                                </div>
                            </span>
                        </div>
                        <div class="table-responsive pt-3">
                            <DataTable :data="data" :options="options" class="display table table-bordered table-hover !border nowrap">
                                <thead>
                                    <tr>
                                        <th class="!text-xs bg-gray-100 uppercase" width="8%"> DR Date</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="20%"> DR No</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="20%"> PO No</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="15%"> Mode</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="1%" align="center"> 
                                            <span class="text-center  px-auto">
                                                <Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></Bars3Icon>
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <template #column-4="">
                                    <a href="/pur_dr/view" class="btn btn-xs btn-warning text-white p-1">
                                        <EyeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></EyeIcon>
                                    </a>
                                </template>
                            </DataTable>
                            <!-- <table class="table table-bordered table-hover !border ">
                                <thead>
                                    <tr>
                                        <th class="!text-xs bg-gray-100 uppercase" width="8%"> DR Date</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="20%"> DR No</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="20%"> PO No</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="15%"> Type</th>
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
                                        <td class="!align-top !text-xs"> MF Computer Solutions, Inc. </td>
                                        <td class="!align-top !text-xs"> RFQ-CENPRI-1001 </td>
                                        <td class="!align-top !text-xs"> Purchase Order </td>
                                        <td class="!align-top !text-xs p-1" align="center">
                                            <a href="/pur_dr/view" class="btn btn-xs btn-warning text-white p-1">
                                                <EyeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></EyeIcon>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="!align-top !text-xs"> 05/06/24 </td>
                                        <td class="!align-top !text-xs"> Supplier 2 </td>
                                        <td class="!align-top !text-xs"> RFQ-CENPRI-1002 </td>
                                        <td class="!align-top !text-xs"> Direct Purchase </td>
                                        <td class="!align-top !text-xs p-1" align="center">
                                            <a href="/pur_dr/view" class="btn btn-xs btn-warning text-white p-1">
                                                <EyeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></EyeIcon>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="">
                                        <td class="!align-top !text-xs"> 05/06/24 </td>
                                        <td class="!align-top !text-xs"> Supplier 3 </td>
                                        <td class="!align-top !text-xs"> RFQ-CENPRI-1003 </td>
                                        <td class="!align-top !text-xs"> Purchase Order </td>
                                        <td class="!align-top !text-xs p-1" align="center">
                                            <a href="/pur_dr/view" class="btn btn-xs btn-warning text-white p-1">
                                                <EyeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></EyeIcon>
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