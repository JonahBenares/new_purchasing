<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{ Bars3Icon, EyeIcon} from '@heroicons/vue/24/solid'
	import{ArrowUpOnSquareIcon, MagnifyingGlassIcon} from '@heroicons/vue/24/outline'
    import { reactive, ref, onMounted } from "vue"
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
    let get_alljor=ref([]);
    onMounted(async () => {
		getallJOR()
	})
	const getallJOR = async () => {
		let response = await axios.get("/api/get_alljor");
		get_alljor.value = response.data.jorall;
	}

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
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Request <small>Completed List</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/job_req">JO Request</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Completed List</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="row">
            <div class="col-lg-12 stretch-card">
                <div class="card">
                    <div class="bg-green-500 py-1 w-full"></div>
                    <div class="card-body">
                        <!-- <div class="flex justify-between  mt-2 mb-0 absolute z-50 ">
                            <a href="/job_req/new/0" class="btn btn-primary mt-2 mt-xl-0 text-white">
                                <span>Add New JO</span>
                            </a>
                        </div> -->
                        <div class="flex justify-between space-x-2 mt-3 mb-0 absolute z-50 ">
                            <a href="/job_req/new/0" class="btn btn-primary !py-[10px] mt-2 mt-xl-0 text-white">
                                <span>Add New JO</span>
                            </a>
                            <div class="space-x-1 pt-1 mt-1">
                                <div class="relative group inline-block">
                                    <a href="/job_req" class="w-20 !text-gray-400 !no-underline rounded-xl text-sm py-1 px-3 border ">
                                        Main List
                                    </a>
                                    <a href="/job_req" class="absolute !no-underline rounded-xl text-sm py-1 px-3 -top-1 left-1/2 transform -translate-x-1/2 w-[93px] text-center scale-0 transition-all duration-150 bg-blue-500 text-white group-hover:scale-100 ">Show List</a>
                                </div>
                                <div class="relative group inline-block">
                                    <a href="/job_req/cancelled" class="w-20 !text-gray-400 !no-underline rounded-xl text-sm py-1 px-3 border ">
                                        Cancelled
                                    </a>
                                    <a href="/job_req/cancelled" class="absolute !no-underline rounded-xl text-sm py-1 px-3 -top-1 left-1/2 transform -translate-x-1/2 w-[95px] text-center scale-0 transition-all duration-150 bg-red-500 text-white group-hover:scale-100 ">Show List</a>
                                </div>
                            </div>
                        </div>
                        <div class="pt-3">
                            <DataTable :data="get_alljor" :options="options" class="display table table-bordered table-hover !text-sm !border nowrap">
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
                                <template #column-6="props">
                                    <div class="flex justify-center">
                                        <span class="badge bg-green-500 text-white !rounded-xl px-2 p-1" v-if="props.rowData.status=='Saved'">{{props.rowData.status}}</span>
                                        <span class="badge bg-yellow-500 text-white !rounded-xl px-2 p-1" v-else-if="props.rowData.status=='Draft'">{{props.rowData.status}}</span>
                                        <span class="badge bg-red-500 text-white !rounded-xl px-2 p-1" v-else-if="props.rowData.status=='Cancelled'">{{props.rowData.status}}</span>
                                        <span class="badge bg-orange-500 text-white !rounded-xl px-2 p-1" v-else-if="props.rowData.status=='Closed'">{{props.rowData.status}}</span>
                                    </div>
                                </template>
                                <template #column-7="props">
                                    <a :href="'/job_req/view/'+props.rowData.id" class="btn btn-xs btn-warning text-white text-white p-1" v-if="props.rowData.status!='Draft'">
                                        <EyeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></EyeIcon>
                                    </a>
                                    <a :href="'/job_req/new/'+props.rowData.id" class="btn btn-xs btn-warning text-white text-white p-1" v-else>
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