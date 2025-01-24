<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, CheckIcon, XMarkIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref, onMounted, watch } from "vue"
    import { useRouter } from "vue-router"
	import moment from 'moment'
	const error =  ref('');
	const success =  ref('');
	const joi_dropdown =  ref([]);
	const po_det = ref(false)
	const save_button =  ref()
	const print_button =  ref(false)
	const hide_printButton = ref()
	const successAlert = ref(false)
	const successAlertReceived = ref(false)
	const dangerAlerterrors = ref(false)
	const hideAlert = ref(true)
	const joi_head_id = ref(0)
	const to_deliver_labor = ref([])
	const to_deliver_material = ref([])
	const enduse = ref('')
	const purpose = ref('')
	const requestor = ref('')
	const project_activity = ref('')
	const general_description = ref('')
	const prepared_by = ref('')
	const driver = ref('')
	const dr_no = ref('')
	const joi_dr_id = ref(0)
	const count_joi_head_id = ref(0)
	const total_labor_sumdelivered = ref(0)
	const total_labor_sumdelivered1 = ref([])
	const total_material_sumdelivered = ref(0)
	const total_material_sumdelivered1 = ref([])
	const joi_dr =  ref([]);
	const joi_dr_mult =  ref([]);
	const joi_dr_labor =  ref([]);
	const joi_dr_material =  ref([]);
	const joi_vendor =  ref([]);
	const offer_labor =  ref([]);
	const offer_material =  ref([]);
	const uom_labor = ref([])
	const uom_material = ref([])
	const remaining_labor_delivery = ref([])
	const remaining_material_delivery = ref([])
	const received_qty = ref([])
	const received_material_qty = ref([])
	const isDriverReadonly = ref(false);
	const isLoading = ref(true);
	const props = defineProps({
		id:{
			type:String,
			default:''
		}
	})
	onMounted(async () => {
		
		if(props.id!=0){
			await drLoad()
		}
		await getJOi()
		isLoading.value = false; 
	})
	const isNumber = (evt)=> {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode == 46) {
			//Check if the text already contains the . character
			if (evt.target.value.indexOf('.') === -1) {
				return true;
			} else {
				evt.preventDefault();
			}
		} else {
			if (charCode > 31 && (charCode < 48 || charCode > 57))
				evt.preventDefault();
		}
		return true;
    }
	const getJOi = async () => {
		let response = await axios.get("/api/joi_dropdown");
		joi_dropdown.value = response.data.joi_dropdown;
	}

	const drLoad = async () => {
		let response = await axios.get("/api/generate_jo_dr/"+props.id);
		dr_no.value = response.data.dr_no;
		count_joi_head_id.value = response.data.count_joi_head_id;
		joi_dr.value = response.data.joi_dr;
		if(joi_dr.value.identifier!=0 && joi_dr.value.print_identifier!=0 && joi_dr.value.received==0 && (joi_dr.value.driver!='' || joi_dr.value.driver!='null')){
			driver.value=joi_dr.value.driver
			isDriverReadonly.value = true;
		}
		joi_dr_mult.value = response.data.joi_dr_mult;
		joi_dr_labor.value = response.data.joi_dr_labor;
		joi_dr_material.value = response.data.joi_dr_material;
		joi_vendor.value = response.data.joi_vendor;
		purpose.value = response.data.purpose;
		requestor.value = response.data.requestor;
		project_activity.value = response.data.project_activity;
		general_description.value = response.data.general_description;
		prepared_by.value = response.data.prepared_by;
		total_labor_sumdelivered.value = response.data.total_sumdelivered;
		total_material_sumdelivered.value = response.data.total_material_sumdelivered;
		joi_dr_labor.value.forEach(function (val, index, theArray) {
			getLaborOffer(val.joi_labor_details_id,val.jo_rfq_labor_offer_id,index)
			checkRemainingLaborQty(val.joi_labor_details_id,val.quantity,index)
		});
		joi_dr_material.value.forEach(function (val, index, theArray) {
			getMaterialOffer(val.joi_material_details_id,val.jo_rfq_material_offer_id,index)
			checkRemainingMaterialQty(val.joi_material_details_id,val.quantity,index)
		});
	}
	const generateJODR = async () => {
		let response = await axios.get("/api/generate_jo_dr/"+joi_head_id.value);
		dr_no.value = response.data.dr_no;
		count_joi_head_id.value = response.data.count_joi_head_id;
		joi_dr.value = response.data.joi_dr;
		if(joi_dr.value.identifier!=0 && joi_dr.value.print_identifier!=0 && joi_dr.value.received==0 && (joi_dr.value.driver!='' || joi_dr.value.driver!='null')){
			driver.value=joi_dr.value.driver
			isDriverReadonly.value = true;
		}else if(joi_dr.value.driver!='' || joi_dr.value.driver!='null'){
			driver.value=''
			isDriverReadonly.value = false;
		}
		joi_dr_mult.value = response.data.joi_dr_mult;
		joi_dr_labor.value = response.data.joi_dr_labor;
		joi_dr_material.value = response.data.joi_dr_material;
		joi_vendor.value = response.data.joi_vendor;
		purpose.value = response.data.purpose;
		requestor.value = response.data.requestor;
		project_activity.value = response.data.project_activity;
		general_description.value = response.data.general_description;
		prepared_by.value = response.data.prepared_by;
		total_labor_sumdelivered.value = response.data.total_sumdelivered;
		total_material_sumdelivered.value = response.data.total_material_sumdelivered;
		joi_dr_labor.value.forEach(function (val, index, theArray) {
			getLaborOffer(val.joi_labor_details_id,val.jo_rfq_labor_offer_id,index)
			checkRemainingLaborQty(val.joi_labor_details_id,val.quantity,index)
		});
		joi_dr_material.value.forEach(function (val, index, theArray) {
			getMaterialOffer(val.joi_material_details_id,val.jo_rfq_material_offer_id,index)
			checkRemainingMaterialQty(val.joi_material_details_id,val.quantity,index)
		});
	}

	const getLaborOffer = async (joi_labor_details_id,jo_rfq_labor_offer_id,count) => {
		let response = await axios.get("/api/get_offer_labor/"+joi_labor_details_id+'/'+jo_rfq_labor_offer_id);
		if(jo_rfq_labor_offer_id!=0 && jo_rfq_labor_offer_id!=''){
			offer_labor.value[count] = response.data.offer.offer;
			uom_labor.value[count] = response.data.offer.uom;
		}else{
			offer_labor.value[count] = response.data.offer.item_description;
			uom_labor.value[count] = response.data.offer.uom;
		}
	}

	const getMaterialOffer = async (joi_material_details_id,jo_rfq_material_offer_id,count) => {
		let response = await axios.get("/api/get_offer_material/"+joi_material_details_id+'/'+jo_rfq_material_offer_id);
		if(jo_rfq_material_offer_id!=0 && jo_rfq_material_offer_id!=''){
			offer_material.value[count] = response.data.offer.offer;
			uom_material.value[count] = response.data.offer.uom;
		}else{
			offer_material.value[count] = response.data.offer.item_description;
			uom_material.value[count] = response.data.offer.uom;
		}
	}

	const checkLaborBalance = async (joi_dr_id,joi_labor_details_id,qty,count) => {
		let response = await axios.get("/api/check_jo_labor_dr_balance/"+joi_dr_id+"/"+joi_labor_details_id);
		if(qty>response.data.balance){
			document.getElementById('balance_labor_checker'+count).style.backgroundColor = '#FAA0A0';
			const btn_save = document.getElementById("save");
			btn_save.disabled = true;
		}else{
			document.getElementById('balance_labor_checker'+count).style.backgroundColor = '#FEFCE8';
			const btn_save = document.getElementById("save");
			btn_save.disabled = false;
		}
	}
	const checkBalanceRec = async (joi_dr_id,joi_labor_details_id,qty,count) => {
		let response = await axios.get("/api/check_jo_labor_dr_balance/"+joi_dr_id+"/"+joi_labor_details_id);
		if(qty>response.data.delivered_qty){
			document.getElementById('balance_rec_checker'+count).style.backgroundColor = '#FAA0A0';
			const btn_save = document.getElementById("save");
			btn_save.disabled = true;
		}else{
			document.getElementById('balance_rec_checker'+count).style.backgroundColor = '#FEFCE8';
			const btn_save = document.getElementById("save");
			btn_save.disabled = false;
		}
	}

	const checkMaterialBalance = async (joi_dr_id,joi_material_details_id,qty,count) => {
		let response = await axios.get("/api/check_jo_material_dr_balance/"+joi_dr_id+"/"+joi_material_details_id);
		if(qty>response.data.balance){
			document.getElementById('balance_material_checker'+count).style.backgroundColor = '#FAA0A0';
			const btn_save = document.getElementById("save");
			btn_save.disabled = true;
		}else{
			document.getElementById('balance_material_checker'+count).style.backgroundColor = '#FEFCE8';
			const btn_save = document.getElementById("save");
			btn_save.disabled = false;
		}
	}

	const checkBalanceMaterialRec = async (joi_dr_id,joi_material_details_id,qty,count) => {
		let response = await axios.get("/api/check_jo_material_dr_balance/"+joi_dr_id+"/"+joi_material_details_id);
		if(qty>response.data.delivered_qty){
			document.getElementById('balance_rec_material_checker'+count).style.backgroundColor = '#FAA0A0';
			const btn_save = document.getElementById("save");
			btn_save.disabled = true;
		}else{
			document.getElementById('balance_rec_material_checker'+count).style.backgroundColor = '#FEFCE8';
			const btn_save = document.getElementById("save");
			btn_save.disabled = false;
		}
	}

	const checkRemainingLaborQty = async (joi_labor_details_id,qty,count) => {
		let response = await axios.get("/api/check_remaining_dr_labor_balance/"+joi_labor_details_id);
		total_labor_sumdelivered.value = response.data.balance_sum
		total_labor_sumdelivered1.value[count] = response.data.balance_sum
		to_deliver_labor.value[count]= parseFloat(qty)  - total_labor_sumdelivered1.value[count]
		remaining_labor_delivery[count]= parseFloat(qty)  - total_labor_sumdelivered1.value[count]
	}

	const checkRemainingMaterialQty = async (joi_material_details_id,qty,count) => {
		let response = await axios.get("/api/check_remaining_dr_material_balance/"+joi_material_details_id);
		total_material_sumdelivered.value = response.data.balance_sum
		total_material_sumdelivered1.value[count] = response.data.balance_sum
		to_deliver_material.value[count]= parseFloat(qty)  - total_material_sumdelivered1.value[count]
		remaining_material_delivery[count]= parseFloat(qty)  - total_material_sumdelivered1.value[count]
	}

	watch(isLoading, (newValue) => {
		
	});

	const onSave = () => {
		const formData= new FormData()
		formData.append('identifier', joi_dr.value.identifier)
		formData.append('dr_no', dr_no.value)
		formData.append('count_joi_head_id', count_joi_head_id.value)
		formData.append('joi_dr_id', joi_dr.value.id)
		formData.append('total_sumdelivered', total_labor_sumdelivered.value)
		formData.append('total_material_sumdelivered', total_material_sumdelivered.value)
		formData.append('driver', driver.value)
		formData.append('joi_dr', JSON.stringify(joi_dr_mult.value))
		formData.append('joi_dr_labor', JSON.stringify(joi_dr_labor.value))
		formData.append('joi_dr_material', JSON.stringify(joi_dr_material.value))
		joi_dr_labor.value.forEach(function (val, index, theArray) {
			formData.append('to_deliver_labor'+index, to_deliver_labor.value[index])
			formData.append('joi_labor_details_id'+index, val.joi_labor_details_id)
			formData.append('remaining_labor_qty'+index, remaining_labor_delivery[index])
		});
		joi_dr_material.value.forEach(function (vals, indexes, theArray) {
			formData.append('to_deliver_material'+indexes, to_deliver_material.value[indexes])
			formData.append('joi_material_details_id'+indexes, vals.joi_material_details_id)
			formData.append('remaining_material_qty'+indexes, remaining_material_delivery[indexes])
		});
		if(driver.value!=''){
			axios.post(`/api/save_jo_dr`,formData).then(function (response) {
				joi_dr_id.value=response.data;
				success.value='You have successfully saved new jo dr.'
				successAlert.value=!successAlert.value
			}, function (err) {
				error.value='Error! Please try again.';
				dangerAlerterrors.value=!dangerAlerterrors.value
			}); 
		}else{
			document.getElementById('driver').placeholder="Driver field must not be empty!"
			document.getElementById('driver').style.backgroundColor = '#FAA0A0';
			const btn_save = document.getElementById("save");
			btn_save.disabled = true;
		}
    }

	const saveReceived = () => {
		const formData= new FormData()
		formData.append('joi_dr_id', joi_dr.value.id)
		formData.append('driver', driver.value)
		formData.append('joi_dr_labor', JSON.stringify(joi_dr_labor.value))
		formData.append('joi_dr_material', JSON.stringify(joi_dr_material.value))
		joi_dr_labor.value.forEach(function (val, index, theArray) {
			formData.append('received_qty'+index, received_qty.value[index])
			formData.append('remaining_labor_qty'+index, remaining_labor_delivery[index])
		});
		joi_dr_material.value.forEach(function (vals, indexes, theArray) {
			formData.append('received_material_qty'+indexes, received_material_qty.value[indexes])
			formData.append('remaining_material_qty'+indexes, remaining_material_delivery[indexes])
		});
		// if(driver.value!=''){
		axios.post(`/api/save_jo_received_dr`,formData).then(function (response) {
			joi_dr_id.value=response.data;
			success.value='You have successfully saved dr.'
			// successAlert.value=!successAlert.value
			successAlertReceived.value=!successAlertReceived.value
		}, function (err) {
			error.value='Error! Please try again.';
			dangerAlerterrors.value=!dangerAlerterrors.value
		}); 
		// }else{
		// 	document.getElementById('driver').placeholder="Driver field must not be empty!"
		// 	document.getElementById('driver').style.backgroundColor = '#FAA0A0';
		// 	const btn_save = document.getElementById("save");
		// 	btn_save.disabled = true;
		// }
    }

	const resetError = () => {
		document.getElementById('driver').style.backgroundColor = '#FEFCE8';
		const btn_save = document.getElementById("save");
		btn_save.disabled = false;
	}

	const allZero = () => {
		return to_deliver_labor.value.every(value => value === 0);
    }

	const allZeroMaterial = () => {
		return to_deliver_material.value.every(value => value === 0);
    }

	const openSuccessAlert = () => {
		successAlert.value = !successAlert.value
	}
	const closeSuccessAlert = () => {
		successAlert.value = !hideAlert.value
		print_button.value = !print_button.value
		save_button.value = hideAlert.value
	}

	const closeAlert = () => {
		dangerAlerterrors.value = !hideAlert.value
	}

