<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{ Bars3Icon, EyeIcon} from '@heroicons/vue/24/solid'
	import{ArrowUpOnSquareIcon, MagnifyingGlassIcon} from '@heroicons/vue/24/outline'
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
        ['EIC24-1005-CNPR', '<div class="text-center">2024-01-01</div>', '<div class="text-center">2024-01-10</div>', 'Electrical/EIC', '<div class="text-center">1</div>', 'Rey D. Argawanon', '<div class="flex justify-center"><span class="badge bg-orange-500 text-white !rounded-xl px-2 p-1">Pending</span></div>' , ''],
        ['FLM24-2019-CNPR', '<div class="text-center">2024-02-03</div>', '<div class="text-center">2024-02-20</div>', 'Fuel and Lube Management', '<div class="text-center">1</div>', 'Fleur de Liz Ambong / Rey D. Argawanon', '<div class="flex justify-center"><span class="badge bg-orange-500 text-white !rounded-xl px-2 p-1">Pending</span></div>' , ''],
        ['HAS24-2034-CNPR', '<div class="text-center">2024-03-04</div>', '<div class="text-center">2024-03-15</div>', 'Safety', '<div class="text-center">1</div>', 'Joselito Panes/Ricky Madeja', '<div class="flex justify-center"><span class="badge bg-green-500 text-white !rounded-xl px-2 p-1">Completed</span></div>' , ''],
        ['Admin24-2033-CNPR', '<div class="text-center">2024-05-03</div>', '<div class="text-center">2024-05-23</div>', 'Admin', '<div class="text-center">1</div>', 'Iris J. Sixto', '<div class="flex justify-center"><span class="badge bg-green-500 text-white !rounded-xl px-2 p-1">Completed</span></div>' , ''],
        ['SPE/Operation24-2032-CNPR', '<div class="text-center">2024-06-03</div>', '<div class="text-center">2024-07-03</div>', 'Admin', '<div class="text-center">1</div>', 'Iris J. Sixto', '<div class="flex justify-center"><span class="badge bg-yellow-500 text-white !rounded-xl px-2 p-1">Draft</span></div>' , ''],
        ['MAI22-2256-CNPR', '<div class="text-center">2024-07-05</div>', '<div class="text-center">2024-08-11</div>', 'Maintenance', '<div class="text-center">1</div>', 'Ruel B. Beato', '<div class="flex justify-center"><span class="badge bg-green-500 text-white !rounded-xl px-2 p-1">Completed</span></div>' , ''],
        ['ITB22-2102-CNPR', '<div class="text-center">2024-08-06</div>', '<div class="text-center">2024-09-12</div>', 'IT Department - BCD', '<div class="text-center">1</div>', 'Jason Flor', '<div class="flex justify-center"><span class="badge bg-green-500 text-white !rounded-xl px-2 p-1">Completed</span></div>' , ''],
        ['FLM22-2043-CNPR', '<div class="text-center">2024-09-07</div>', '<div class="text-center">2024-10-13</div>', 'Fuel and Lube Management', '<div class="text-center">1</div>', 'Fleur de Liz Ambong / Rey D. Argawanon','<div class="flex justify-center"><span class="badge bg-yellow-500 text-white !rounded-xl px-2 p-1">Draft</span></div>' , ''],
        ['LAB22-2797-CNPR', '<div class="text-center">2024-10-08</div>', '<div class="text-center">2024-11-15</div>', 'Laboratory and Chemical', '<div class="text-center">1</div>', 'Beverly Ampog', '<div class="flex justify-center"><span class="badge bg-yellow-500 text-white !rounded-xl px-2 p-1">Draft</span></div>' , ''],
        ['MAI22-2257-CNPR', '<div class="text-center">2024-11-09</div>', '<div class="text-center">2024-12-16</div>', 'Maintenance', '<div class="text-center">1</div>', 'Godfrey S. E. Samano', '<div class="flex justify-center"><span class="badge bg-red-500 text-white !rounded-xl px-2 p-1">Cancelled</span></div>' , ''],
        ['HRB22-2067-CNPR', '<div class="text-center">2024-12-10</div>', '<div class="text-center">2024-12-29</div>', 'HR', '<div class="text-center">1</div>', 'Joemar De Los Santos', '<div class="flex justify-center"><span class="badge bg-red-500 text-white !rounded-xl px-2 p-1">Cancelled</span></div>' , ''],
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
				title:'Purchase Request List',
				extend: 'copy',
				exportOptions: {
					columns: [ 0, 1, 2, 3, 4, 5],
					orthogonal: null,
                    
				}
			},
			{
				title:'Purchase Request List',
				extend: 'excel',
				exportOptions: {
					columns: [ 0, 1, 2, 3, 4, 5], 
					orthogonal: 'export',
                    format: {
                        body: function (data, row, column, node) {
                            if (column === 1 || column === 2){
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
				title:'Purchase Request List',
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
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Request <small>List</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">JO Request</li>
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
                            <a href="/job_req/new" class="btn btn-primary mt-2 mt-xl-0 text-white">
                                <span>Add New JO</span>
                            </a>
                        </div>
                        <div class="pt-3">
                            <DataTable :data="data" :options="options" class="display table table-bordered table-hover !text-sm !border nowrap">
                                <thead>
                                    <tr>
                                        <th class="!text-xs bg-gray-100 uppercase" width="20%"> JO No</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="12%"> Date Prepared</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="12%"> Date Upload</th>
                                        <th class="!text-xs bg-gray-100 uppercase"> Department</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="5%"> Urgency</th>
                                        <th class="!text-xs bg-gray-100 uppercase"> Requestor</th>
                                        <th class="!text-xs bg-gray-100 uppercase"> Status</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="1%" align="center"> 
                                            <span class="text-center  px-auto">
                                                <Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></Bars3Icon>
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <template #column-7="props">
                                    <a href="/job_req/view" class="btn btn-xs btn-warning text-white text-white p-1">
                                        <EyeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></EyeIcon>
                                    </a>
                                </template>
                            </DataTable>
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