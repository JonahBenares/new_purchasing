<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{ Bars3Icon, PencilIcon, MagnifyingGlassIcon} from '@heroicons/vue/24/solid'
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
        ['Installation of CCTV in parking lot','','1 week duration','2024-08-16','2024-08-26','2024-08-16','2024-08-27','₱ 15,670.92','Done',''],
        ['Building trash bins inside CENPRI compound','','2 weeks duration','2024-09-02','2024-09-13','','','₱ 8,520.50','Pending',''],
        ['Fix Roofings','','1 month duration','2024-08-01','2024-08-31','2024-08-01','','₱ 50,460.86','Pending',''],
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
				title:'Project/Activity',
				extend: 'copy',
				exportOptions: {
					columns: [0, 1, 2, 3, 4, 5,6,7,8],
					orthogonal: null
				}
			},
			{
				title:'Project/Activity',
				extend: 'excel',
				exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5,6,7,8],
					orthogonal: null,
                    format: {
                        body: function (data, row, column, node) {
                            if (column === 3 || column === 4 || column === 5 || column === 6){
                               return moment.utc(data).format('MMMM DD, YYYY');
                            }else if(column === 9){
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
				title:'Project/Activity',
				extend: 'print',
				exportOptions: {
					columns: [ 0, 1, 2, 3, 4, 5,6,7,8],
					orthogonal: null
				}
			},
			{
				extend: 'pageLength'
			}
		]
	};
</script>
<template>
	<navigation>
        <div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Project/Activity <small>List</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Project/Activity List</li>
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
                            <a href="/project/new" class="btn btn-primary mt-2 mt-xl-0 text-white">
                                <span>Add New Project/Activity</span>
                            </a>
                        </div>
                        <div class="overflow-x-scroll pt-3 relative">
                            <DataTable :data="data" :options="options" class="display table table-bordered table-hover !border nowrap" width="200%">
                                <thead>
                                    <tr>
                                        <th class="!text-xs bg-gray-100 uppercase" width="15%"> Project/Activity</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="10%"> Remarks</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="8%"> Duration (# of Days)</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="8%"> Target Start Date</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="8%"> Target Completion</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="8%"> Actual Start</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="8%"> Actual Completion</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="8%"> Est. Total(Materials)</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="8%"> Status</th>
                                        <th class="!text-xs bg-gray-100 uppercase !sticky !right-0" width="1%" align="center"> 
                                            <span class="text-center  px-auto">
                                                <Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></Bars3Icon>
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <template #column-9="">
                                    <a href="/project/edit" class="btn btn-xs btn-info text-white p-1">
                                        <PencilIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PencilIcon>
                                    </a>
                                </template>
                            </DataTable>
                            <!-- <table class="table table-bordered table-hover !border" width="200%">
                                <thead>
                                    <tr>
                                        <th class="!text-xs bg-gray-100 uppercase" width="15%"> Project/Activity</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="10%"> Remarks</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="8%"> Duration (# of Days)</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="8%"> Target Start Date</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="8%"> Target Completion</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="8%"> Actual Start</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="8%"> Actual Completion</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="8%"> Est. Total(Materials)</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="8%"> Status</th>
                                        <th class="!text-xs bg-gray-100 uppercase !sticky !right-0" width="1%" align="center"> 
                                            <span class="text-center  px-auto">
                                                <Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></Bars3Icon>
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="">
                                        <td class="!text-xs"> sample </td>
                                        <td class="!text-xs"> sample </td>
                                        <td class="!text-xs"> sample </td>
                                        <td class="!text-xs"> sample </td>
                                        <td class="!text-xs"> sample </td>
                                        <td class="!text-xs"> sample </td>
                                        <td class="!text-xs"> sample </td>
                                        <td class="!text-xs"> sample </td>
                                        <td class="!text-xs"> sample </td>
                                        <td class="!text-xs p-1 !sticky !right-0 bg-white" align="center">
                                            <a href="/project/edit" class="btn btn-xs btn-info text-white p-1">
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