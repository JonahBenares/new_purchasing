<script setup>
	import navigation from '@/layouts/navigation.vue';
	import printheader from '@/layouts/print_header.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, CheckIcon, EyeIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref, onMounted } from "vue"
    import { useRouter } from "vue-router"
	let error=ref('');
	let success=ref('');
	const dangerAlert = ref(false)
	const dangerAlert_item = ref(false)
	const successAlert = ref(false)
	const dangerAlert_item1 = ref(false)
	const dangerAlert_item2 = ref(false)
	const dangerAlert_item3 = ref(false)
	const dangerAlert_item4 = ref(false)
	const warningAlert = ref(false)
    const infoAlert = ref(false)
	const hideAlert = ref(true)
	const viewComments = ref(false)
	let get_jorhead=ref([]);
	let get_jorlabordetails=ref([]);
	let get_jormaterialdetails=ref([]);
	let get_jornotes=ref([]);
	let jor_labor_details_id_view=ref("")
	let jor_material_details_id_view=ref("")
	let jor_note_details_id_view=ref("")
	let jor_head_id_view=ref("")
	let cancel_labor_reason=ref("")
	let cancel_material_reason=ref("")
	let label=ref("")
	const props = defineProps({
		id:{
			type:String,
			default:''
		}
	})
    onMounted(async () => {
		getJOR()
	})
	const getJOR = async () => {
		let response = await axios.get(`/api/get_jor_view_details/${props.id}`);
		get_jorhead.value=response.data.jorhead
		get_jorlabordetails.value=response.data.jorlabordetails
		get_jormaterialdetails.value=response.data.jomaterialdetails
		get_jornotes.value=response.data.jornotes
	}
	const opendangerAlert = () => {
		dangerAlert.value = !dangerAlert.value
	}
	const opendangerAlert_item = () => {
		dangerAlert_item.value = !dangerAlert_item.value
	}
	const opendangerAlert_item1 = () => {
		dangerAlert_item1.value = !dangerAlert_item1.value
	}
	const opendangerAlert_item2 = () => {
		dangerAlert_item2.value = !dangerAlert_item2.value
	}
	const opendangerAlert_item3 = () => {
		dangerAlert_item3.value = !dangerAlert_item3.value
	}
	const opendangerAlert_item4 = () => {
		dangerAlert_item4.value = !dangerAlert_item4.value
	}
    const openDangerAlert = () => {
		modalEdit.value = !modalEdit.value
	}
	const closeAlert = () => {
		successAlert.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
		dangerAlert_item.value = !hideAlert.value
		dangerAlert_item1.value = !hideAlert.value
		dangerAlert_item2.value = !hideAlert.value
		dangerAlert_item3.value = !hideAlert.value
		dangerAlert_item4.value = !hideAlert.value
	}
	const openViewComments = () => {
		viewComments.value = !viewComments.value
	}
	const closeModal = () => {
		viewComments.value = !hideAlert.value
	}
	const removeItem1 = () => {
		const item1 = document.getElementById("item1");
		dangerAlert_item.value = !hideAlert.value
		item1.remove();
	}

	const removeItem2 = () => {
		const item2 = document.getElementById("item2");
		dangerAlert_item1.value = !hideAlert.value
		item2.remove();
	}

	const removeItem3 = () => {
		const item3 = document.getElementById("item3");
		dangerAlert_item2.value = !hideAlert.value
		item3.remove();
	}

	const removeScopeitem1 = () => {
		const scope = document.getElementById("scope");
		dangerAlert_item3.value = !hideAlert.value
		scope.remove();
	}

	const removeNotes1 = () => {
		const notes = document.getElementById("notes");
		dangerAlert_item4.value = !hideAlert.value
		notes.remove();
	}
	const printDiv = () => {
		window.print();
	}

	const updateRecomdate = (id,identifier) => {
		const formData= new FormData()
		if(identifier=='Labors'){
			formData.append('recom_date', JSON.stringify(get_jorlabordetails.value))
			var api='update_jor_labor_recomdate_view';
		}else if(identifier=='Materials'){
			formData.append('recom_date', JSON.stringify(get_jormaterialdetails.value))
			var api='update_jor_material_recomdate_view';
		}
		axios.post('/api/'+api,formData).then(function () {
		}, function (err) {
			error.value = err.response.data.message;
		});
	}

	const cancelJorLabordetails = (option, id) => {
		if(option=='yes'){
			if(cancel_labor_reason.value!=''){
				const formData= new FormData()
				formData.append('cancel_reason', cancel_labor_reason.value)
				axios.post(`/api/cancel_jorlabordetails/`+id,formData).then(function (response) {
					label.value='scope'
					dangerAlert_item.value = !hideAlert.value
					success.value='Successfully cancelled scope!'
					successAlert.value = !successAlert.value
					cancel_labor_reason.value=''
					document.getElementById('labor_check').placeholder=""
					document.getElementById('labor_check').style.backgroundColor = '#FFFFFF';
					setTimeout(() => {
						closeAlert()
						getJOR()
					}, 2000);
				})
			}else{
				document.getElementById('labor_check').placeholder="Cancel Reason must not be empty!"
				document.getElementById('labor_check').style.backgroundColor = '#FAA0A0';
			}
		}else{
			label.value='scope'
			jor_labor_details_id_view.value=id
			dangerAlert_item.value = !dangerAlert_item.value
		}
	}

	const cancelJorMaterialdetails = (option, id) => {
		if(option=='yes'){
			if(cancel_material_reason.value!=''){
				const formData= new FormData()
				formData.append('cancel_reason', cancel_material_reason.value)
				axios.post(`/api/cancel_jormaterialdetails/`+id,formData).then(function (response) {
					label.value='item'
					dangerAlert_item.value = !hideAlert.value
					success.value='Successfully cancelled item!'
					successAlert.value = !successAlert.value
					cancel_material_reason.value=''
					document.getElementById('material_check').placeholder=""
					document.getElementById('material_check').style.backgroundColor = '#FFFFFF';
					setTimeout(() => {
						closeAlert()
						getJOR()
					}, 2000);
				})
			}else{
				document.getElementById('material_check').placeholder="Cancel Reason must not be empty!"
				document.getElementById('material_check').style.backgroundColor = '#FAA0A0';
			}
		}else{
			label.value='item'
			jor_material_details_id_view.value=id
			dangerAlert_item.value = !dangerAlert_item.value
		}
	}

	const cancelJorNotes = (option, id) => {
		if(option=='yes'){
			axios.get(`/api/cancel_jornotes/`+id).then(function (response) {
				label.value='note'
				dangerAlert_item.value = !hideAlert.value
				success.value='Successfully cancelled note!'
				successAlert.value = !successAlert.value
				setTimeout(() => {
					closeAlert()
					getJOR()
				}, 2000);
			})
		}else{
			label.value='note'
			jor_note_details_id_view.value=id
			dangerAlert_item.value = !dangerAlert_item.value
		}
	}

	const cancelAlljor = (option) => {
		if(option=='yes'){
			axios.get(`/api/cancel_alljor/${props.id}`).then(function (response) {
				dangerAlert.value = !hideAlert.value
				success.value='Successfully cancelled JOR!'
				successAlert.value = !successAlert.value
				setTimeout(() => {
					closeAlert()
					getJOR()
				}, 2000);
			})
		}else{
			jor_head_id_view.value=props.id
			dangerAlert.value = !dangerAlert.value
		}
	}
