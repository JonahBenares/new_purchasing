<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{ Bars3Icon, EyeIcon , MagnifyingGlassIcon} from '@heroicons/vue/24/solid'
	import{ArrowUpOnSquareIcon} from '@heroicons/vue/24/outline'
    import {onMounted, ref} from "vue";
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
    const router = useRouter();
    
    // 
    const options = {
		// dom: 'Bftip',
		dom: "<'row'<'col-sm-8 col-lg-8 mb-2 pr-0 flex justify-end'B ><'col-sm-4 col-lg-4 mb-2 pl-1'f>>"+"<'row'<'col-sm-12 mb-2'tr>>"+"<'row'<'col-sm-6 mb-2'i><'col-sm-6 mb-2'p>>",
		select: true,
        order: [0, 'asc'],
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

    let aoq_list=ref([]);

    onMounted(async () => {
		getAOQ()
	})

    const getAOQ = async (page = 1) => {
		let response = await axios.get(`/api/get_all_jo_aoq?page=${page}`);
		aoq_list.value=response.data.aoqarray
	}

    const ViewAOQ = (id,aoq_details_id,status,aoq_status) => {
        if(status != 'Cancelled' && aoq_status == 'Done TE'){
            router.push('/job_aoq/view/'+id+'/'+aoq_details_id)
        }else{
            router.push('/job_aoq/print_te/'+id)
        }
		
	}
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
                        <div class="flex justify-between  mt-2 mb-0 absolute z-50 ">
                            <a href="/job_aoq/new" class="btn btn-primary mt-2 mt-xl-0 text-white">
                                <span>Add New AOQ</span>
                            </a>
                        </div>
                        <div class=" pt-3">
                            <DataTable :data="aoq_list" :options="options" class="display table table-bordered table-hover !border nowrap">
                                <thead>
                                    <tr>
                                        <th class="!text-xs bg-gray-100 uppercase" > AOQ Date</th>
                                        <th class="!text-xs bg-gray-100 uppercase" > JOR No</th>
                                        <th class="!text-xs bg-gray-100 uppercase" > Supplier</th>
                                        <th class="!text-xs bg-gray-100 uppercase" > Department</th>
                                        <!-- <th class="!text-xs bg-gray-100 uppercase" > Enduse</th> -->
                                        <th class="!text-xs bg-gray-100 uppercase" > Requestor</th>
                                        <th class="!text-xs bg-gray-100 uppercase" > Status</th>
                                        <th class="!text-xs bg-gray-100 uppercase"  align="center"> 
                                            <span class="text-center  px-auto">
                                                <Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></Bars3Icon>
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <template #column-2="props">
                                    <ul v-for="ven in props.rowData.vendor" class="mb-0 list-disc">
                                        <li :class="(ven.count_labor_awarded != 0 || ven.count_material_awarded != 0) ? 'px-1 bg-lime-500' : 'px-1'">
                                            {{ ven.vendor_name }} ({{ ven.identifier }})
                                        </li>
                                    </ul>
                                </template>
                                <template #column-5="props">
                                    <span class="bg-yellow-500 px-1 text-white" v-if="props.rowData.aoq_status == 'For TE'">{{props.rowData.aoq_status}}</span>
                                    <span class="bg-blue-500 px-1 text-white" v-if="props.rowData.aoq_status == 'Done TE'">{{props.rowData.aoq_status}}</span>
                                    <span class="bg-lime-500 px-1 text-white" v-if="props.rowData.aoq_status == 'Awarded'">{{props.rowData.aoq_status}}</span>
                                    <span class="bg-yellow-500 px-1 text-white" v-if="props.rowData.status == 'Draft'">- {{props.rowData.status}}</span>
                                    <span class="bg-red-500 px-1 text-white" v-if="props.rowData.status == 'Cancelled'">- {{props.rowData.status}}</span>
                                </template>
                                <template #column-6="props">
                                    <button @click="ViewAOQ(props.rowData.id, props.rowData.aoq_details_id, props.rowData.status, props.rowData.aoq_status)" class="btn btn-xs btn-warning text-white p-1">
                                        <EyeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></EyeIcon>
                                    </button>
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