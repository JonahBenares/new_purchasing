<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, CheckIcon, XMarkIcon, ExclamationTriangleIcon} from '@heroicons/vue/24/solid'
	import axios from 'axios';
    import { onMounted, ref } from "vue"
	import { useRouter } from "vue-router";
	const router = useRouter();

	let prnolist=ref([]);
	let vendorlist=ref([]);
	let pr_no=ref([]);
	let vendor_list=ref([]);
	let PRHead=ref([]);
	let PRDetails=ref([]);
	let selected_items=ref([]);
	let rfq_name=ref('');
	let rfq_no=ref('');
	let date_created=ref('');
	let vendor=ref('');
	let all_checkbox=ref(0);

	const props = defineProps({
        id:{
            type:String,
            default:''
        },	
    })

	onMounted(async () => {
		getprno()
		getvendors()
		if(props.id != 0){
			getPRDetails()
		}
	})

	const getprno = async () => {
			let response = await axios.get("/api/pr_list");
			prnolist.value=response.data
	}

	const getvendors = async () => {
			let response = await axios.get("/api/vendor_list");
			vendorlist.value=response.data
	}

	const getPRDetails= async () => {
		let id = pr_no.value
		if(props.id != 0 && id == ''){
			let response = await axios.get(`/api/get_pr_data/${props.id}`)
			document.getElementById("display_details").style.display="block"
			PRHead.value = response.data.PRHead
			PRDetails.value = response.data.PRDetails
			rfq_no.value = response.data.rfq_no
		}else if(id != ''){
			let response = await axios.get('/api/get_pr_data/'+id)
			document.getElementById("display_details").style.display="block"
			PRHead.value = response.data.PRHead
			PRDetails.value = response.data.PRDetails
			rfq_no.value = response.data.rfq_no
		}else{
			PRNoAlert.value = !PRNoAlert.value
		}	
	}

	const showModal = ref(false)
	const PRNoAlert = ref(false)
	const SaveandProceedAlert = ref(false)
	const hideModal = ref(true)

	const closeModal = () => {
		showModal.value = !hideModal.value
		PRNoAlert.value = !hideModal.value
		SaveandProceedAlert.value = !hideModal.value
		selected_items.value=[]
		vendor.value=''
		document.getElementById('rfqname_').style.backgroundColor = '#FEFCE8';
		document.getElementById('rfqdate_').style.backgroundColor = '#FEFCE8';
		document.getElementById('vendor_alert').style.backgroundColor = '#FEFCE8';
		document.getElementById("DuplicateVendorMessage").style.display="none"
	}

	const allSelected=ref(false);
	const checkall=ref([]);
	const CheckAll = () => {
			var count_check=document.getElementsByClassName('checkboxes');
			for(var x=0;x<count_check.length;x++){
				var check=document.getElementsByClassName('checkboxes')[x].checked;
				if(!check){
					checkall.value=allSelected
					if(all_checkbox.value == 0){
						PRDetails.value[x].checkbox=1;
					}
					document.getElementById("AddVendorButton").style.display="block"
				}else{
					checkall.value=!allSelected
					if(all_checkbox.value == 1){
						PRDetails.value[x].checkbox=0;
					}
					document.getElementById("AddVendorButton").style.display="none"
				}
			}
	}

	const CountCheckbox= () =>{
		var isChecked = document.getElementsByClassName("checkboxes");
		var count=0;
		for(var x=0;x<isChecked.length;x++){
			if(isChecked[x].checked === true){
				count++;
			}
		}
			if(count>=1){
				document.getElementById("AddVendorButton").style.display="block"
			}else{
				document.getElementById("AddVendorButton").style.display="none"
			}
	}

	const addItemsVendor= () => {
		for(var x=0; x<PRDetails.value.length; x++){
				if(PRDetails.value[x].checkbox == 1){
					selected_items.value.push({
						pr_details_id:PRDetails.value[x].pr_details_id,
						quantity:PRDetails.value[x].quantity,
						remaining_qty:PRDetails.value[x].remaining_qty,
						uom:PRDetails.value[x].uom,
						pn_no:PRDetails.value[x].pn_no,
						item_description:PRDetails.value[x].item_description,
						wh_stocks:PRDetails.value[x].wh_stocks,
						date_needed:PRDetails.value[x].date_needed,
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
			// document.getElementById('vendor_alert').value='Please select vendor.'
			document.getElementById("SelectVendorMessage").style.display="block"
			document.getElementById("DuplicateVendorMessage").style.display="none"
			// document.getElementById('vendor_alert').style.backgroundColor = '#FAA0A0';
		}else if(vendor_count == 1){
			document.getElementById("SelectVendorMessage").style.display="none"
			document.getElementById("DuplicateVendorMessage").style.display="block"
			// document.getElementById('vendor_alert').style.backgroundColor = '#FAA0A0';
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
			formRFQ.append('pr_head_id', PRHead.value.pr_head_id)
			formRFQ.append('pr_no', PRHead.value.pr_no)
			formRFQ.append('rfq_name', rfq_name.value)
			formRFQ.append('rfq_no', rfq_no.value)
			formRFQ.append('rfq_date', date_created.value)
			formRFQ.append('user_id', PRHead.value.user_id)
			formRFQ.append('rfq_items', JSON.stringify(selected_items.value))
			formRFQ.append('rfq_vendors', JSON.stringify(vendor_list.value))
			axios.post("/api/add_rfq", formRFQ).then(function (response) {
				router.push('/pur_quote/print/'+response.data)
			});
	// }
	}

	const removeVendor = (index) => {
		vendor_list.value.splice(index,1)

		if(index > 0){
			document.getElementById("SaveandProceedBtn").disabled = false;
		}else{
			document.getElementById("SaveandProceedBtn").disabled = true;
		}
	}
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Request for Quotation <small>New</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/pur_quote">Request for Quotation</a></li>
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
									<label class="text-gray-500 m-0" for="">Choose PR Number</label>
									<!-- <input type="file" name="img[]" class="file-upload-default"> -->
									<div class="input-group col-xs-12">
										<select class="form-control file-upload-info" v-model="pr_no">
											<option :value="pr.id" v-for="pr in prnolist" :key="pr.id">{{ pr.pr_no }} (Total RFQ:{{ (pr.count_pr_in_rfq != '') ? pr.count_pr_in_rfq : 0  }})</option>
										</select>
										<span class="input-group-append">
											<button class="btn btn-primary" type="button" @click="getPRDetails()">Select</button>
										</span>
									</div>
								</div>
							</div>
						</div>
						<hr class="border-dashed">
						<div style="display:none" id="display_details">
							<div class="row">
							<div class="col-lg-6">
								<span class="text-sm text-gray-700 font-bold pr-1">Purchase Request: </span>
								<span class="text-sm text-gray-700">{{ PRHead.location }}</span>
							</div>
							<div class="col-lg-6">
								<span class="text-sm text-gray-700 font-bold pr-1">Prepared Date: </span>
								<span class="text-sm text-gray-700">{{ PRHead.date_prepared }}</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<span class="text-sm text-gray-700 font-bold pr-1">PR Number: </span>
								<span class="text-sm text-gray-700">{{ PRHead.pr_no }}</span>
							</div>
							<div class="col-lg-6">
								<!-- <span class="text-sm text-gray-700 font-bold pr-1">Site PR Number: </span>
								<span class="text-sm text-gray-700">{{ PRHead.site_pr }}</span> -->
								<span class="text-sm text-gray-700 font-bold pr-1">Process Code: </span>
								<span class="text-sm text-gray-700">{{ PRHead.process_code }}</span>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-6">
								<span class="text-sm text-gray-700 font-bold pr-1">Department: </span>
								<span class="text-sm text-gray-700">{{ PRHead.department_name }}</span>
							</div>
							<div class="col-lg-4">
								<span class="text-sm text-gray-700 font-bold pr-1">Urgency: </span>
								<span class="text-sm text-gray-700">{{ PRHead.urgency }}</span>
							</div>
							<!-- <div class="col-lg-2">
								<span class="text-sm text-gray-700 font-bold pr-1">Urgency: </span>
								<span class="text-sm text-gray-700">{{ PRHead.urgency }}</span>
							</div> -->
						</div>
						<div class="row">
							<div class="col-lg-12">
								<span class="text-sm text-gray-700 font-bold pr-1">End-Use: </span>
								<span class="text-sm text-gray-700">{{ PRHead.enduse }}</span>
							</div>
							<div class="col-lg-12">
								<span class="text-sm text-gray-700 font-bold pr-1">Purpose: </span>
								<span class="text-sm text-gray-700">{{ PRHead.purpose }}</span>
							</div>
						</div>
							<br>
							<div class="row">
								<div class="col-lg-12">
									<table class="w-full table-bordered !text-xs mb-3">
										<tr class="bg-gray-100">
											<td class="p-1 uppercase text-center" width="5%">
												<input type="checkbox" id="checkall" @click="CheckAll" :checked="allSelected" v-model="all_checkbox" :true-value="1" :false-value="0">
											</td>
											<td class="p-1 uppercase text-center" width="7%">PR Qty</td>
											<td class="p-1 uppercase text-center" width="7%">Remaining Qty</td>
											<td class="p-1 uppercase text-center" width="7%">UOM</td>
											<td class="p-1 uppercase" width="20%">PN No.</td>
											<td class="p-1 uppercase" width="">Item Description</td>
											<td class="p-1 uppercase" width="10%">WH Stocks</td>
											<td class="p-1 uppercase" width="15%">Date Needed</td>
										</tr>
										<tr v-for="d in PRDetails">
											<td class="p-1 text-center">
												<input type="checkbox" class='checkboxes' v-model="d.checkbox" :checked="checkall" :true-value="1" :false-value="0" @change="CountCheckbox">
											</td>
											<input type="hidden" v-model="d.pr_details_id">
											<td class="p-1 text-center">{{ parseFloat(d.quantity).toFixed(2) }}</td>
											<td class="p-1 text-center">{{ parseFloat(d.remaining_qty).toFixed(2) }}</td>
											<td class="p-1 text-center">{{ d.uom }}</td>
											<td class="p-1">{{ d.pn_no }}</td>
											<td class="p-1">{{ d.item_description }}</td>
											<td class="p-1">{{ d.wh_stocks }}</td>
											<td class="p-1">{{ d.date_needed }}</td>
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
						<div class="row">
							<div class="col-lg-12">
								<table class="w-full table-bordered !text-xs mb-3">
									<tr class="bg-gray-100">
										<td class="p-1 uppercase text-center" width="7%">PR Qty</td>
										<td class="p-1 uppercase text-center" width="7%">Remaining Qty</td>
										<td class="p-1 uppercase text-center" width="7%">UOM</td>
										<td class="p-1 uppercase" width="20%">PN No.</td>
										<td class="p-1 uppercase" width="">Item Description</td>
										<td class="p-1 uppercase" width="10%">WH Stocks</td>
										<td class="p-1 uppercase" width="15%">Date Needed</td>
									</tr>
									<tr v-for="d in selected_items">
										<input type="hidden" v-model="d.pr_details_id">
										<td class="p-1 text-center">{{ parseFloat(d.quantity).toFixed(2) }}</td>
										<td class="p-1 text-center">{{ parseFloat(d.remaining_qty).toFixed(2) }}</td>
										<td class="p-1 text-center">{{ d.uom }}</td>
										<td class="p-1">{{ d.pn_no }}</td>
										<td class="p-1">{{ d.item_description }}</td>
										<td class="p-1">{{ d.wh_stocks }}</td>
										<td class="p-1">{{ d.date_needed }}</td>
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
										<!-- <td class=""><input placeholder="Vendor" type="text" v-model="vendor_name" class="w-full text-sm p-1 px-2"></td> -->
										<select class="p-2 border w-full bg-yellow-50 text-sm" v-model="vendor" id="vendor_alert">
											<!-- <option value="" disabled selected id="SelectVendorMessage" style="display:block">Select Vendor</option>
											<option value="" disabled selected id="DuplicateVendorMessage" style="display:none">Vendor is already existing!</option> -->
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
											{{ v.vendor_name }} {{ (v.identifier != '') ? '('+v.identifier+')' : '' }}
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
									<!-- <tr>
										<td class="text-sm p-1 px-2">Lectrix Solutions Electrical Supplies & Services Cebu</td>
										<td class="text-center">
											<button class="btn btn-danger p-1">
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
											</button>
										</td>
									</tr>

									<tr>
										<td class="text-sm p-1 px-2">MF Computer Solutions, Inc.</td>
										<td class="text-center">
											<button class="btn btn-danger p-1">
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
											</button>
										</td>
									</tr>
									<tr>
										<td class="text-sm p-1 px-2">Nexus Industrial Prime Solutions Corp.</td>
										<td class="text-center">
											<button class="btn btn-danger p-1">
												<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
											</button>
										</td>
									</tr> -->
								</table>
							</div>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <a href="/pur_quote/print" class="btn btn-primary mr-2 w-44">Save and Proceed</a> -->
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
			<div class="modal p-0 !bg-transparent" :class="{ show:PRNoAlert }">
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
									<h5 class="leading-tight">You must select PR No!</h5>
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