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
        ['SPE/Operation24-2032-CNPR', 'RFQ-0005', 'RFQ 5', '<div class="text-center">2024-07-01</div>', '<ul class="list-disc m-0"><li class="leading-none">Bacolod Triumph Hardware (Main Branch)</li><li class="leading-none">Bacolod Mindanao Lumber and Plywood Corp.</li><li class="leading-none">SGS Hardware Corporation</li></ul>', 'sample' , ''],
        ['Admin24-2033-CNPR', 'RFQ-0004', 'RFQ 4', '<div class="text-center">2024-05-21</div>', '<ul class="list-disc m-0"><li class="leading-none">Javieros Hollow Blocks Factory</li></ul>', 'sample' , ''],
        ['HAS24-2034-CNPR', 'RFQ-0003', 'RFQ 3', '<div class="text-center">2024-03-13</div>', '<ul class="list-disc m-0"><li class="leading-none">Bacolod Paint Marketing</li><li class="leading-none">Sugarland Hardware Corp.</li><li class="leading-none">Bacolod Luis Paint Center Enterprises. Inc.</li></ul>', 'sample' , ''],
        ['FLM24-2019-CNPR', 'RFQ-0002', 'RFQ 2', '<div class="text-center">2024-02-08</div>', '<ul class="list-disc m-0"><li class="leading-none">New China Enterprise Inc.</li> </ul>', 'sample' , ''],
        ['EIC24-1005-CNPR', 'RFQ-0001', 'RFQ 1', '<div class="text-center">2024-01-08</div>', '<ul class="list-disc m-0"><li class="leading-none">Bearing Center & Machinery Inc.</li><li class="leading-none">CAR-V Industrial Sales</li><li class="leading-none">United Bearing Industrial Corp</li></ul>', 'sample' , ''],

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
				title:'Request for Quotation List',
				extend: 'copy',
				exportOptions: {
					columns: [ 0, 1, 2, 3, 4, 5],
					orthogonal: null,
                    
				}
			},
			{
				title:'Request for Quotation List',
				extend: 'excel',
				exportOptions: {
					columns: [ 0, 1, 2, 3, 4, 5], 
					orthogonal: 'export',
                    format: {
                        body: function (data, row, column, node) {
                            if (column === 3){
                               return moment.utc(data).format('MMMM DD, YYYY');
                            }else if(column === 4){
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
				title:'Request for Quotation List',
				extend: 'print',
				exportOptions: {
					columns: [ 0, 1, 2, 3, 4, 5],
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
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Request for Quotation <small>List</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">JO Request for Quotation</li>
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
                            <a href="/job_quote/new" class="btn btn-primary mt-2 mt-xl-0 text-white">
                                <span>Add New RFQ</span>
                            </a>
                        </div>
                        <div class="pt-3">
                            <!-- <table class="table table-bordered table-hover !border "> -->
                                <DataTable :data="data" :options="options" class="display table table-bordered table-hover !border nowrap">
                                <thead>
                                    <tr>
                                        <th class="!text-xs bg-gray-100 uppercase" width="15%"> JOR</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="15%"> RFQ No</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="15%"> RFQ Name</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="10%"> Date</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="20%"> Vendors</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="20%"> Scope</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="1%" align="center"> 
                                            <span class="text-center  px-auto">
                                                <Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></Bars3Icon>
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <template #column-6="props">
                                    <a href="/job_quote/view" class="btn btn-xs btn-warning text-white p-1">
                                        <EyeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></EyeIcon>
                                    </a>
                                </template>
                                </DataTable>
                                <!-- <tbody>
                                    <tr class="">
                                        <td class="!align-top !text-xs"> PR-CENPRI24-1002 </td>
                                        <td class="!align-top !text-xs"> RFQ-CENPRI-1001 </td>
                                        <td class="!align-top !text-xs"> rfq_it001 </td>
                                        <td class="!align-top !text-xs"> February 16, 2024</td>
                                        <td class="!align-top !text-xs"> 
                                            <ul class="m-0 !text-xs !list-disc">
                                                <li>Lectrix Solutions Electrical Supplies & Services Cebu</li>
                                                <li>MF Computer Solutions, Inc.</li>
                                                <li>Nexus Industrial Prime Solutions Corp.</li>
                                            </ul>
                                        </td>
                                        <td class="!align-top !text-xs p-1" align="center">
                                            <a href="/pur_quote/view" class="btn btn-xs btn-warning text-white p-1">
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