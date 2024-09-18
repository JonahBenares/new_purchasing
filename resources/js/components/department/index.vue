<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{ Bars3Icon, PencilIcon, XMarkIcon, CheckIcon} from '@heroicons/vue/24/solid'
	import{ArrowUpOnSquareIcon} from '@heroicons/vue/24/outline'
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
	let form=ref([]);
	let success=ref('');
	let error=ref('');
	let department_list=ref([]);
	let edit_department=ref([]);
	onMounted(async () => {
		departmentForm()
		getDepartment()
	})
	const departmentForm = async () => {
		let response = await axios.get("/api/create_department");
		form.value = response.data;
	}
	const getDepartment = async () => {
		let response = await axios.get("/api/get_all_department");
		department_list.value=response.data.department
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
				title:'Department',
				extend: 'copy',
				exportOptions: {
					columns: [ 0 ],
					orthogonal: null
				}
			},
			{
				title:'Department',
				extend: 'excel',
				exportOptions: {
                    columns: [ 0 ],
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
				title:'Department',
				extend: 'print',
				exportOptions: {
					columns: [ 0 ],
					orthogonal: null
				}
			},
			{
				extend: 'pageLength'
			}
		]
	};

    const modalNew = ref(false)
    const modalEdit = ref(false)
	const hideModal = ref(true)
	const dangerAlert = ref(false)
	const successAlert = ref(false)
	const hideAlert = ref(true)
	const openNew = () => {
		modalNew.value = !modalNew.value
	}
    const openEdit = async (id) => {
		let response = await axios.get("/api/edit_department/"+id);
		edit_department.value=response.data.department
		modalEdit.value = !modalEdit.value
	}
	const closeModal = () => {
		modalNew.value = !hideModal.value
		modalEdit.value = !hideModal.value
	}

	const closeAlert = () => {
		successAlert.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
	}

	const onSave = () => {
		const formData= new FormData()
		formData.append('department_name', form.value.department_name)
		axios.post("/api/add_department",formData).then(function () {
			success.value='You have successfully added new department!'
			form.value.department_name=''
			successAlert.value = !successAlert.value
			getDepartment()
			setTimeout(() => {
				closeModal()
				closeAlert()
			}, 2000);
		}, function (err) {
			error.value = err.response.data.message;
			dangerAlert.value = !dangerAlert.value
			getDepartment()
		});
	}

	const onEdit = (id) => {
		const formData= new FormData()
		formData.append('department_name', edit_department.value.department_name)
		axios.post(`/api/update_department/${id}`,formData).then(function () {
			success.value='You have successfully updated department!'
			form.value.department_name=''
			successAlert.value = !successAlert.value
			getDepartment()
			setTimeout(() => {
				closeModal()
				closeAlert()
			}, 2000);
		}, function (err) {
			error.value = err.response.data.message;
			dangerAlert.value = !dangerAlert.value
			getDepartment()
		});
	}
</script>
<template>
	<navigation>
        <div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Department <small>List</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Department List</li>
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
                            <button @click="openNew()" class="btn btn-primary mt-2 mt-xl-0 text-white">
                                <span>Add New Department </span>
                            </button>
                        </div>
                        <div class="pt-3">
                            <DataTable :data="department_list" :options="options" class="display table table-bordered table-hover !border nowrap">
                                <thead>
                                    <tr>
                                        <th class="!text-xs bg-gray-100 uppercase"> Department Name</th>
                                        <th class="!text-xs bg-gray-100 uppercase" width="1%" align="center"> 
                                            <span class="text-center  px-auto">
                                                <Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></Bars3Icon>
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <template #column-1="props" align="center">
									<center>
										<button @click="openEdit(props.rowData.id)" class="btn btn-xs btn-info text-white p-1">
											<PencilIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PencilIcon>
										</button >
									</center>
                                </template>
                            </DataTable>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal pt-4 px-3" :class="{ show:modalNew }">
				<div @click="closeModal" class="w-full h-full fixed"></div>
				<div class="modal__content w-6/12">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">Add Department</span>
							<a href="#" class="text-gray-600" @click="closeModal">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<hr class="mt-0">
					<div class="modal_s_items ">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Department Name</label>
									<input type="text" class="form-control" placeholder="Department" v-model="form.department_name">
								</div>
							</div>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button  @click="onSave()" class="btn btn-primary mr-2 w-44" id='save'>Save</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal pt-4 px-3" :class="{ show:modalEdit }">
				<div @click="closeModal" class="w-full h-full fixed"></div>
				<div class="modal__content w-6/12">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">Update Department</span>
							<a href="#" class="text-gray-600" @click="closeModal">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<hr class="mt-0">
					<div class="modal_s_items ">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Department Name</label>
									<input type="text" class="form-control" placeholder="Department Name" v-model="edit_department.department_name">
								</div>
							</div>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <a href="/" class="btn btn-info mr-2 w-44">Update</a> -->
									<button  @click="onEdit(edit_department.id)" class="btn btn-info mr-2 w-44" id='save'>Update</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
		<Transition
            enter-active-class="transition ease-out !duration-1000"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-500"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-500"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal p-0 !bg-transparent" :class="{ show:successAlert }">
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-green-500 border-8 bg-green-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
							<div class="p-2 text-white">
								<CheckIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 "></CheckIcon>
							</div>
						</div>
					</div>
					<div class="py-5 rounded-t-3xl"></div>
					<div class="modal_s_items pt-0 !px-8 pb-4">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="text-center">
									<h2 class="mb-2  font-bold text-green-400">Success!</h2>
									<h5 class="leading-tight">{{ success }}</h5>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</Transition>
		<Transition
            enter-active-class="transition ease-out !duration-1000"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-500"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-500"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert }">
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-red-500 border-8 bg-red-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
							<div class="p-2 text-white">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-24 h-24 "></XMarkIcon>
							</div>
						</div>
					</div>
					<div class="py-5 rounded-t-3xl"></div>
					<div class="modal_s_items pt-0 !px-8 pb-4">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="text-center">
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Error!</h2>
									<h5 class="leading-tight" >{{ error }}</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn btn-danger btn-sm !rounded-full w-full"  @click="closeAlert()">Close</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
</template>