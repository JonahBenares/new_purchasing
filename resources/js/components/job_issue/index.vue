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
    const drawer_revise = ref(false)
	const hideModal = ref(true)
    const openDrawerRevise = () => {
		drawer_revise.value = !drawer_revise.value
	}
    const closeModal = () => {
		drawer_revise.value = !hideModal.value
	}
	DataTablesCore.Buttons.jszip(jszip);
    DataTable.use(DataTablesCore);
    const data = [
        ['<span>2024-08-15</span>', '<span>2024-08-15</span>', 'POPE19-1000-1001' , 'POPE19-1000-1001' , 'MF Computer Solutions, Inc.','PR-19772-8727','Purchase Request','Pending',''],
        ['<span>2024-08-16</span>', '<span>2024-08-15</span>', 'PEIC20-1176-1283' , 'POPE19-1000-1001' , 'A-one Industrial Sales','FLM22-2020','Direct Purchase','Pending',''],
        ['<span>2024-08-16</span>', '<span>2024-08-15</span>', 'PSPE20-1224-1358' , 'POPE19-1000-1001' , '7RJ Brothers Sand & Gravel & Gen. Mdse.','ENV24-1359','Purchase Request','Fully Delivered',''],
        ['<span>2024-08-17</span>', '<span>2024-08-15</span>', 'PENV19-1045-1344' , 'POPE19-1000-1001' , 'A.C. Parts Merchandising','OPE24-1355','Repeat Order','Cancelled',''],
        ['<span>2024-08-20</span>', '<span>2024-08-15</span>', 'PWHC19-1173-1398' , 'POPE19-1000-1001' , 'Bacolod General Parts Marketing','HAS24-1354','Purchase Request','Fully Delivered',''],
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
				title:'Purchase Order',
				extend: 'copy',
				exportOptions: {
					columns: [ 0, 1, 2, 3, 4, 5],
					orthogonal: null
				}
			},
			{
				title:'Purchase Order',
				extend: 'excel',
				exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5],
					orthogonal: null,
                    format: {
                        body: function (data, row, column, node) {
                            if (column === 0){
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
				title:'Purchase Order',
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
<style></style>
<template>
	<navigation>
        <div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Issuance <small>List</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">JO Issuance</li>
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
                            <a href="/job_issue/new" class="btn btn-primary mt-2 mt-xl-0 text-white">
                                <span>Add New PO</span>
                            </a>
                        </div>
                        <div class="pt-3">
                            <DataTable :data="data" :options="options" class="display table table-bordered table-hover !border nowrap">
                                <thead>
                                    <tr>
                                        <th class="!text-xs bg-gray-100 uppercase" width="8%"> Date Prepared</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="8%"> Date Needed</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="15%"> JOI No</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="20%"> CENJO #/ JO #</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="20%"> Supplier</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="20%"> Project Title</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="20%"> Mode</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="5%"> Status</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="1%" align="center"> 
                                            <span class="text-center  px-auto">
                                                <Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></Bars3Icon>
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <template #column-2="">
                                        <span class="text-left m-0 p-0 leadinng-none cursor-pointer" @click="openDrawerRevise()">
                                            JOIPE19-1000-1001.r1
                                        </span>
								</template>
                                <template #column-8="">
                                    <a href="/job_issue/view" class="btn btn-xs btn-warning text-white p-1">
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
    <Transition
        enter-active-class="transition ease-out duration-500"
        enter-from-class="opacity-0 "
        enter-to-class="opacity-100 "
        leave-active-class="transition ease-in duration-75"
        leave-from-class="opacity-100 "
        leave-to-class="opacity-0 scale-95"
    >
        <div class="modal pt-0 px-0 !bg-transparent" :class="{ show:drawer_revise }">
            <div @click="closeModal" class="w-full h-screen fixed bg-transparent"></div>
            <div class="modal__content w-3/12 float-right min-h-[690px]">
                <div class="row mb-3">
                    <div class="col-lg-12 flex justify-between">
                        <span class="font-bold ">Revise List</span>
                        <a href="#" class="text-gray-600" @click="closeModal">
                            <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
                        </a>
                    </div>
                </div>
                <hr class="m-0">
                <div class="modal_s_items ">
                    <div class="">
                        <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">JOI-88270-7662 (Main)</a>
                        <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">JOI-88270-7662.r1</a>
                        <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">JOI-88270-7662.r2</a>
                        <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">JOI-88270-7662.r3</a>
                        <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">JOI-88270-7662.r4</a>
                        <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">JOI-88270-7662.r5</a>
                        <a href="#"  @click="closeModal" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">JOI-88270-7662.r6 (Current)</a>
                    </div>
                    <!-- <div>
                        <p class="text-center text-sm">No Data</p>
                    </div> -->
                </div> 
            </div>
        </div>
    </Transition>
</template>
<style>
    @import 'datatables.net-dt';
    @import 'datatables.net-buttons-dt';
    @import 'datatables.net-select-dt';
</style>