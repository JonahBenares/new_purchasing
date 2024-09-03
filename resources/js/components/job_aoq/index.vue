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
        ['<p class="m-0 text-center">2024-02-04</p>', 'SPE/Operation24-2032-CNPR', '<ul class="list-disc m-0"><li class="leading-none">Bacolod Triumph Hardware (Main Branch)</li> <li class="leading-none">Bacolod Mindanao Lumber and Plywood Corp.</li> <li class="leading-none">SGS Hardware Corporation</li> </ul>', 'Admin', 'Special Projects/Operation', 'Iris J. Sixto', '<span class="badge bg-orange-500 text-white !rounded-xl px-2 p-1">For TE</span>' ,'<a href="/job_aoq/print_te" class="btn btn-xs btn-warning text-white p-1"><EyeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></EyeIcon>View</a>'],

        ['<p class="m-0 text-center">2024-02-04</p>', 'Admin24-2033-CNPR', '<ul class="list-disc m-0"><li class="leading-none">Javieros Hollow Blocks Factory</li></ul>', 'Admin', 'Special Projects/Operation', 'Iris J. Sixto', '<span class="badge bg-orange-500 text-white !rounded-xl px-2 p-1">For TE</span>','<a href="/job_aoq/print_te" class="btn btn-xs btn-warning text-white p-1"><EyeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></EyeIcon>View</a>'],

        ['<p class="m-0 text-center">2024-02-04</p>', 'HAS24-2034-CNPR', '<ul class="list-disc m-0"><li class="leading-none bg-green-500 p-1"><span class="!text-white">Bacolod Paint Marketing</span></li> <li class="leading-none">Sugarland Hardware Corp.</li> <li class="leading-none">Bacolod Luis Paint Center Enterprises. Inc.</li> </ul>', 'Safety', 'Fire Hydrant System', 'Joselito Panes/Ricky Madeja', '<span class="badge bg-green-500 text-white !rounded-xl px-2 p-1">Awarded</span>','<a href="/job_aoq/awarded" class="btn btn-xs btn-warning text-white p-1"><EyeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></EyeIcon>View</a>'],

        ['<p class="m-0 text-center">2024-02-04</p>', 'FLM24-2019-CNPR', '<ul class="list-disc m-0"><li class="leading-none bg-green-500 p-1"><span class="!text-white">New China Enterprise Inc.</span></li></ul>', 'Fuel and Lube Management', 'Fire Hydrant System', 'JFleur de Liz Ambong / Rey D. Argawanon', '<span class="badge bg-green-500 text-white !rounded-xl px-2 p-1">Awarded</span>','<a href="/job_aoq/awarded" class="btn btn-xs btn-warning text-white p-1"><EyeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></EyeIcon>View</a>'],

        ['<p class="m-0 text-center">2024-01-09</p>', 'EIC24-1005-CNPR', '<ul class="list-disc m-0"><li class="leading-none">Bearing Center & Machinery Inc.</li><li class="leading-none">CAR-V Industrial Sales</li><li class="leading-none bg-green-500 p-1"><span class="!text-white">United Bearing Industrial Corp</span></li></ul>', 'Electrical/EIC', 'Fire Hydrant System', 'Rey D. Argawanon', '<span class="badge bg-green-500 text-white !rounded-xl px-2 p-1">Awarded</span>','<a href="/job_aoq/awarded" class="btn btn-xs btn-warning text-white p-1"><EyeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></EyeIcon>View</a>'],    

        ['<p class="m-0 text-center">2024-01-09</p>', 'EIC24-1005-CNPR', '<ul class="list-disc m-0"><li class="leading-none">Bearing Center & Machinery Inc.</li><li class="leading-none">CAR-V Industrial Sales</li><li class="leading-none bg-green-500 p-1"><span class="!text-white">United Bearing Industrial Corp</span></li></ul>', 'Electrical/EIC', 'Fire Hydrant System', 'Rey D. Argawanon', '<span class="badge bg-green-500 text-white !rounded-xl px-2 p-1">Awarded</span>','<a href="/job_aoq/awarded" class="btn btn-xs btn-warning text-white p-1"><EyeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></EyeIcon>View</a>'],   
        
        ['<p class="m-0 text-center">2024-01-09</p>', 'EIC24-1005-CNPR', '<ul class="list-disc m-0"><li class="leading-none">Bearing Center & Machinery Inc.</li><li class="leading-none">CAR-V Industrial Sales</li><li class="leading-none p-1"><span>United Bearing Industrial Corp</span></li></ul>', 'Electrical/EIC', 'Fire Hydrant System', 'Rey D. Argawanon', '<span class="badge bg-blue-500 text-white !rounded-xl px-2 p-1">Done TE</span>','<a href="/job_aoq/view" class="btn btn-xs btn-warning text-white p-1"><EyeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></EyeIcon>View</a>'],   
    ];
    
    // 
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
				title:'Abstract of Quotation List',
				extend: 'copy',
				exportOptions: {
					columns: [ 0, 1, 2, 3, 4, 5],
					orthogonal: null,
                    
				}
			},
			{
				title:'Abstract of Quotation List',
				extend: 'excel',
				exportOptions: {
					columns: [ 0, 1, 2, 3, 4, 5], 
					orthogonal: 'export',
                    format: {
                        body: function (data, row, column, node) {
                            if (column === 0){
                               return moment.utc(data).format('MMMM DD, YYYY');
                            }else if(column === 5){
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
				title:'Abstract of Quotation List',
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
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Abstract of Quotation <small>List</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">JO Abstract of Quotation</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="row">
            <div class="col-lg-12 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class=" pt-3">
                            <DataTable :data="data" :options="options" class="display table table-bordered table-hover !border nowrap">
                                <thead>
                                    <tr>
                                        <th class="!text-xs bg-gray-100 uppercase" > AOQ Date</th>
                                        <th class="!text-xs bg-gray-100 uppercase" > JOR No</th>
                                        <th class="!text-xs bg-gray-100 uppercase" > Supplier</th>
                                        <th class="!text-xs bg-gray-100 uppercase" > Department</th>
                                        <th class="!text-xs bg-gray-100 uppercase" > Enduse</th>
                                        <th class="!text-xs bg-gray-100 uppercase" > Requestor</th>
                                        <th class="!text-xs bg-gray-100 uppercase" > Status</th>
                                        <th class="!text-xs bg-gray-100 uppercase"  align="center"> 
                                            <span class="text-center  px-auto">
                                                <Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></Bars3Icon>
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
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