</script>
<template>
	<navigation>
		<div class="row" id="breadcrumbs">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Request <small>View</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/job_req">JO Request</a></li>
                            <li class="breadcrumb-item active" aria-current="page">View</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
					<div class="py-2 px-2 bg-red-500" v-if="get_jorhead.status=='Cancelled'">
						<span class="font-bold text-white">CANCELLED</span>
					</div>
				<div class="card-body">
					<hr class="border-dashed mt-0">
					<div class="pt-1" id="printable">
						<div class="hidden print:block">
							<printheader ></printheader>
							<div class="flex justify-center mt-1">
								<span class="uppercase">Job Order</span>
							</div>
							<hr class="print:block border-dashed mt-2">
						</div>
						<div class="print:block hidden print:flex print:justify-center h-full" v-if="get_jorhead.status=='Cancelled'">
							<img src="../../../images/bg_cancelled.png" alt="" class="absolute h-[420px] align-center opacity-100">
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6">
								<span class="text-sm text-gray-700 font-bold pr-1">Location: </span>
								<span class="text-sm text-gray-700">{{get_jorhead.location}}</span>
							</div>							
							<div class="col-lg-4 col-md-4 col-sm-4">
								<span class="text-sm text-gray-700 font-bold pr-1">Date Prepared: </span>
								<span class="text-sm text-gray-700">{{get_jorhead.date_prepared}}</span>
							</div>
							<div class="col-lg-2 col-md-2 col-sm-2">
								<span class="text-sm text-gray-700 font-bold pr-1">Duration: </span>
								<span class="text-sm text-gray-700">{{get_jorhead.duration}}</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6">
								<span class="text-sm text-gray-700 font-bold pr-1">JOR Number: </span>
								<span class="text-sm text-gray-700">{{get_jorhead.jor_no}}</span>
							</div>
							<div class="col-lg-4 col-sm-4 col-md-4">
								<span class="text-sm text-gray-700 font-bold pr-1">Completion Date: </span>
								<span class="text-sm text-gray-700">{{get_jorhead.completion_date}}</span>
							</div>
							<div class="col-lg-2 col-sm-2 col-md-2">
								<span class="text-sm text-gray-700 font-bold pr-1">Process Code: </span>
								<span class="text-sm text-gray-700">{{get_jorhead.process_code}}</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6">
								<span class="text-sm text-gray-700 font-bold pr-1">Site JOR Number: </span>
								<span class="text-sm text-gray-700">{{get_jorhead.site_jor}}</span>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4">
								<span class="text-sm text-gray-700 font-bold pr-1">Delivery Date: </span>
								<span class="text-sm text-gray-700">{{get_jorhead.delivery_date}}</span>
							</div>							
							<div class="col-lg-2 col-md-2 col-sm-2">
								<span class="text-sm text-gray-700 font-bold pr-1">Urgency: </span>
								<span class="text-sm text-gray-700">{{get_jorhead.urgency}}</span>
							</div>							
						</div>
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6">
								<span class="text-sm text-gray-700 font-bold pr-1">Department: </span>
								<span class="text-sm text-gray-700">{{get_jorhead.department_name}}</span>
							</div>							
						</div>
						<div class="row">
							<div class="col-lg-12">
								<span class="text-sm text-gray-700 font-bold pr-1">Purpose: </span>
								<span class="text-sm text-gray-700">{{get_jorhead.purpose}}</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<span class="text-sm text-gray-700 font-bold pr-1">Project/Activity: </span>
								<span class="text-sm text-gray-700">{{get_jorhead.project_activity}}</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<span class="text-sm text-gray-700 font-bold pr-1">General Description: </span>
								<span class="text-sm text-gray-700">{{get_jorhead.general_description}}</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<table class="w-full table-bordered !text-xs mt-3">
									<tr class="bg-gray-100 print:bg-transparent">
										<td class="p-1 uppercase text-center" width="2%">#</td>
										<td class="p-1 uppercase" width="">Scope Of Works</td>
										<td class="p-1 uppercase text-center" width="10%">Qty</td>
										<td class="p-1 uppercase text-center" width="10%">UOM</td>
										<td class="p-1 uppercase text-center" width="10%">Unit Cost</td>
										<td class="p-1 uppercase text-center" width="10%">Total Cost</td>
										<td class="p-1 uppercase" width="10%">Recom Date</td>
										<td class="p-1 space-x-auto uppercase text-center po_buttons" align="center" width="1%">
											<Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></Bars3Icon>
										</td>
									</tr>
									<tr v-for="(jl,index) in get_jorlabordetails" id="scope">
										<td :class="(jl.status=='Cancelled') ? 'bg-red-100 p-1 align-top text-center print:!bg-transparent print:!text-red-500' : 'p-1 align-top text-center print:!bg-transparent'">{{ index + 1 }}</td>
										<td :class="(jl.status=='Cancelled') ? 'bg-red-100 p-1 align-top print:!bg-transparent print:!text-red-500' : 'p-1 align-top'">{{ jl.scope_of_work }}</td>
										<td :class="(jl.status=='Cancelled') ? 'bg-red-100 p-1 align-top text-center print:!bg-transparent print:!text-red-500' : 'p-1 align-top text-center print:!bg-transparent'">{{ jl.quantity }}</td>
										<td :class="(jl.status=='Cancelled') ? 'bg-red-100 p-1 align-top text-center print:!bg-transparent print:!text-red-500' : 'p-1 align-top text-center print:!bg-transparent'">{{ jl.uom }}</td>
										<td :class="(jl.status=='Cancelled') ? 'bg-red-100 p-1 align-top text-center print:!bg-transparent print:!text-red-500' : 'p-1 align-top text-center print:!bg-transparent'">{{ jl.unit_cost }}</td>
										<td :class="(jl.status=='Cancelled') ? 'bg-red-100 p-1 align-top text-center print:!bg-transparent print:!text-red-500' : 'p-1 align-top text-center print:!bg-transparent'">{{ jl.unit_cost * jl.quantity  }}</td>
										<td class="p-1" :class="(jl.status=='Cancelled') ? 'bg-red-100 p-1 print:!bg-transparent print:!text-red-500' : 'p-1 print:!bg-transparent'">
											<input type="date" class="w-full bg-transparent" v-model="jl.recom_date" @change="updateRecomdate(jl.id,'Labors')"  v-if="jl.status!='Cancelled'">
											<input type="date" class="w-full bg-transparent" v-model="jl.recom_date" @change="updateRecomdate(jl.id,'Labors')" readonly v-else>
										</td>
										<td :class="(jl.status=='Cancelled') ? 'bg-red-100 text-center po_buttons' : 'text-center po_buttons'">
											<div v-if="jl.status!='Cancelled'">
												<button class="btn btn-danger p-1" @click="cancelJorLabordetails('no',jl.id)"  >
													<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
												</button>
											</div>
											<div v-else>
												<button class="btn btn-warning text-white p-1" @click="openViewComments()"  >
													<EyeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></EyeIcon>
												</button>
											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-12">
								<table class="w-full table-bordered !text-xs mt-3">
									<tr class="bg-gray-100 print:bg-transparent">
										<td class="p-1 uppercase text-center" width="2%">#</td>
										<td class="p-1 uppercase text-center" width="7%">Qty</td>
										<td class="p-1 uppercase text-center" width="7%">UOM</td>
										<td class="p-1 uppercase" width="20%">PN No.</td>
										<td class="p-1 uppercase" width="">Item Description</td>
										<td class="p-1 uppercase" width="10%">WH Stocks</td>
										<td class="p-1 uppercase" width="15%">Date Needed</td>
										<td class="p-1 uppercase" width="10%">Recom Date</td>
										<td class="p-1 uppercase po_buttons" width="1%" align="center">
											<span>
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 " ></XMarkIcon>
											</span>
										</td>
									</tr>
									<tr v-for="(jm,indexed) in get_jormaterialdetails" id="item1">
										<td :class="(jm.status=='Cancelled') ? 'bg-red-100 p-1 text-center print:!bg-transparent print:!text-red-500' : 'p-1 text-center print:!bg-transparent'">{{ indexed + 1 }}</td>
										<td :class="(jm.status=='Cancelled') ? 'bg-red-100 p-1 text-center print:!bg-transparent print:!text-red-500' : 'p-1 text-center print:!bg-transparent'">{{ jm.quantity }}</td>
										<td :class="(jm.status=='Cancelled') ? 'bg-red-100 p-1 text-center print:!bg-transparent print:!text-red-500' : 'p-1 text-center print:!bg-transparent'">{{ jm.uom }}</td>
										<td :class="(jm.status=='Cancelled') ? 'bg-red-100 p-1 print:!bg-transparent print:!text-red-500' : 'p-1 print:!bg-transparent'">{{ jm.pn_no }}</td>
										<td :class="(jm.status=='Cancelled') ? 'bg-red-100 p-1 print:!bg-transparent print:!text-red-500' : 'p-1 print:!bg-transparent'">{{ jm.item_description }}</td>
										<td :class="(jm.status=='Cancelled') ? 'bg-red-100 p-1 print:!bg-transparent print:!text-red-500' : 'p-1 print:!bg-transparent'">{{ jm.wh_stocks }}</td>
										<td :class="(jm.status=='Cancelled') ? 'bg-red-100 p-1 print:!bg-transparent print:!text-red-500' : 'p-1 print:!bg-transparent'">{{ jm.date_needed }}</td>
										<td :class="(jm.status=='Cancelled') ? 'bg-red-100 p-1 print:!bg-transparent print:!text-red-500' : 'p-1 print:!bg-transparent'">
											<input type="date" class="w-full bg-transparent" v-model="jm.recom_date" @change="updateRecomdate(jm.id,'Materials')"  v-if="jm.status!='Cancelled'">
											<input type="date" class="w-full bg-transparent" v-model="jm.recom_date" @change="updateRecomdate(jm.id,'Materials')" readonly v-else>
										</td>
										<td :class="(jm.status=='Cancelled') ? 'bg-red-100 text-center po_buttons' : 'text-center po_buttons'">
											<div v-if="jm.status!='Cancelled'">
												<button class="btn btn-danger p-1" @click="cancelJorMaterialdetails('no',jm.id)">
													<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
												</button>
											</div>
											<div v-else>
												<button class="btn btn-warning text-white p-1" @click="openViewComments()"  >
													<EyeIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></EyeIcon>
												</button>
											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-12">
								<table class="w-full table-bordered !text-xs mt-3">
									<tr class="bg-gray-100 print:bg-transparent">
										<td class="p-1 uppercase text-center" width="1%">#</td>
										<td class="p-1 uppercase" width="">Notes</td>
										<td class="p-1 space-x-auto uppercase text-center po_buttons" align="center" width="1%">
											<Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></Bars3Icon>
										</td>
									</tr>
									<tr v-for="(jn,indexer) in get_jornotes" id="notes">
										<td :class="(jn.status=='Cancelled') ? 'print:!bg-transparent print:!text-red-500bg-red-100 p-1 text-center align-top' : 'p-1 print:!bg-transparent text-center align-top'">{{ indexer + 1 }}</td>
										<td :class="(jn.status=='Cancelled') ? 'print:!bg-transparent print:!text-red-500bg-red-100 p-1 align-top' : 'p-1 print:!bg-transparent align-top'">{{ jn.notes }}</td>
										<td :class="(jn.status=='Cancelled') ? 'print:!bg-transparent print:!text-red-500bg-red-100 text-center po_buttons'  : 'text-center po_buttons'">
											<button class="btn btn-danger p-1" @click="cancelJorNotes('no',jn.id)" v-if="jn.status!='Cancelled'">
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
											</button>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<br>
						<!-- <hr class="border-dashed"> -->
						<div class="row my-2 po_buttons" > 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button type="button" class="btn btn-danger mr-2 w-36" @click="cancelAlljor('no')" v-if="get_jorhead.status!='Cancelled'">Cancel</button>
									<button type="button" class="btn btn-primary mr-2 w-36" @click="printDiv()">Print</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				</div>
			</div>
		</div>
		<Transition
            enter-active-class="transition ease-out !duration-1000"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-500"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-500"
            leave-to-class="opacity-0 scale-95"
        >
			<div class="modal p-0 !bg-transparent" :class="{ show:successAlert }">
				<div @click="closeAlert()" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
				<div @click="closeAlert()" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Warning!</h2>
									<h5 class="leading-tight">Are you sure you want to cancel this JOR?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full"  @click="cancelAlljor('yes')">Yes</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert_item }">
				<div @click="closeAlert()" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Warning!</h2>
									<h5 class="leading-tight">
										Are you sure you want to cancel this {{ label }}?<br>
										If yes, please state your reason.
									</h5>
									<label v-if="label=='scope'">Cancel Reason: </label>
									<label v-else-if="label=='item'">Cancel Reason: </label>
									<textarea name="" id="labor_check" class="form-control !border" rows="3" v-model="cancel_labor_reason"  v-if="label=='scope'"></textarea>
									<textarea name="" id="material_check" class="form-control !border" rows="3" v-model="cancel_material_reason"  v-else-if="label=='item'"></textarea>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="cancelJorLabordetails('yes',jor_labor_details_id_view)" v-if="label=='scope'">Yes</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="cancelJorMaterialdetails('yes',jor_material_details_id_view)" v-else-if="label=='item'">Yes</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="cancelJorNotes('yes',jor_note_details_id_view)" v-else-if="label=='note'">Yes</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert_item1 }">
				<div @click="closeAlert()" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Warning!</h2>
									<h5 class="leading-tight">Are you sure you want to remove this Item?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="removeItem2(index)">Yes</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert_item2 }">
				<div @click="closeAlert()" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Warning!</h2>
									<h5 class="leading-tight">Are you sure you want to remove this Item?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="removeItem3(index)">Yes</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert_item3 }">
				<div @click="closeAlert()" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Warning!</h2>
									<h5 class="leading-tight">Are you sure you want to remove this scope of work?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="removeScopeitem1(index)">Yes</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert_item4 }">
				<div @click="closeAlert()" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h2 class="mb-2 text-gray-700 font-bold text-red-400">Warning!</h2>
									<h5 class="leading-tight">Are you sure you want to remove this note?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full"  @click="closeAlert()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="removeNotes1(index)">Yes</button>
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
			<div class="modal pt-4 px-3" :class="{ show:viewComments }">
				<div @click="closeModal()" class="w-full h-full fixed"></div>
				 <!-- cancel here -->
				<div class="modal__content w-6/12">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold text-red-500">Cancelled</span>
							<a href="#" class="text-gray-600" @click="closeModal()">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<hr class="mt-0">
					<div class="modal_s_items ">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="flex justify-start space-x-1">
									<label class="text-gray-500 m-0 text-sm" for="">Date Cancelled: 02/11/24</label>
								</div>
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Cancel Reason:</label>
									<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum</p>
								</div>
							</div>
						</div>
					</div> 
				</div>
				 <!-- cancel here -->
			</div>
		</Transition>
	</navigation>
</template>