</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Delivery Receipt <small>New</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/job_dr">JO Delivery Receipt</a></li>
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
						<hr class="border-dashed mt-0">
						<div class="pt-1">
							<div class="row">							
								<div class="col-lg-6 offset-lg-3 col-md-3">
									<div class="form-group">
									<label class="text-gray-500 m-0" for="">Choose JOI Number</label>
									<div class="input-group col-xs-12">
										<select class="form-control file-upload-info" v-model="joi_head_id">
											<option value="0">Select JOI</option>
											<option :value="j.joi_head_id" v-for="j in joi_dropdown" :key="j.joi_head_id">{{ j.joi_no }}{{ (j.revision_no!=0 && j.revision_no!=null) ? '.r'+j.revision_no : '' }}</option>
										</select>
										<span class="input-group-append">
											<button class="btn btn-primary" type="button" @click="generateJODR()">Select</button>
											<!-- <button class="btn btn-primary" type="button" @click="po_det = !po_det">Select</button> -->
										</span>
									</div>
									</div>
								</div>
							</div>
							<hr class="border-dashed">
							<div v-if="joi_dr && joi_dr.length!=0 && (!allZero() || !allZeroMaterial())">
								<div class="row">
									<div class="col-lg-8">
										<input type="hidden" v-model="dr_no">
										<span class="text-sm text-gray-700 font-bold pr-1">JOI No: </span>
										<span class="text-sm text-gray-700">{{joi_dr.joi_no}}{{ (joi_dr.revision_no!=0 && joi_dr.revision_no!=null && joi_dr.revision_no!='') ? '.r'+joi_dr.revision_no : '' }}</span>
									</div>
									<div class="col-lg-4">
										<span class="text-sm text-gray-700 font-bold pr-1">DR No: </span>
										<span class="text-sm text-gray-700">{{ (joi_dr.identifier!=0) ? dr_no : joi_dr.dr_no}}</span>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-8">
										<span class="text-sm text-gray-700 font-bold pr-1">JOR No: </span>
										<span class="text-sm text-gray-700">{{joi_dr.jor_no}}</span>
									</div>
									<div class="col-lg-4">
										<span class="text-sm text-gray-700 font-bold pr-1">Date: </span>
										<span class="text-sm text-gray-700">{{moment(joi_dr.dr_date).format('MMM. DD,YYYY')}}</span>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-4">
										<span class="text-sm text-gray-700 font-bold pr-1">Requestor: </span>
										<span class="text-sm text-gray-700">{{requestor}}</span>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<span class="text-sm text-gray-700 font-bold pr-1">Purpose:</span>
										<span class="text-sm text-gray-700">{{purpose}}</span>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<span class="text-sm text-gray-700 font-bold pr-1">Project Title:</span>
										<span class="text-sm text-gray-700">{{project_activity}}</span>
									</div>
								</div>						
								<div class="row">
									<div class="col-lg-12">
										<table class="w-full table-bordered !text-xs mt-3">
											<tr class="bg-gray-100">
												<td class="p-1 uppercase text-center" width="2%">#</td>
												<td class="p-1 uppercase text-center" width="25%">Supplier</td>
												<td class="p-1 uppercase text-center" width="25%">Description</td>
												<td class="p-1 uppercase text-center" width="7%">To Deliver</td>
												<td class="p-1 uppercase text-center" width="8%">DLVRD Qty</td>
												<td class="p-1 uppercase text-center" width="5%">Received</td>
												<td class="p-1 uppercase text-center" width="5%">UOM</td>
												<td class="p-1 uppercase text-center" width="5%">Remarks</td>
											</tr>
											<tr >
												<td colspan="6"><span class="font-bold">{{ general_description}} </span></td>
											</tr>
											<tr v-for="(jdl,index) in joi_dr_labor">
												<td class="p-1 text-center">{{index+1}} </td>
												<td class="p-1 ">{{joi_vendor.vendor_name}} ({{ joi_vendor.identifier }})</td>
												<td class="p-1 ">{{  offer_labor[index] }}</td>
												<td class="p-0" v-if="joi_dr.print_identifier==0 && joi_dr.received==0">
													<input type="text" min="0" @keypress="isNumber($event)" @keyup="checkLaborBalance(jdl.joi_dr_id,jdl.joi_labor_details_id,to_deliver_labor[index],index)" class="w-full p-1 bg-orange-50 text-center" :id="'balance_labor_checker'+index" v-model="to_deliver_labor[index]">
												</td>
												<td class="p-0" v-else-if="joi_dr.identifier==1 && joi_dr.print_identifier==1 && joi_dr.received==1">
													<input type="text" min="0" @keypress="isNumber($event)" @keyup="checkLaborBalance(jdl.joi_dr_id,jdl.joi_labor_details_id,to_deliver_labor[index],index)" class="w-full p-1 bg-orange-50 text-center" :id="'balance_labor_checker'+index" v-model="to_deliver_labor[index]">
												</td>
												<td class="p-1 text-center" v-else>
													<input type="hidden" min="0" @keypress="isNumber($event)" @keyup="checkLaborBalance(jdl.joi_dr_id,jdl.joi_labor_details_id,to_deliver_labor[index],index)" class="w-full p-1 bg-orange-50 text-center" :id="'balance_labor_checker'+index" v-model="to_deliver_labor[index]">
													{{jdl.delivered_qty}}
												</td>
												<td class="p-1 text-center">{{ total_labor_sumdelivered1[index] }}</td>
												<td class="p-1 text-center" v-if="joi_dr.print_identifier==0 && joi_dr.received==0"></td>
												<td class="p-1 text-center" v-else-if="joi_dr.print_identifier==1 && joi_dr.received==1"></td>
												<td class="p-1 text-center" v-else>
													<input type="text" min="0" @keypress="isNumber($event)" @keyup="checkBalanceRec(jdl.joi_dr_id,jdl.joi_labor_details_id,received_qty[index],index)" class="w-full p-1 bg-orange-50 text-center" :id="'balance_rec_checker'+index" v-model="received_qty[index]">
												</td>
												<td class="p-1 text-center">{{ uom_labor[index] }}</td>
												<td class="p-1 text-center"></td>
											</tr>
											<tr class="bg-gray-100">
												<td class="p-1 font-bold" colspan="6">Materials:</td>
											</tr>
											<tr v-for="(jdm,indexes) in joi_dr_material">
												<td class="p-1 text-center">{{indexes+1}}</td>
												<td class="p-1 ">{{joi_vendor.vendor_name}} ({{ joi_vendor.identifier }})</td>
												<td class="p-1 ">{{ offer_material[indexes] }}</td>
												<td class="p-0" v-if="joi_dr.print_identifier==0 && joi_dr.received==0">
													<input type="text" min="0" @keypress="isNumber($event)" @keyup="checkMaterialBalance(jdm.joi_dr_id,jdm.joi_material_details_id,to_deliver_material[indexes],indexes)" class="w-full p-1 bg-orange-50 text-center" :id="'balance_material_checker'+indexes" v-model="to_deliver_material[indexes]">
												</td>
												<td class="p-0" v-else-if="joi_dr.identifier==1 && joi_dr.print_identifier==1 && joi_dr.received==1">
													<input type="text" min="0" @keypress="isNumber($event)" @keyup="checkMaterialBalance(jdm.joi_dr_id,jdm.joi_material_details_id,to_deliver_material[indexes],indexes)" class="w-full p-1 bg-orange-50 text-center" :id="'balance_material_checker'+indexes" v-model="to_deliver_material[indexes]">
												</td>
												<td class="p-1 text-center" v-else>
													<input type="hidden" min="0" @keypress="isNumber($event)" @keyup="checkMaterialBalance(jdm.joi_dr_id,jdm.joi_material_details_id,to_deliver_material[indexes],indexes)" class="w-full p-1 bg-orange-50 text-center" :id="'balance_material_checker'+indexes" v-model="to_deliver_material[indexes]">
													{{jdm.delivered_qty}}
												</td>
												<td class="p-1 text-center">{{ total_material_sumdelivered1[indexes] }}</td>
												<td class="p-1 text-center" v-if="joi_dr.print_identifier==0 && joi_dr.received==0"></td>
												<td class="p-1 text-center" v-else-if="joi_dr.print_identifier==1 && joi_dr.received==1"></td>
												<td class="p-1 text-center" v-else>
													<input type="text" min="0" @keypress="isNumber($event)" @keyup="checkBalanceMaterialRec(jdm.joi_dr_id,jdm.joi_material_details_id,received_material_qty[indexes],indexes)" class="w-full p-1 bg-orange-50 text-center" :id="'balance_rec_material_checker'+indexes" v-model="received_material_qty[indexes]">
												</td>
												<td class="p-1 text-center">{{ uom_material[indexes] }}</td>
												<td class="p-1 text-center"></td>
											</tr>
										</table>
									</div>
								</div>
								<br>
								<div class="row mt-4 mb-4">
									<div class="col-lg-12">
										<table class="w-full text-xs">
											<tr>
												<td width="10%"></td>
												<td class="text-center" width="20%">Prepared by</td>
												<td width="10%"></td>
												<td class="text-center" width="20%">Driver</td>
												<td width="10%"></td>	
											</tr>
											<tr>
												<td></td>
												<td class="text-center border-b"><br><br></td>
												<td></td>
												<td class="text-center border-b"></td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td class="text-center p-1">{{prepared_by}}</td>
												<td></td>
												<td class="p-0 "><input type="text" id="driver"  class="w-full bg-yellow-50 p-1 text-center" v-model="driver" @click="resetError()" :readonly="isDriverReadonly"></td>
												<td></td>
											</tr>
										</table>
									</div>
								</div>
								<div class="row mt-4 mb-4">
									<div class="col-lg-12">
										<table class="w-full text-xs">
											<tr>
												<td class="text-center" width="20%">Received by</td>
												<td width="2%"></td>
												<td class="text-center" width="20%">Complete & Accepted by Enduser</td>
												<td width="2%"></td>
												<td class="text-center" width="20%">Witnessed by</td>
											</tr>
											<tr>
												<td class="text-center border-b"><br><br></td>
												<td></td>
												<td class="text-center border-b"></td>
												<td></td>
												<td class="text-center border-b"></td>
											</tr>
											<tr>
												<td class="text-center p-1">Print Name & Signature with Date Received</td>
												<td></td>
												<td class="text-center p-1">Print Name & Signature with Date Received</td>
												<td></td>
												<td class="text-center p-1">Print Name & Signature with Date Received</td>
											</tr>
											<tr>
												<td class="text-center"><br><br></td>
												<td></td>
												<td class="text-center"></td>
												<td></td>
												<td class="text-center"></td>
											</tr>
										</table>
									</div>
								</div>
								<!-- <hr class="border-dashed"> -->
								<div>
									<div class="row my-2"> 
										<div class="col-lg-12 col-md-12">
											<div class="flex justify-center space-x-2">
												<!-- <button type="button" class="btn btn-primary mr-2 w-36" @click="onSave()" id="save">Save</button> -->
												<button type="button" class="btn btn-primary mr-2 w-36" @click="onSave()" id="save" v-if="joi_dr.print_identifier==0 && joi_dr.identifier==0 && joi_dr.received==0">Create DR</button>
												<button type="button" class="btn btn-primary mr-2 w-36" @click="onSave()" id="save" v-else-if="joi_dr.print_identifier==1 && joi_dr.identifier==1 && joi_dr.received==1">Create DR</button>
												<button type="button" class="btn btn-primary mr-2 w-36" @click="saveReceived()" id="save" v-else>Save</button>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div v-else-if="to_deliver_labor.length==0 && to_deliver_material.length==0 && (allZero() || allZeroMaterial())">
								<center><span><b>Fully Delivered!</b></span></center>
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
									<h5 class="leading-tight">You have successfully created a DR.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <a href="/job_po/view" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Back to JOI</a> -->
									<a :href="'/job_issue/view/'+props.id" class="btn !bg-gray-100 btn-sm !rounded-full w-full" v-if="props.id!=0">Back to JOI</a>
									<a href="/job_dr/new/0" class="btn !bg-gray-100 btn-sm !rounded-full w-full" v-else>Create New</a>
									<a :href="'/job_dr/view/'+joi_dr_id" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close & Print</a>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:successAlertReceived }">
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
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <a href="/job_po/view" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Back to JOI</a> -->
									<a :href="'/job_issue/view/'+props.id" class="btn !bg-gray-100 btn-sm !rounded-full w-full" v-if="props.id!=0">Back to JOI</a>
									<a href="/job_dr/new/0" class="btn !bg-gray-100 btn-sm !rounded-full w-full" v-else>Create New</a>
									<!-- <a :href="'/job_dr/view/'+joi_dr_id" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close & Print</a> -->
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlerterrors }">
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
									<h5 class="leading-tight" v-if="error!=''" >{{ error }}</h5>
									<!-- <h5 class="leading-tight" v-else-if="error_inventory!=''">{{ error_inventory }}</h5>
									<h5 class="leading-tight" v-else-if="error_pr!=''" v-for="er in error_pr">{{ er }}</h5> -->
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