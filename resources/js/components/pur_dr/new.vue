<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, CheckIcon, XMarkIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref, onMounted } from "vue"
    import { useRouter } from "vue-router"
	import moment from 'moment'
	const error =  ref('');
	const success =  ref('');
	const po_dropdown =  ref([]);
	const po_det = ref(false)
	const save_button =  ref()
	const print_button =  ref(false)
	const hide_printButton = ref()
	const successAlert = ref(false)
	const hideAlert = ref(true)
	const po_head_id = ref(0)
	const to_deliver = ref([])
	const enduse = ref('')
	const purpose = ref('')
	const requestor = ref('')
	const prepared_by = ref('')
	const driver = ref('')
	const dr_no = ref('')
	const po_dr_id = ref(0)
	const total_sumdelivered = ref(0)
	const po_dr =  ref([]);
	const po_dr_mult =  ref([]);
	const po_dr_items =  ref([]);
	const vendor =  ref([]);
	const offer =  ref([]);
	const uom = ref([])
	const props = defineProps({
		id:{
			type:String,
			default:''
		}
	})
	onMounted(async () => {
		getPO()
		if(props.id!=0){
			drLoad()
		}
	})
	const openSuccessAlert = () => {
		successAlert.value = !successAlert.value
	}
	const closeSuccessAlert = () => {
		successAlert.value = !hideAlert.value
		print_button.value = !print_button.value
		save_button.value = hideAlert.value
	}
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
	const getPO = async () => {
		let response = await axios.get("/api/po_dropdown");
		po_dropdown.value = response.data.po_dropdown;
	}
	const drLoad = async () => {
		let response = await axios.get("/api/generate_dr/"+props.id);
		dr_no.value = response.data.dr_no;
		po_dr.value = response.data.po_dr;
		po_dr_items.value = response.data.po_dr_items;
		vendor.value = response.data.vendor;
		enduse.value = response.data.enduse;
		purpose.value = response.data.purpose;
		requestor.value = response.data.requestor;
		prepared_by.value = response.data.prepared_by;
		total_sumdelivered.value = response.data.total_sumdelivered;
		po_dr_items.value.forEach(function (val, index, theArray) {
			getOffer(val.rfq_offer_id,index)
			to_deliver.value[index]= parseFloat(val.quantity)  - total_sumdelivered.value
		});
	}
	const generateDR = async () => {
		let response = await axios.get("/api/generate_dr/"+po_head_id.value);
		dr_no.value = response.data.dr_no;
		po_dr.value = response.data.po_dr;
		po_dr_items.value = response.data.po_dr_items;
		vendor.value = response.data.vendor;
		enduse.value = response.data.enduse;
		purpose.value = response.data.purpose;
		requestor.value = response.data.requestor;
		prepared_by.value = response.data.prepared_by;
		total_sumdelivered.value = response.data.total_sumdelivered;
		po_dr_items.value.forEach(function (val, index, theArray) {
			getOffer(val.rfq_offer_id,index)
			to_deliver.value[index]= parseFloat(val.quantity)  - total_sumdelivered.value
			// to_deliver.value[index]= parseFloat(val.quantity) - parseFloat(val.delivered_qty)
		});
	}

	const getOffer = async (rfq_offer_id,count) => {
		let response = await axios.get("/api/get_offer/"+rfq_offer_id);
		offer.value[count] = response.data.offer.offer;
		uom.value[count] = response.data.offer.uom;
	}
	
	const checkBalance = async (po_dr_id,po_details_id,qty,count) => {
		let response = await axios.get("/api/check_dr_balance/"+po_dr_id+"/"+po_details_id);
		if(qty>response.data.balance){
			document.getElementById('balance_checker'+count).style.backgroundColor = '#FAA0A0';
			const btn_save = document.getElementById("save");
			btn_save.disabled = true;
		}else{
			document.getElementById('balance_checker'+count).style.backgroundColor = '#FEFCE8';
			const btn_save = document.getElementById("save");
			btn_save.disabled = false;
		}
	}

	const onSave = () => {
		
		const formData= new FormData()
		formData.append('dr_no', dr_no.value)
		formData.append('po_dr_id', po_dr.value.id)
		formData.append('total_sumdelivered', total_sumdelivered.value)
		formData.append('driver', driver.value)
		formData.append('po_dr', JSON.stringify(po_dr_mult.value))
		formData.append('po_dr_items', JSON.stringify(po_dr_items.value))
		po_dr_items.value.forEach(function (val, index, theArray) {
			formData.append('to_deliver'+index, to_deliver.value[index])
			formData.append('po_details_id'+index, val.po_details_id)
		});
		if(driver.value!=''){
			axios.post(`/api/save_dr`,formData).then(function (response) {
				alert(response.data)
				// po_dr_id.value=response.data;
				// success.value='You have successfully saved new dr.'
				// successAlert.value=!successAlert.value
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

	const resetError = () => {
		document.getElementById('driver').style.backgroundColor = '#FEFCE8';
		const btn_save = document.getElementById("save");
		btn_save.disabled = false;
	}
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Delivery Receipt <small>New</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/pur_dr">Delivery Receipt</a></li>
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
									<label class="text-gray-500 m-0" for="">Choose PO Number</label>
									<div class="input-group col-xs-12">
										<select class="form-control file-upload-info" v-model="po_head_id">
											<option value="0">Select PO</option>
											<option :value="p.po_head_id" v-for="p in po_dropdown" :key="p.po_head_id">{{ p.po_no }}</option>
										</select>
										<span class="input-group-append">
											<button class="btn btn-primary" type="button" @click="generateDR()">Select</button>
											<!-- <button class="btn btn-primary" type="button" @click="po_det = !po_det">Select</button> -->
										</span>
									</div>
									</div>
								</div>
							</div>
							<hr class="border-dashed">
							<!-- <div v-show="po_det"> -->
							<div v-if="po_dr && po_dr.length!=0">
								<div class="row">
									<div class="col-lg-8">
										<input type="text" v-model="po_dr_id">
										<input type="hidden" v-model="dr_no">
										<span class="text-sm text-gray-700 font-bold pr-1">PO No: </span>
										<span class="text-sm text-gray-700">{{po_dr.po_no}}{{ (po_dr.revision_no!=0 && po_dr.revision_no!=null && po_dr.revision_no!='') ? '.r'+po_dr.revision_no : '' }}</span>
									</div>
									<div class="col-lg-4">
										<span class="text-sm text-gray-700 font-bold pr-1">DR No: </span>
										<span class="text-sm text-gray-700">{{ (total_sumdelivered!=0) ? dr_no : po_dr.dr_no}}</span>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-8">
										<span class="text-sm text-gray-700 font-bold pr-1">PR No: </span>
										<span class="text-sm text-gray-700">{{po_dr.pr_no}}</span>
									</div>
									<div class="col-lg-4">
										<span class="text-sm text-gray-700 font-bold pr-1">Date: </span>
										<span class="text-sm text-gray-700">{{moment(po_dr.dr_date).format('MMM. DD,YYYY')}}</span>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-8">
										<span class="text-sm text-gray-700 font-bold pr-1">End-use: </span>
										<span class="text-sm text-gray-700">{{enduse}}</span>
									</div>
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
										<table class="w-full table-bordered !text-xs mt-3">
											<tr class="bg-gray-100">
												<td class="p-1 uppercase text-center" width="2%">#</td>
												<td class="p-1 uppercase" width="25%">Supplier</td>
												<td class="p-1 uppercase" width="25%">Description</td>
												<td class="p-1 uppercase text-center" width="7%">To Deliver</td>
												<td class="p-1 uppercase text-center" width="8%">DLVRD Qty</td>
												<td class="p-1 uppercase text-center" width="5%">Received</td>
												<td class="p-1 uppercase text-center" width="5%">UOM</td>
											</tr>
											<tr v-for="(pdi,index) in po_dr_items">
												<td class="p-1 text-center">{{index+1}}</td>
												<td class="p-1 ">{{vendor.vendor_name}} ({{ vendor.identifier }})</td>
												<td class="p-1 ">{{ offer[index] }}</td>
												<td class="p-0"><input type="text" min="0" @keypress="isNumber($event)" @keyup="checkBalance(pdi.po_dr_id,pdi.po_details_id,to_deliver[index],index)" class="w-full p-1 bg-orange-50 text-center" :id="'balance_checker'+index" v-model="to_deliver[index]"></td>
												<td class="p-1 text-center">{{ total_sumdelivered }}</td>
												<td class="p-1 text-center">{{ pdi.quantity }}</td>
												<td class="p-1 text-center">{{ uom[index] }}</td>
											</tr>
										</table>
									</div>
								</div>
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
												<td class="p-0 "><input type="text" id="driver" class="w-full bg-yellow-50 p-1 text-center" v-model="driver" @click="resetError()"></td>
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
								<div class="row my-2"> 
									<div class="col-lg-12 col-md-12">
										<div class="flex justify-center space-x-2">
											<button type="button" class="btn btn-primary mr-2 w-36" @click="onSave()" id="save">Save</button>
											<!-- <button type="button" class="btn btn-primary mr-2 w-36" @click="openSuccessAlert()" id="save">Save</button> -->
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
									<a :href="'/pur_po/view/'+props.id" class="btn !bg-gray-100 btn-sm !rounded-full w-full" v-if="props.id!=0">Back to PO</a>
									<a href="/pur_dr/new/0" class="btn !bg-gray-100 btn-sm !rounded-full w-full" v-else>Create New</a>
									<a :href="'/pur_dr/view/'+po_dr_id"  class="btn !bg-gray-100 btn-sm !rounded-full w-full" >Close & Print</a>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
</template>