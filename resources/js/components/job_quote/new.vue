<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, ExclamationTriangleIcon, CheckIcon} from '@heroicons/vue/24/solid'
	import { onMounted, ref } from "vue"
	import { useRouter } from "vue-router";
	const router = useRouter();

	let jornolist=ref([]);
	let jor_no=ref([]);
	let vendorlist=ref([]);
	let vendor_list=ref([]);
	let JORHead=ref([]);
	let LaborDetails=ref([]);
	let MaterialDetails=ref([]);
	let selected_labor=ref([]);
	let selected_materials=ref([]);
	let rfq_name=ref('');
	let rfq_no=ref('');
	let date_created=ref('');
	let vendor=ref('');
	let all_labor_checkbox=ref(0);
	let all_material_checkbox=ref(0);

	const props = defineProps({
        id:{
            type:String,
            default:''
        },	
    })

	onMounted(async () => {
		getjorno()
		getvendors()
		if(props.id != 0){
			getJORDetails()
		}
	})

	const getjorno = async () => {
			let response = await axios.get("/api/jor_list");
			jornolist.value=response.data
	}

	const getvendors = async () => {
			let response = await axios.get("/api/vendor_list");
			vendorlist.value=response.data
	}

	const getJORDetails= async () => {
		let id = jor_no.value
		if(props.id != 0 && id == ''){
			let response = await axios.get(`/api/get_jor_data/${props.id}`)
			document.getElementById("display_details").style.display="block"
			JORHead.value = response.data.JORHead
			LaborDetails.value = response.data.LaborDetails
			MaterialDetails.value = response.data.MaterialDetails
			rfq_no.value = response.data.rfq_no
		}else if(id != ''){
			let response = await axios.get('/api/get_jor_data/'+id)
			document.getElementById("display_details").style.display="block"
			JORHead.value = response.data.JORHead
			LaborDetails.value = response.data.LaborDetails
			MaterialDetails.value = response.data.MaterialDetails
			rfq_no.value = response.data.rfq_no
		}else{
			JORNoAlert.value = !JORNoAlert.value
		}	
	}

	const allSelectedLabor=ref(false);
	const checkalllabor=ref([]);
	const CheckAllLabor = () => {
			var MaterialisChecked = document.getElementsByClassName("checkboxesmaterial");
			var material_count=0;
			for(var m=0;m<MaterialisChecked.length;m++){
				if(MaterialisChecked[m].checked === true){
					material_count++;
				}
			}

			var count_check_labor=document.getElementsByClassName('checkboxeslabor');
			for(var x=0;x<count_check_labor.length;x++){
				var check_labor=document.getElementsByClassName('checkboxeslabor')[x].checked;
				if(!check_labor){
					checkalllabor.value=allSelectedLabor
					if(all_labor_checkbox.value == 0){
						LaborDetails.value[x].labor_checkbox=1;
					}
					document.getElementById("AddVendorButton").style.display="block"
				}else{
					checkalllabor.value=!allSelectedLabor
					if(all_labor_checkbox.value == 1){
						LaborDetails.value[x].labor_checkbox=0;
					}

					if(material_count>=1){
						document.getElementById("AddVendorButton").style.display="block"
					}else{
						document.getElementById("AddVendorButton").style.display="none"
					}
				}
			}
	}

	const allSelectedMaterial=ref(false);
	const checkallmaterial=ref([]);
	const CheckAllMaterial = () => {
			var LaborisChecked = document.getElementsByClassName("checkboxeslabor");
			var labor_count=0;
			for(var l=0;l<LaborisChecked.length;l++){
				if(LaborisChecked[l].checked === true){
					labor_count++;
				}
			}

			var count_check_material=document.getElementsByClassName('checkboxesmaterial');
			for(var x=0;x<count_check_material.length;x++){
				var check_material=document.getElementsByClassName('checkboxesmaterial')[x].checked;
				if(!check_material){
					checkallmaterial.value=allSelectedMaterial
					if(all_material_checkbox.value == 0){
						MaterialDetails.value[x].material_checkbox=1;
					}
					document.getElementById("AddVendorButton").style.display="block"
				}else{
					checkallmaterial.value=!allSelectedMaterial
					if(all_material_checkbox.value == 1){
						MaterialDetails.value[x].material_checkbox=0;
					}
					
					if(labor_count>=1){
						document.getElementById("AddVendorButton").style.display="block"
					}else{
						document.getElementById("AddVendorButton").style.display="none"
					}
				}
			}
	}

	const CountCheckbox= () =>{
		var LaborisChecked = document.getElementsByClassName("checkboxeslabor");
		var labor_count=0;
		for(var l=0;l<LaborisChecked.length;l++){
			if(LaborisChecked[l].checked === true){
				labor_count++;
			}
		}

		var MaterialisChecked = document.getElementsByClassName("checkboxesmaterial");
		var material_count=0;
		for(var m=0;m<MaterialisChecked.length;m++){
			if(MaterialisChecked[m].checked === true){
				material_count++;
			}
		}

			if(labor_count>=1 || material_count>=1){
				document.getElementById("AddVendorButton").style.display="block"
			}else{
				document.getElementById("AddVendorButton").style.display="none"
			}
	}

	const addItemsVendor= () => {
		for(var l=0; l<LaborDetails.value.length; l++){
			if(LaborDetails.value[l].labor_checkbox == 1){
				selected_labor.value.push({
					jor_labor_details_id:LaborDetails.value[l].jor_labor_details_id,
					quantity:LaborDetails.value[l].quantity,
					// remaining_qty:LaborDetails.value[x].remaining_qty,
					uom:LaborDetails.value[l].uom,
					scope_of_work:LaborDetails.value[l].scope_of_work,
				});
			}
		}
		for(var m=0; m<MaterialDetails.value.length; m++){
			if(MaterialDetails.value[m].material_checkbox == 1){
				selected_materials.value.push({
					jor_material_details_id:MaterialDetails.value[m].jor_material_details_id,
					quantity:MaterialDetails.value[m].quantity,
					// remaining_qty:MaterialDetails.value[x].remaining_qty,
					uom:MaterialDetails.value[m].uom,
					pn_no:MaterialDetails.value[m].pn_no,
					item_description:MaterialDetails.value[m].item_description,
					wh_stocks:MaterialDetails.value[m].wh_stocks,
					date_needed:MaterialDetails.value[m].date_needed,
				});
			}
		}
		showModal.value = !showModal.value
	}

	const addVendor= () => {
		let ven = vendor.value
		const v = ven.split("_")
		let vendor_details_id= v[0]
		let vendor_name= v[2]
		let identifier= v[3]

		for(var x=0; x<vendor_list.value.length; x++){
			if(document.getElementById("vendor_id"+x).value == vendor_details_id){
				var vendor_count = 1;
			}
		}

		if(vendor.value == ''){
			document.getElementById("SelectVendorMessage").style.display="block"
			document.getElementById("DuplicateVendorMessage").style.display="none"
		}else if(vendor_count == 1){
			document.getElementById("SelectVendorMessage").style.display="none"
			document.getElementById("DuplicateVendorMessage").style.display="block"
			vendor.value='';
		}else{
			const vendors = {
				vendor_details_id:vendor_details_id,
				vendor_name:vendor_name,
				identifier:identifier,
			}
			vendor.value='',
			vendor_list.value.push(vendors)
			document.getElementById("SelectVendorMessage").style.display="none"
			document.getElementById("DuplicateVendorMessage").style.display="none"
			document.getElementById('vendor_alert').style.backgroundColor = '#FEFCE8';
			document.getElementById("SaveandProceedBtn").disabled = false;
		}
	}

	const removeVendor = (index) => {
		vendor_list.value.splice(index,1)

		if(index > 0){
			document.getElementById("SaveandProceedBtn").disabled = false;
		}else{
			document.getElementById("SaveandProceedBtn").disabled = true;
		}
	}

	const SaveandProceedBtn= () => {
		if(rfq_name.value == ''){
			document.getElementById('rfqname_').placeholder='Please fill in RFQ Name.'
			document.getElementById('rfqname_').style.backgroundColor = '#FAA0A0';
		}else if(date_created.value == ''){
			document.getElementById('rfqname_').style.backgroundColor = '#FEFCE8';
			document.getElementById('rfqdate_').placeholder='Please fill in RFQ Date.'
			document.getElementById('rfqdate_').style.backgroundColor = '#FAA0A0';
		}else{
			document.getElementById('rfqdate_').style.backgroundColor = '#FEFCE8';
			SaveandProceedAlert.value = !SaveandProceedAlert.value
		}
	}

	const CancelSave = () => {
		SaveandProceedAlert.value = !hideModal.value
	}

	const SaveandProceed= () => {
		document.getElementById("YesNew").disabled = true;
		document.getElementById("NoNew").disabled = true;
		const formRFQ= new FormData()
			formRFQ.append('jor_head_id', JORHead.value.jor_head_id)
			formRFQ.append('jor_no', JORHead.value.jor_no)
			formRFQ.append('rfq_name', rfq_name.value)
			formRFQ.append('rfq_no', rfq_no.value)
			formRFQ.append('rfq_date', date_created.value)
			formRFQ.append('user_id', JORHead.value.user_id)
			formRFQ.append('rfq_labor', JSON.stringify(selected_labor.value))
			formRFQ.append('rfq_materials', JSON.stringify(selected_materials.value))
			formRFQ.append('rfq_vendors', JSON.stringify(vendor_list.value))
			axios.post("/api/add_jo_rfq", formRFQ).then(function (response) {
				router.push('/job_quote/print/'+response.data)
			});
	// }
	}

	const showModal = ref(false)
	const hideModal = ref(true)
	const JORNoAlert = ref(false)
	const SaveandProceedAlert = ref(false)
	// const openModel = () => {
	// 	showModal.value = !showModal.value
	// }
	const closeModal = () => {
		showModal.value = !hideModal.value
		JORNoAlert.value = !hideModal.value
		SaveandProceedAlert.value = !hideModal.value
		selected_labor.value=[]
		selected_materials.value=[]
		vendor.value=''
		document.getElementById('rfqname_').style.backgroundColor = '#FEFCE8';
		document.getElementById('rfqdate_').style.backgroundColor = '#FEFCE8';
		document.getElementById('vendor_alert').style.backgroundColor = '#FEFCE8';
		document.getElementById("DuplicateVendorMessage").style.display="none"
	}

	const jor_det = ref(false)
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Request for Quotation <small>New</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/job_quote">JO Request for Quotation</a></li>
                            <li class="breadcrumb-item active" aria-current="page">New</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
				<div class="card-body">
					<!-- <span class="pt-2">
						<h3 class="card-title !text-lg m-0">Request for Quotation <small>New</small></h3>
					</span> -->
					<hr class="border-dashed mt-0">
					<div class="pt-1">
						<div class="row">							
							<div class="col-lg-6 offset-lg-3 col-md-3">
								<div class="form-group">
								<label class="text-gray-500 m-0" for="">Choose JOR Number</label>
								<!-- <input type="file" name="img[]" class="file-upload-default"> -->
								<div class="input-group col-xs-12">
									<select class="form-control file-upload-info" v-model="jor_no">
										<option :value="jor.id" v-for="jor in jornolist" :key="jor.id">{{ jor.jor_no }} (Total RFQ:{{ (jor.count_jor_in_rfq != '') ? jor.count_jor_in_rfq : 0  }})</option>
									</select>
									<span class="input-group-append">
										<button class="btn btn-primary" type="button" @click="getJORDetails()">Select</button>
									</span>
								</div>
								</div>
							</div>
						</div>
						<hr class="border-dashed">
						<div style="display:none" id="display_details">
							<div class="row">
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">Job Order Request: </span>
									<span class="text-sm text-gray-700">{{ JORHead.location }}</span>
								</div>
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">Prepared Date: </span>
									<span class="text-sm text-gray-700">{{ JORHead.date_prepared }}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">JOR Number: </span>
									<span class="text-sm text-gray-700">{{ JORHead.site_jor }}</span>
								</div>
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">New JOR Number: </span>
									<span class="text-sm text-gray-700">{{ JORHead.jor_no }}</span>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">Department: </span>
									<span class="text-sm text-gray-700">{{ JORHead.department_name }}</span>
								</div>
								<div class="col-lg-4">
									<span class="text-sm text-gray-700 font-bold pr-1">Process Code: </span>
									<span class="text-sm text-gray-700">{{ JORHead.process_code }}</span>
								</div>
								<div class="col-lg-2">
									<span class="text-sm text-gray-700 font-bold pr-1">Urgency: </span>
									<span class="text-sm text-gray-700">{{ JORHead.urgency }}</span>
								</div>
							</div>
							<div class="row">
								<!-- <div class="col-lg-12">
									<span class="text-sm text-gray-700 font-bold pr-1">End-Use: </span>
									<span class="text-sm text-gray-700"></span>
								</div> -->
								<div class="col-lg-12">
									<span class="text-sm text-gray-700 font-bold pr-1">Purpose: </span>
									<span class="text-sm text-gray-700">{{ JORHead.purpose }}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<span class="text-sm text-gray-700 font-bold pr-1">Project/Activity: </span>
									<span class="text-sm text-gray-700">{{ JORHead.project_activity }}</span>
								</div>
							</div>
							
							<div class="row">
								<div class="col-lg-12">
									<table class="w-full table-bordered !text-xs mt-3" v-if="LaborDetails.length != 0">
										<tr class="bg-gray-100">
											<td class="p-1 uppercase text-center" width="2%">
												<input type="checkbox" id="checkalllabor" @click="CheckAllLabor" :checked="allSelectedLabor" v-model="all_labor_checkbox" :true-value="1" :false-value="0">
											</td>
											<td class="p-1 uppercase text-center" width="2%">#</td>
											<td class="p-1 uppercase" width="">Scope Of Works</td>
											<td class="p-1 uppercase text-center" width="10%">Qty</td>
											<td class="p-1 uppercase text-center" width="10%">UOM</td>
										</tr>
										<tr v-for="(ld, laborno) in LaborDetails">
											<input type="hidden" v-model="ld.jor_labor_details_id">
											<td class="p-1 text-center">
												<input type="checkbox" class='checkboxeslabor' v-model="ld.labor_checkbox" :checked="checkalllabor" :true-value="1" :false-value="0" @change="CountCheckbox">
											</td>
											<td class="p-1 text-center align-top">{{ laborno + 1 }}</td>
											<td class="p-1 align-top">{{ ld.scope_of_work }}</td>
											<td class="p-1 align-top text-center">{{ parseFloat(ld.quantity).toFixed(2) }}</td>
											<td class="p-1 align-top text-center">{{ ld.uom }}</td>
										</tr>
									</table>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-lg-12">
									<table class="w-full table-bordered !text-xs mb-3" v-if="MaterialDetails.length != 0">
										<tr class="bg-gray-100">
											<td class="p-1 uppercase text-center" width="2%">
												<input type="checkbox" id="checkallmaterial" @click="CheckAllMaterial" :checked="allSelectedMaterial" v-model="all_material_checkbox" :true-value="1" :false-value="0">
											</td>
											<td class="p-1 uppercase text-center" width="2%">#</td>
											<td class="p-1 uppercase text-center" width="7%">Qty</td>
											<td class="p-1 uppercase text-center" width="7%">UOM</td>
											<td class="p-1 uppercase" width="20%">PN No.</td>
											<td class="p-1 uppercase" width="">Item Description</td>
											<td class="p-1 uppercase" width="10%">WH Stocks</td>
											<td class="p-1 uppercase" width="15%">Date Needed</td>
										</tr>
										<tr v-for="(md, materialno) in MaterialDetails">
											<input type="hidden" v-model="md.jor_material_details_id">
											<td class="p-1 text-center">
												<input type="checkbox" class='checkboxesmaterial' v-model="md.material_checkbox" :checked="checkallmaterial" :true-value="1" :false-value="0" @change="CountCheckbox">
											</td>
											<td class="p-1 text-center align-top">{{ materialno + 1 }}</td>
											<td class="p-1 text-center">{{ parseFloat(md.quantity).toFixed(2) }}</td>
											<td class="p-1 text-center">{{ md.uom }}</td>
											<td class="p-1">{{ md.pn_no }}</td>
											<td class="p-1">{{ md.item_description }}</td>
											<td class="p-1">{{ md.wh_stocks }}</td>
											<td class="p-1">{{ md.date_needed }}</td>
										</tr>
									</table>
								</div>
							</div>
						
							<br>
							<div class="row my-2"> 
								<div class="col-lg-12 col-md-12">
									<div class="flex justify-center space-x-2">
										<button type="submit" @click="addItemsVendor()" id="AddVendorButton" style="display:none" class="btn btn-primary mr-2 w-44">Add Vendor</button>
									</div>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:JORNoAlert }">
				<div @click="closeModal" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h5 class="leading-tight">You must select JOR No!</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn btn-danger btn-sm !rounded-full w-full"  @click="closeModal()">Close</button>
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
			<div class="modal pt-4 px-3" :class="{ show:showModal }">
				<div @click="closeModal" class="w-full h-full fixed"></div>
				<div class="modal__content w-8/12">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">Add Vendor</span>
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
									<label class="text-gray-500 m-0" for="">RFQ Name</label>
									<input type="text" class="p-2 border w-full bg-yellow-50 text-sm" placeholder="RFQ Name" v-model="rfq_name" id="rfqname_">
								</div>
							</div>
							<div class="col-lg-6 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">RFQ No</label>
									<input type="text" class="form-control" placeholder="RFQ No" v-model="rfq_no" readonly>
								</div>
							</div>
							<div class="col-lg-6 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Date Created</label>
									<input type="text" class="p-2 border w-full bg-yellow-50 text-sm" placeholder="Date Created"  onfocus="(this.type='date')" v-model="date_created" id="rfqdate_">
								</div>
							</div>
						</div>
						<div class="row" v-if="selected_labor != ''">
							<div class="col-lg-12">
								<table class="w-full table-bordered !text-xs mt-3">
									<tr class="bg-gray-100">
										<td class="p-1 uppercase text-center" width="2%">#</td>
										<td class="p-1 uppercase" width="">Scope Of Works</td>
										<td class="p-1 uppercase text-center" width="10%">Qty</td>
										<td class="p-1 uppercase text-center" width="10%">UOM</td>
									</tr>
									<tr v-for="(sl, i) in selected_labor">
										<input type="hidden" v-model="sl.jor_labor_details_id">
										<td class="p-1 text-center align-top">{{ i + 1 }}</td>
										<td class="p-1 align-top">{{ sl.scope_of_work }}</td>
										<td class="p-1 align-top text-center">{{ parseFloat(sl.quantity).toFixed(2) }}</td>
										<td class="p-1 align-top text-center">{{ sl.uom }}</td>
									</tr>
								</table>
							</div>
						</div>
						<br>
						<div class="row" v-if="selected_materials != ''">
							<div class="col-lg-12">
								<table class="w-full table-bordered !text-xs mb-3">
									<tr class="bg-gray-100">
										<td class="p-1 uppercase text-center" width="2%">#</td>
										<td class="p-1 uppercase text-center" width="7%">Qty</td>
										<td class="p-1 uppercase text-center" width="7%">UOM</td>
										<td class="p-1 uppercase" width="20%">PN No.</td>
										<td class="p-1 uppercase" width="">Item Description</td>
										<td class="p-1 uppercase" width="15%">Date Needed</td>
									</tr>
									<tr v-for="(sm, m) in selected_materials">
										<input type="hidden" v-model="sm.jor_material_details_id">
										<td class="p-1 text-center align-top">{{ m + 1 }}</td>
										<td class="p-1 text-center">{{ parseFloat(sm.quantity).toFixed(2) }}</td>
										<td class="p-1 text-center">{{ sm.uom }}</td>
										<td class="p-1">{{ sm.pn_no }}</td>
										<td class="p-1">{{ sm.item_description }}</td>
										<td class="p-1">{{ sm.date_needed }}</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="bg-blue-100 border-2 border-blue-200 w-full p-2 text-blue-400 my-1 mb-2 hidden"  id="SelectVendorMessage">
							<div class="flex justify-start space-x-2">
								<ExclamationTriangleIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></ExclamationTriangleIcon>
								<span>Please select vendor!</span>
							</div>
						</div>
						<div class="bg-red-100 border-2 border-red-200 w-full p-2 text-red-500 my-1 mb-2 hidden"  id="DuplicateVendorMessage">
							<div class="flex justify-start space-x-2">
								<ExclamationTriangleIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></ExclamationTriangleIcon>
								<span>Vendor is already existing!</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-12">
								<table class="table-bordered !w-full"  >
									<tr class="bg-gray-100">
										<td class="p-1 px-2 text-sm">Add Vendor</td>
										<td class="p-1 space-x-auto uppercase text-center" align="center" width="1%">
											<Bars3Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></Bars3Icon>
										</td>
									</tr>
									<tr>
										<select class="p-2 border w-full bg-yellow-50 text-sm" v-model="vendor" id="vendor_alert">
											<option :value="v.vendor_details_id+'_'+v.vendor_head_id+'_'+v.vendor_name+'_'+v.identifier" v-for="v in vendorlist" :key="v.vendor_details_id">{{ v.vendor_name }} {{ (v.identifier != '') ? '('+v.identifier+')' : '' }}</option>
										</select>
										<td class="text-center">
											<button type="button" class="btn btn-primary p-1" @click="addVendor">
												<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
											</button>
										</td>
									</tr>
									<tr v-for="(v,index) in vendor_list">
										<td class="text-sm p-1 px-2">
											{{ v.vendor_name }} {{ v.identifier }}
											<input type="hidden" class="p-1 m-0 w-full leading-none" :id="'v_name'+index" v-model="v.vendor_name"/>
											<input type="hidden" class="p-1 m-0 w-full leading-none" v-model="v.identifier"/>
											<input type="hidden" class="p-1 m-0 w-full leading-none" :id="'vendor_id'+index" v-model="v.vendor_details_id"/>
										</td>
										<td class="text-center">
											<button class="btn btn-danger p-1" @click="removeVendor(index)">
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
											</button>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <button type="submit" @click="openModel()" class="btn btn-info mr-2 w-44">Create New RFQ</button> -->
									<button type="submit" @click="closeModal()" class="btn btn-danger mr-2 w-44">Close</button>
									<button type="submit" id="SaveandProceedBtn" @click="SaveandProceedBtn()" class="btn btn-primary mr-2 w-44" disabled>Save and Proceed</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:SaveandProceedAlert }">
				<div @click="CancelSave" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h2 class="mb-2  font-bold text-green-400">Confirmation!</h2>
									<h5 class="leading-tight">Are you sure you want to save and proceed?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full" id= "NoNew" @click="CancelSave()">No</button>
									<button class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full" id= "YesNew" @click="SaveandProceed()">Yes</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</Transition>
	</navigation>
</template>