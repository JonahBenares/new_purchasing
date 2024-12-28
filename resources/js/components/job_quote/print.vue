<script setup>
	import printheader from '@/layouts/print_header.vue';
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, CheckIcon, ExclamationTriangleIcon} from '@heroicons/vue/24/solid'
	import axios from 'axios';
    import {onMounted, ref} from "vue";
	import { useRouter } from "vue-router";
	const router = useRouter();

	let RFQHead=ref([]);
	let HeadNotes=ref([]);
	let RFQVendors=ref([]);
	let RFQLaborDetails=ref([]);
	let RFQMaterialDetails=ref([]);
	let RFQLaborOffers=ref([]);
	let RFQMaterialOffers=ref([]);
	let vendorlist=ref([]);
	let signatories=ref([]);
	let rfq_vendor_terms=ref([]);
	let vendor_details=ref('');
	let noted_by=ref(3);
	let approved_by=ref(1);
	let count_jorlabor=ref(0);
	let count_jormaterial=ref(0);
	let term=ref('');
	let letters=ref([]);
	let rfqvendorid=ref('');
	let count_ccr=ref(0);
	let LaborDetails=ref([]);
	let MaterialDetails=ref([]);
	let all_labor_checkbox=ref(0);
	let all_material_checkbox=ref(0);


	const props = defineProps({
        id:{
            type:String,
            default:''
        }
    })

	onMounted(async () => {
		GetRFQDetails()
		GetRFQTermsDetails()
		GetPerVendorDetails()
		GetAdditionalItems()
		GetAdditionalVendors()
	})

	const GetRFQDetails = async () => {
		let response = await axios.get(`/api/get_jo_rfq_data/${props.id}`)
		RFQHead.value=response.data.head
		HeadNotes.value=response.data.jor_head_notes
		RFQVendors.value=response.data.rfq_vendor
		RFQLaborDetails.value=response.data.rfq_labor_details
		RFQLaborOffers.value=response.data.rfq_labor_offers
		RFQMaterialDetails.value=response.data.rfq_material_details
		RFQMaterialOffers.value=response.data.rfq_material_offers
		signatories.value=response.data.signatories
		count_jorlabor.value=response.data.count_jorlabor
		count_jormaterial.value=response.data.count_jormaterial
		rfq_vendor_terms.value=response.data.rfq_vendor_terms
		letters.value=response.data.letters
		count_ccr.value=response.data.count_ccr
		rfqvendorid.value=response.data.rfqvendor_id
	}

	const GetPerVendorDetails = async () => {
		let response = await axios.get(`/api/get_jo_rfq_data/${props.id}`)
		RFQHead.value=response.data.head
		HeadNotes.value=response.data.jor_head_notes
		RFQVendors.value=response.data.rfq_vendor
		RFQLaborDetails.value=response.data.rfq_labor_details
		RFQLaborOffers.value=response.data.rfq_labor_offers
		RFQMaterialDetails.value=response.data.rfq_material_details
		RFQMaterialOffers.value=response.data.rfq_material_offers
		signatories.value=response.data.signatories
		count_jorlabor.value=response.data.count_jorlabor
		count_jormaterial.value=response.data.count_jormaterial
		rfq_vendor_terms.value=response.data.rfq_vendor_terms
		letters.value=response.data.letters
		count_ccr.value=response.data.count_ccr
	}

	const GetRFQTermsDetails = async () => {
		let response = await axios.get(`/api/get_jo_rfq_data/${props.id}`)
		rfq_vendor_terms.value=response.data.rfq_vendor_terms
	}

	const AddRFQTerms= (jo_rfq_vendor_id) => {
		if(term.value!=''){
			const formTerms= new FormData()
			formTerms.append('jo_rfq_vendor_id', jo_rfq_vendor_id)
			formTerms.append('terms', term.value)
			rfqvendorid.value=jo_rfq_vendor_id
			axios.post("/api/add_additional_jo_terms", formTerms).then(function () {
				term.value=''
				document.getElementById('newterms').placeholder=""
				document.getElementById('newterms').style.backgroundColor = '#FEFCE8';
				GetRFQTermsDetails()
			
			});
		}else{
			document.getElementById('newterms').placeholder="Please fill in Terms."
			document.getElementById('newterms').style.backgroundColor = '#FAA0A0';
		}
	}

	const UpdateRFQTerms= (loop, id) => {
		const rfqterms = document.getElementById("rfqterms_"+loop).value;
		if(rfqterms!=''){
			const formRFQTerms= new FormData()
			formRFQTerms.append('jo_rfq_terms_id', id)
			formRFQTerms.append('terms', rfqterms)
			axios.post("/api/update_jo_terms/", formRFQTerms).then(function (response) {
				GetRFQTermsDetails()
				document.getElementById("rfqterms_"+loop).style.backgroundColor = '#FEFCE8';
				document.getElementById("printbtn").disabled = false;
			});
		}else{
			document.getElementById("printbtn").disabled = true;
			document.getElementById("rfqterms_"+loop).placeholder="Please fill in Terms."
			document.getElementById("rfqterms_"+loop).style.backgroundColor = '#FAA0A0';
		}
	}

	const RemoveRFQVendorTerms = (order_no, terms_id) => {
		axios.get(`/api/remove_jo_terms/${terms_id}`).then(function () {
			rfq_vendor_terms.value.splice(order_no,1)
		});
	}

	const GetAdditionalVendors = async () => {
		let response = await axios.get(`/api/get_jo_rfq_vendor_list/${props.id}`)
		vendorlist.value=response.data
	}

	const AdditionalVendorBtn= () => {
		if(vendor_details.value == ''){
			document.getElementById("vendor_alert").style.display="block"
		}else{
			AdditionalVendorAlert.value = !AdditionalVendorAlert.value
			document.getElementById("vendor_alert").style.display="none"
		}
	}

	const AdditionalVendor= () => {
		document.getElementById("YesVendor").disabled = true;
		document.getElementById("NoVendor").disabled = true;
		const formVendor= new FormData()
				let ven = vendor_details.value
				const v = ven.split("_")
				var vendor_details_id= v[0]
				var vendor_name= v[1]
				var identifier= v[2]

			formVendor.append('jo_rfq_head_id', props.id)
			formVendor.append('jor_no', RFQHead.value.jor_no)
			formVendor.append('vendor_details_id', vendor_details_id)
			formVendor.append('vendor_name', vendor_name)
			formVendor.append('vendor_identifier', identifier)
			axios.post("/api/add_additional_jo_rfq_vendor", formVendor).then(function () {
				GetPerVendorDetails()
				GetAdditionalItems()
				GetAdditionalVendors()
				document.getElementById("YesVendor").disabled = false;
				document.getElementById("NoVendor").disabled = false;
				closeModal()
			});
	}

	const GetAdditionalItems = async () => {
		let response = await axios.get(`/api/get_jo_rfq_item_list/${props.id}`)
		LaborDetails.value=response.data.jor_labor_list
		MaterialDetails.value=response.data.jor_material_list
	}

	const AdditionalItemsBtn= () => {
		AdditionalItemsAlert.value = !AdditionalItemsAlert.value
	}

	const AdditionalItems= () => {
		document.getElementById("YesItem").disabled = true;
		document.getElementById("NoItem").disabled = true;
		const formItems= new FormData()
			formItems.append('jo_rfq_head_id', props.id)
			formItems.append('jor_no', RFQHead.value.jor_no)
			formItems.append('additional_labor', JSON.stringify(LaborDetails.value))
			formItems.append('additional_material', JSON.stringify(MaterialDetails.value))
			axios.post("/api/add_additional_labor_material", formItems).then(function () {
				GetPerVendorDetails()
				GetAdditionalItems()
				GetAdditionalVendors()
				closeModal()
				document.getElementById("YesItem").disabled = false;
				document.getElementById("NoItem").disabled = false;
			});
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
						document.getElementById("AddItemsBtn").disabled = false;
					}else{
						checkalllabor.value=!allSelectedLabor
						if(all_labor_checkbox.value == 1){
							LaborDetails.value[x].labor_checkbox=0;
						}

						if(material_count>=1){
							document.getElementById("AddItemsBtn").disabled = false;
						}else{
							document.getElementById("AddItemsBtn").disabled = true;
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
				// alert(all_material_checkbox.value)
				var count_check_material=document.getElementsByClassName('checkboxesmaterial');
				for(var x=0;x<count_check_material.length;x++){
					var check_material=document.getElementsByClassName('checkboxesmaterial')[x].checked;
					count_check_material[x].value = false;
					if(!check_material){
						checkallmaterial.value=allSelectedMaterial
						if(all_material_checkbox.value == 0){
							MaterialDetails.value[x].material_checkbox=1;
						}
						document.getElementById("AddItemsBtn").disabled = false;
					}else{
						checkallmaterial.value=!allSelectedMaterial
						if(all_material_checkbox.value == 1){
							MaterialDetails.value[x].material_checkbox=0;
						}

						if(labor_count>=1){
							document.getElementById("AddItemsBtn").disabled = false;
						}else{
							document.getElementById("AddItemsBtn").disabled = true;
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
				document.getElementById("AddItemsBtn").disabled = false;
			}else{
				document.getElementById("AddItemsBtn").disabled = true;
			}
	}

	const printDivBtn= (jo_rfq_vendor_id) => {
	var duedate =document.getElementById('duedate').value
		if(duedate == ''){
			document.getElementById("duedatealert").style.display="block"
		}else{
			document.getElementById("duedatealert").style.display="none"
			rfqvendorid.value = jo_rfq_vendor_id
			PrintAlert.value = !PrintAlert.value
		}
	}

	const printDiv = (jo_rfq_vendor_id) => {
		PrintAlert.value = !hideModal.value
		const formData= new FormData()

		formData.append('jo_rfq_vendor_id', jo_rfq_vendor_id)
		formData.append('due_date', duedate)
		formData.append('prepared_by', RFQHead.value.preparedby_id)
		formData.append('noted_by', noted_by.value)
		formData.append('approved_by',  approved_by.value)
		axios.post(`/api/save_jo_rfq_print_details`, formData).then(function () {
			window.print();
			GetPerVendorDetails()
		});
	}

	const printCanvassComplete = () => {
		window.print();
	}

	const vendor =  ref(rfqvendorid);
	const showModal = ref(false)
	const addItems = ref(false)
	const hideModal = ref(true)
	const AdditionalVendorAlert = ref(false)
	const AdditionalItemsAlert = ref(false)
	const PrintAlert = ref(false)
	const openAddItem = () => {
		addItems.value = !addItems.value
	}
	const openModel = () => {
		showModal.value = !showModal.value
	}
	const closeModal = () => {
		addItems.value = !hideModal.value
		showModal.value = !hideModal.value
		AdditionalVendorAlert.value = !hideModal.value
		AdditionalItemsAlert.value = !hideModal.value
		PrintAlert.value = !hideModal.value
		vendor_details.value=''
		document.getElementById('newterms').style.backgroundColor = '';
	}

	const openEncodeOffer = () => {
		router.push(`/job_quote/view/${props.id}/0`)
	}
</script>
<template>
	<navigation>
		<div class="row" id="breadcrumbs">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Request for Quotation <small>Print</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/job_quote">JO Request for Quotation</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Print</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
				<div class="card-body">
					
					<div class="pt-1">
						<div id="details">
							<hr class="border-dashed mt-2">
							<div class="row">
								<div class="col-lg-8">
									<div class="row">
										<div class="col-lg-6">
											<span class="text-sm text-gray-700 font-bold pr-1">RFQ No: </span>
											<span class="text-sm text-gray-700">{{ RFQHead.rfq_no }}</span>
										</div>
										<div class="col-lg-6">
											<span class="text-sm text-gray-700 font-bold pr-1">Date:</span>
											<span class="text-sm text-gray-700">{{ RFQHead.rfq_date }}</span>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12">
											<span class="text-sm text-gray-700 font-bold pr-1">RFQ Name: </span>
											<span class="text-sm text-gray-700">{{ RFQHead.rfq_name }}</span>
										</div>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="flex justify-end space-x-2">
										<button class="btn btn-sm p-1 px-3 !text-xs btn-primary" @click="openAddItem()" v-if="((count_jorlabor != 0 || count_jormaterial != 0) && count_ccr == 0)">Add Items</button>
										<button class="btn btn-sm p-1 px-3 !text-xs btn-primary" @click="openEncodeOffer()">Encode Offer</button>
									</div>
								</div>
							</div>
							<br>
						</div>
						<div>
							<div class="rfq_buttons">
								<div class="w-full flex justify-between space-x-1">
									<button
										v-for="rv in RFQVendors"
										:key="rv.jo_rfq_vendor_id"
										class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !font-bold"
										:class="{
											'!text-orange-400 !border-orange-300 !bg-orange-200 border-0': vendor !== rv.jo_rfq_vendor_id,
											'!text-white !bg-orange-500 !border-orange-500 border-0': vendor === rv.jo_rfq_vendor_id
										}"
										@click="vendor = rv.jo_rfq_vendor_id"
									>
										{{ rv.vendor_name }} ({{ rv.vendor_identifier }})
									</button>
									<button @click="openModel()" class="btn btn-primary p-1">
										<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
									</button>
								</div>
							</div>
							<div class="page-bg">
								
								<div v-for="rvi in RFQVendors">
									<!-- <div class="page"> -->
									<div class="bg-white !mx-auto w-[21cm] print:!w-full" v-if="(vendor == rvi.jo_rfq_vendor_id)">
										<div class="subpage">
											<div class="hidden print:block">
												<printheader ></printheader>
												<div class="flex justify-center mt-1">
													<span class="uppercase">Request for Quotation</span>
												</div>
												<hr class="print:block border-dashed mt-2">
											</div>
											<table class="table-bosrdered w-full text-xs mb-0">
												<tr>
													<td class="font-bold" width="8%">Date: </td>
													<td class="">{{ RFQHead.rfq_date }}</td>
													<td class="font-bold" width="10%">RFQ No.:</td>
													<td class="" >{{ RFQHead.rfq_no }}</td>
												</tr>
												<tr>
													<td class="font-bold">JOR No:</td>
													<td class="" width="55%">{{ RFQHead.jor_no }}</td>
													<td class="font-bold" >RFQ Name:</td>
													<td class="" >{{ RFQHead.rfq_name }}</td>
												</tr>
											</table>
											<!-- <hr class="border-dashed my-2"> -->
											<table class="table-bsordered w-full text-xs mb-2">
												<tr>
													<td class="font-bold" width="8%">Supplier: </td>
													<td class="" width="55%">{{ rvi.vendor_name }}</td>
													<td class="font-bold" width="10%">Contact No.:</td>
													<td class="" >{{ rvi.phone_no }}</td>
												</tr>
											</table>
											<div class="border-y-2 py-1 mb-2">
												<p class="text-sm print:!text-base font-bold text-gray-600 text-center m-0">{{ RFQHead.project_activity }}</p>
												<p class="text-xs text-gray-600 text-center m-0">Project Title/Description</p>
											</div>
											<table class="table-bordered w-full text-xs mb-2" v-if = "RFQLaborDetails.length != 0">
												<tr class="bg-gray-100">
													<td class="p-1 text-center" width="3%">#</td>
													<td class="p-1" width="50%">Scope of Work</td>
													<td class="p-1" width="35%">Offer</td>
													<td class="p-1 text-center" width="15%">Unit Price</td>
												</tr>
												<tr>
													<td class="p-1 align-top font-bold" colspan="4">{{ RFQHead.general_description }}</td>
												</tr>
												<span hidden>{{ labor_no=1 }}</span>
												<tbody v-for="rld in RFQLaborDetails" class="p-0">
												<tr v-if="rld.jo_rfq_vendor_id == rvi.jo_rfq_vendor_id">
													<td class="p-1 align-top text-center">{{ labor_no }}</td>
													<td class="p-1 align-top">{{ rld.scope_of_work }}</td>
													<span hidden>{{ labor_no++ }}</span>
													<template v-if="(rvi.canvassed == 0)">
														<td class="align-top">
															<div class="border-b p-1 w-full h-10 !align-top"></div>
															<div class="border-b p-1 w-full h-10 !align-top"></div>
															<div class="p-1 w-full h-10 !align-top"></div>
														</td>
														<td class="align-top">
															<div class="border-b p-1 w-full h-10 !align-top text-center"></div>
															<div class="border-b p-1 w-full h-10 !align-top text-center"></div>
															<div class="p-1 w-full h-10 !align-top text-center"></div>
														</td>
													</template>
													<template v-if="(rvi.canvassed == 1)">
														<td class="align-top">
															<template v-for="rlo in RFQLaborOffers">
																<div class="border-b p-1 w-full h-10 !align-top" v-if="rlo.jo_rfq_labor_details_id == rld.jo_rfq_labor_details_id">{{ rlo.offer }}</div>
															</template>
														</td>
														<td class="align-top">
															<template v-for="rlo in RFQLaborOffers">
																<div class="border-b p-1 w-full h-10 !align-top text-center" v-if="rlo.jo_rfq_labor_details_id == rld.jo_rfq_labor_details_id">{{ parseFloat(rlo.unit_price).toFixed(2) }}</div>
															</template>
														</td>
													</template>
												</tr>
												</tbody>
											</table>
											<table class="table-bordered w-full text-xs mb-2" v-if = "RFQMaterialDetails.length != 0">
												<tr class="bg-gray-100">
													<td class="p-1 text-center" width="3%">#</td>
													<td class="p-1 text-center" width="5%">Qty</td>
													<td class="p-1" width="45%">Item Description</td>
													<td class="p-1" width="35%">Brand/Offer</td>
													<td class="p-1 text-center" width="15%">Unit Price</td>
												</tr>
												<span hidden>{{ item_no=1 }}</span>
												<tbody v-for="rmd in RFQMaterialDetails" class="p-0">
													<tr v-if="rmd.jo_rfq_vendor_id == rvi.jo_rfq_vendor_id">
														<td class="p-1 align-top text-center">{{ item_no }}</td>
														<td class="p-1 align-top text-center">{{ parseFloat(rmd.quantity).toFixed(2) }}</td>
														<td class="p-1 align-top">{{ rmd.item_description }}</td>
														<span hidden>{{ item_no++ }}</span>
														<template v-if="(rvi.canvassed == 0)">
															<td class="align-top">
																<div class="border-b p-1 w-full h-10 !align-top"></div>
																<div class="border-b p-1 w-full h-10 !align-top"></div>
																<div class="p-1 w-full h-10 !align-top"></div>
															</td>
															<td class="align-top">
																<div class="border-b p-1 w-full h-10 !align-top text-center"></div>
																<div class="border-b p-1 w-full h-10 !align-top text-center"></div>
																<div class="p-1 w-full h-10 !align-top text-center"></div>
															</td>
														</template>
														<template v-if="(rvi.canvassed == 1)">
															<td class="align-top">
																<template v-for="rmo in RFQMaterialOffers">
																<div class="border-b p-1 w-full h-10 !align-top" v-if="rmo.jo_rfq_material_details_id == rmd.jo_rfq_material_details_id">{{ rmo.offer }}</div>
																</template>
																<!-- <div class="border-b p-1 w-full h-10 !align-top"></div>
																<div class="p-1 w-full h-10 !align-top"></div> -->
															</td>
															<td class="align-top">
																<template v-for="rmo in RFQMaterialOffers">
																<div class="border-b p-1 w-full h-10 !align-top text-center" v-if="rmo.jo_rfq_material_details_id == rmd.jo_rfq_material_details_id">{{ parseFloat(rmo.unit_price).toFixed(2) }}</div>
																</template>
																<!-- <div class="border-b p-1 w-full h-10 !align-top text-center"></div>
																<div class="p-1 w-full h-10 !align-top text-center"></div> -->
															</td>
														</template>
													</tr>
												</tbody>
											</table>
											<table class="table-bordered w-full text-xs mb-2">
												<tr class="bg-gray-100">
													<td class="p-1" width="">Notes</td>
												</tr>
												<tr v-for="hn in HeadNotes">
													<td class="p-1 align-top">{{ hn.notes }}</td>
												</tr>
											</table>
											<div class="bg-red-100 border-2 border-red-200 w-full p-1  px-2 text-red-500 my-1 mb-2 hidden"  id="duedatealert">
												<div class="flex justify-start space-x-2">
													<ExclamationTriangleIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></ExclamationTriangleIcon>
													<span class="text-sm">Due date is required!</span>
												</div>
											</div>
											<table class="table-bordesred w-full text-xs">
												<tr>
													<td colspan="4" v-if="(rvi.canvassed == 0)">1. Quotation must be submitted on or before <input class="bg-yellow-50 print:bg-white" type="date" id="duedate" v-model="rvi.due_date"></td>
													<td colspan="4" id="duedate" v-else>1. Quotation must be submitted on or before {{ rvi.due_date }} </td>
												</tr>
												<tr>
													<td colspan="4">2. Please Fill - Up :</td>
												</tr>
												<tbody v-if="(rvi.canvassed==0)">
													<tr class="po_buttons">
														<td width="10%"></td>
														<td width="40%" colspan="2">
															<div class="flex justify-between space-x-1">
																<input type="text" cl ass="p-1 w-full bg-yellow-50" id="newterms" v-model="term">
																<button type="button" class="btn btn-primary p-1" @click="AddRFQTerms(rvi.jo_rfq_vendor_id)">
																<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon></button>
															</div>
														</td>
														<td width="10%"></td>
													</tr>
													<span hidden>{{ orderno=1 }}</span>
													<tr v-for="(vt, order_no) in rfq_vendor_terms">
														<td width="10%"></td>
														<td width="40%" colspan="2">
															<div v-if="vt.jo_rfq_vendor_id == rvi.jo_rfq_vendor_id">
																<div class="flex justify-between space-x-1">
																	<span class="pt-1">{{ letters[orderno-1] }}. </span>
																	<input type="text" class="p-1 w-full print:bg-white bg-yellow-50 rfqterms" :id="'rfqterms_'+ order_no" v-model="vt.terms" @blur="UpdateRFQTerms(order_no,vt.id)">
																	<button class="btn btn-danger p-1 po_buttons" @click="RemoveRFQVendorTerms(order_no,vt.id)">
																		<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
																	</button>
																	<span hidden>{{ orderno++ }}</span>
																</div>
																<input type="hidden" class="p-1 w-full bg-yellow-50" v-model="vt.jo_rfq_vendor_id">
																<input type="hidden" class="p-1 w-full bg-yellow-50 vendortermsid" v-model="vt.id">
															</div>
														</td>
														<td width="10%"></td>
													</tr>
												</tbody>
												<tbody v-for="(vt, index) in rvi.jorfq_vendor_terms" v-else>
													<!-- <tr v-if="vt.jo_rfq_vendor_id == rvi.jo_rfq_vendor_id"> -->
													<tr>
														<td width="10%"></td>
														<td width="1%">{{ letters[index] }}.</td>
														<td width="40%">{{ vt.terms }}</td>
														<td width="10%"></td>
													</tr>
												</tbody>
											</table>
											<br>
											<br>
											<table class="w-full text-xs">
												<tr>
													<td class="text-center" width="30%">Prepared by</td>
													<td width="5%"></td>
													<td class="text-center" width="30%">Noted by</td>
													<td width="5%"></td>
													<td class="text-center" width="30%">Approved by</td>
												</tr>
												<tr>
													<td class="text-center border-b"><br></td>
													<td></td>
													<td class="text-center border-b"></td>
													<td></td>
													<td class="text-center border-b"></td>
												</tr>
												<tr>
													<td class="text-center p-1" v-if="rvi.prepared_by_id == 0">{{  RFQHead.prepared_by_name }}</td>
													<td class="text-center p-1" v-else>{{  rvi.prepared_by_name }}</td>
													<td></td>
													<td class="text-center p-1">
														<select v-model="noted_by" v-if="rvi.noted_by_id == 0" style="-webkit-appearance: none;" class="w-full text-center">
															<option :value="s.id" v-for="s in signatories" :key="s.id" :selected="s.id==2">{{ s.name }}</option>
														</select>
														<span class="text-center p-1" v-else>{{  rvi.noted_by_name }}</span>
													</td>
													<td></td>
													<td class="text-center p-1">
															<select v-model="approved_by" v-if="rvi.approved_by_id == 0"  style="-webkit-appearance: none;" class="w-full text-center">
																<option :value="s.id" v-for="s in signatories" :key="s.id" >{{ s.name }}</option>
															</select>
														
														<span class="text-center p-1" v-else>{{  rvi.approved_by_name }}</span>
													</td>
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
									<div class="row my-2 po_buttons" v-if="vendor == rvi.jo_rfq_vendor_id"> 
										<div class="col-lg-12 col-md-12">
											<div class="flex justify-center space-x-2">

												<button type="submit" class="btn btn-primary mr-2 w-44" id="printbtn" @click="printDivBtn(rvi.jo_rfq_vendor_id)" v-if="(rvi.canvassed==0)">Print</button>
												<button type="submit" class="btn btn-primary mr-2 w-44" id="printbtn" @click="printCanvassComplete()" v-else>Print</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- <br>
						<div class="row my-2"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button type="submit" class="btn btn-primary mr-2 w-44">Print</button>
								</div>
							</div>
						</div> -->
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
				<div class="modal__content w-6/12">
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
							<div class="bg-blue-100 border-2 border-blue-200 w-full p-2 text-blue-400 my-1 mb-2 hidden" id="vendor_alert">
								<div class="flex justify-start space-x-2">
									<ExclamationTriangleIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></ExclamationTriangleIcon>
									<span>Please select vendor!</span>
								</div>
							</div>
							<div class="col-lg-12 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Vendor</label>
									<select class="form-control" v-model="vendor_details">
										<option :value="v.id+'_'+v.vendor_head.vendor_name+'_'+v.identifier" v-for="v in vendorlist" :key="v.id">{{ v.vendor_head.vendor_name }} {{ v.identifier}}</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button type="submit" @click="AdditionalVendorBtn()" class="btn btn-primary mr-2 w-44">Add Vendor</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:AdditionalVendorAlert }">
				<div @click="closeModal" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h5 class="leading-tight">Are you sure you want to add this vendor?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full" id="NoVendor" @click="closeModal()">No</button>
									<button class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full" id="YesVendor" @click="AdditionalVendor()">Yes</button>
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
			<div class="modal pt-4 px-3" :class="{ show:addItems }">
				<div @click="closeModal" class="w-full h-full fixed"></div>
				<div class="modal__content w-11/12">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">Add Items</span>
							<a href="#" class="text-gray-600" @click="closeModal">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<hr class="mt-0">
					<div class="modal_s_items ">
						<div>
							<div class="row">
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">Job Order Request: </span>
									<span class="text-sm text-gray-700">{{ RFQHead.location }}</span>
								</div>
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">Prepared Date: </span>
									<span class="text-sm text-gray-700">{{ RFQHead.date_prepared }}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">JOR Number: </span>
									<span class="text-sm text-gray-700">{{ RFQHead.site_jor }}</span>
								</div>
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">New JOR Number: </span>
									<span class="text-sm text-gray-700">{{ RFQHead.jor_no }}</span>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">Department: </span>
									<span class="text-sm text-gray-700">{{ RFQHead.department_name }}</span>
								</div>
								<div class="col-lg-4">
									<span class="text-sm text-gray-700 font-bold pr-1">Process Code: </span>
									<span class="text-sm text-gray-700">{{ RFQHead.process_code }}</span>
								</div>
								<div class="col-lg-2">
									<span class="text-sm text-gray-700 font-bold pr-1">Urgency: </span>
									<span class="text-sm text-gray-700">{{ RFQHead.urgency }}</span>
								</div>
							</div>
							<div class="row">
								<!-- <div class="col-lg-12">
									<span class="text-sm text-gray-700 font-bold pr-1">End-Use: </span>
									<span class="text-sm text-gray-700"></span>
								</div> -->
								<div class="col-lg-12">
									<span class="text-sm text-gray-700 font-bold pr-1">Purpose: </span>
									<span class="text-sm text-gray-700">{{ RFQHead.purpose }}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<span class="text-sm text-gray-700 font-bold pr-1">Project/Activity: </span>
									<span class="text-sm text-gray-700">{{ RFQHead.project_activity }}</span>
								</div>
							</div>
							
							<div class="row">
								<div class="col-lg-12">
									<table class="w-full table-bordered !text-xs mt-3">
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
									<table class="w-full table-bordered !text-xs mb-3">
										<tr class="bg-gray-100">
											<td class="p-1 uppercase text-center" width="2%">
												<input type="checkbox" id="checkallmaterial" @click="CheckAllMaterial" :checked="allSelectedMaterial" v-model="all_material_checkbox" :true-value="1" :false-value="0">
											</td>
											<td class="p-1 uppercase text-center" width="7%">Qty</td>
											<td class="p-1 uppercase text-center" width="7%">UOM</td>
											<td class="p-1 uppercase" width="20%">PN No.</td>
											<td class="p-1 uppercase" width="">Item Description</td>
											<td class="p-1 uppercase" width="10%">WH Stocks</td>
											<td class="p-1 uppercase" width="15%">Date Needed</td>
										</tr>
										<tr v-for="md in MaterialDetails">
											<input type="hidden" v-model="md.jor_material_details_id">
											<td class="p-1 text-center">
												<input type="checkbox" class='checkboxesmaterial' v-model="md.material_checkbox" :checked="checkallmaterial" :true-value="1" :false-value="0" @change="CountCheckbox">
											</td>
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
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button type="submit" @click="AdditionalItemsBtn()" id="AddItemsBtn" class="btn btn-primary mr-2 w-44" disabled>Add</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:AdditionalItemsAlert }">
				<div @click="closeModal" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h5 class="leading-tight">Are you sure you want to add this Labor and Material/s?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full" id= "NoItem" @click="closeModal()">No</button>
									<button class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full" id= "YesItem" @click="AdditionalItems()">Yes</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:PrintAlert }">
				<div @click="closeModal" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h5 class="leading-tight">Are you sure you want to print this?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full" id = "NoPrint" @click="closeModal()">No</button>
									<button class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full" id = "YesPrint" @click="printDiv(rfqvendorid)">Yes</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</Transition>
	</navigation>
</template>