<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, CheckIcon, ExclamationTriangleIcon} from '@heroicons/vue/24/solid'
	import axios from 'axios';
	import {onMounted, ref} from "vue";
	import { useRouter } from "vue-router";
	const router = useRouter();

	let RFQHead=ref([]);
	let RFQVendors=ref([]);
	let RFQDetails=ref([]);
	let RFQOffers=ref([]);
	let signatories=ref([]);
	let vendor_terms=ref([]);
	let rfq_vendor_terms=ref([]);
	let rfqvendor_terms=ref([]);
	let pritem_list=ref([]);
	let vendorlist=ref([]);
	let vendoroffers=ref([]);
	// let noted_by=ref(5);
	// let approved_by=ref(4);
	let checkbox=ref(0);
	let term=ref('');
	let vendor_details=ref('');
	let count_pritems=ref(0);
	let currency=ref([]);
	// let rfq_order_no=ref([]);
	let letters=ref([]);
	let count_ccr=ref(0);
	let rfqvendorid=ref('');

	const props = defineProps({
        id:{
            type:String,
            default:''
        }
    })

	onMounted(async () => {
		GetRFQDetails()
		GetRFQTermsDetails()
		GetDraftCanvassDetails()
		GetAdditionalItems()
		GetAdditionalVendors()
	})

	const GetRFQDetails = async () => {
		let response = await axios.get(`/api/get_rfq_data/${props.id}`)
		RFQHead.value=response.data.head
		RFQVendors.value=response.data.rfq_vendor
		RFQDetails.value=response.data.rfq_details
		RFQOffers.value=response.data.rfq_offers
		vendor_terms.value=response.data.vendor_terms
		signatories.value=response.data.signatories
		count_pritems.value=response.data.count_pritems
		rfq_vendor_terms.value=response.data.rfq_vendor_terms
		currency.value = response.data.currency
		letters.value=response.data.letters
		count_ccr.value=response.data.count_ccr
		rfqvendorid.value=response.data.rfqvendor_id

		// for (var i = 0; i < rfq_vendor_terms.value.length; i++) {
		// 	let letter = '';
		// 	let num = i;

		// 	do {
		// 		letter = String.fromCharCode((num % 26) + 97) + letter; // ASCII 'a' = 97
		// 		num = Math.floor(num / 26) - 1; // Adjust for zero-indexing
		// 	} while (num >= 0);

		// 	rfq_order_no.value[i] = letter;
		// }
	}

	const GetRFQTermsDetails = async () => {
		let response = await axios.get(`/api/get_rfq_data/${props.id}`)
		rfq_vendor_terms.value=response.data.rfq_vendor_terms
	}

	const GetDraftCanvassDetails = async () => {
		let response = await axios.get(`/api/get_rfq_data/${props.id}`)
		RFQHead.value=response.data.head
		RFQVendors.value=response.data.rfq_vendor
		RFQDetails.value=response.data.rfq_details
		RFQOffers.value=response.data.rfq_offers
		vendor_terms.value=response.data.vendor_terms
		signatories.value=response.data.signatories
		count_pritems.value=response.data.count_pritems
		rfq_vendor_terms.value=response.data.rfq_vendor_terms
		currency.value = response.data.currency
		letters.value=response.data.letters
		count_ccr.value=response.data.count_ccr
	}

	const vendor =  ref(rfqvendorid);
	const showModal = ref(false)
	const addItems = ref(false)
	const addVendorModal = ref(false)
	const hideModal = ref(true)
	const DraftAlert = ref(false)
	const CanvassCompleteAlert = ref(false)
	const AdditionalVendorAlert = ref(false)
	const AdditionalItemsAlert = ref(false)
	const openModel = () => {
		showModal.value = !showModal.value
	}

	const openAddItem = () => {
		addItems.value = !addItems.value
	}

	const openVendorModel = () => {
		addVendorModal.value = !addVendorModal.value
	}

	const closeModal = () => {
		GetDraftCanvassDetails()
		GetAdditionalItems()
		GetAdditionalVendors()
		showModal.value = !hideModal.value
		DraftAlert.value = !hideModal.value
		addItems.value = !hideModal.value
		addVendorModal.value = !hideModal.value
		CanvassCompleteAlert.value = !hideModal.value
		AdditionalVendorAlert.value = !hideModal.value
		AdditionalItemsAlert.value = !hideModal.value
		vendor_details.value=''
		document.getElementById("AddItemsBtn").disabled = true;
	}

	const GetAdditionalItems = async () => {
		let response = await axios.get(`/api/get_item_list/${props.id}`)
		pritem_list.value=response.data
	}

	const GetAdditionalVendors = async () => {
		let response = await axios.get(`/api/get_vendor_list/${props.id}`)
		vendorlist.value=response.data
	}

	const ShowPrintView = () => {
		router.push(`/pur_quote/print/${props.id}`)
	}

	const printDiv = () => {
		window.print();
	}

	const AdditionalVendorBtn= () => {
		if(vendor_details.value == ''){
			document.getElementById("vendor_alert").style.display="block"
			// document.getElementById('vendor_alert').style.backgroundColor = '#FAA0A0';
		}else{
			AdditionalVendorAlert.value = !AdditionalVendorAlert.value
			document.getElementById("vendor_alert").style.display="none"
			// document.getElementById('vendor_alert').style.backgroundColor = '';
		}
	}

	const AdditionalVendor= () => {
		// if(confirm("Are you sure you want to update this Vendor?")){
		const formVendor= new FormData()
				let ven = vendor_details.value
				const v = ven.split("_")
				var vendor_details_id= v[0]
				var vendor_name= v[1]
				var identifier= v[2]

			formVendor.append('rfq_head_id', props.id)
			formVendor.append('pr_no', RFQHead.value.pr_no)
			formVendor.append('vendor_details_id', vendor_details_id)
			formVendor.append('vendor_name', vendor_name)
			formVendor.append('vendor_identifier', identifier)
			axios.post("/api/add_additional_vendor", formVendor).then(function () {
				GetDraftCanvassDetails()
				GetAdditionalItems()
				GetAdditionalVendors()
				closeModal()
				// successAlert.value = !successAlert.value
			});
	// }
	}

	const allSelected=ref(false);
	const checkall=ref([]);
	const CheckAll = () => {
		var count_check=document.getElementsByClassName('checkboxes');
		for(var x=0;x<count_check.length;x++){
			var check=document.getElementsByClassName('checkboxes')[x].checked;
			if(!check){
				checkall.value=allSelected
				checkbox.value=1;
				document.getElementById("AddItemsBtn").disabled = false;
			}else{
				checkall.value=!allSelected
				document.getElementById("AddItemsBtn").disabled = true;
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
				document.getElementById("AddItemsBtn").disabled = false;
			}else{
				document.getElementById("AddItemsBtn").disabled = true;
			}
	}
	
	const AdditionalItemsBtn= () => {
		AdditionalItemsAlert.value = !AdditionalItemsAlert.value
	}

	const AdditionalItems= () => {
		// if(confirm("Are you sure you want to update this Vendor?")){
		const formItems= new FormData()
			formItems.append('rfq_head_id', props.id)
			formItems.append('pr_no', RFQHead.value.pr_no)
			formItems.append('additional_items', JSON.stringify(pritem_list.value))
			axios.post("/api/add_additional_items", formItems).then(function () {
				GetDraftCanvassDetails()
				GetAdditionalItems()
				GetAdditionalVendors()
				closeModal()
				// successAlert.value = !successAlert.value
			});
	// }
	}

	const AddRFQTerms= (vendor_details_id,rfq_vendor_id) => {
		// var count_rfq_terms=document.getElementsByClassName('rfqvendorterms');
		if(term.value!=''){
			// if(count_rfq_terms.length != 0){
			// 	rfq_vendor_terms.value.push({
			// 		terms:term.value,
			// 		rfq_vendor_id:rfq_vendor_id,
			// 		id:0,
			// 	});
			// }else{
			// 	vendor_terms.value.push({
			// 		terms:term.value,
			// 		vendor_details_id:vendor_details_id,
			// 		rfq_vendor_terms_id:0,
			// 	});
			// }
			// term.value=''
			// document.getElementById('newterms').placeholder=""
			// document.getElementById('newterms').style.backgroundColor = '#FEFCE8';
			const formTerms= new FormData()
			formTerms.append('rfq_vendor_id', rfq_vendor_id)
			formTerms.append('terms', term.value)
			axios.post("/api/add_additional_terms", formTerms).then(function () {
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
			formRFQTerms.append('rfq_vendor_terms_id', id)
			formRFQTerms.append('terms', rfqterms)
			axios.post("/api/update_terms/", formRFQTerms).then(function (response) {
				GetRFQTermsDetails()
				document.getElementById("rfqterms_"+loop).style.backgroundColor = '#FEFCE8';
				document.getElementById("canvasscompletebtn").disabled = false;
				document.getElementById("draftbtn").disabled = false;
			});
		}else{
			document.getElementById("canvasscompletebtn").disabled = true;
			document.getElementById("draftbtn").disabled = true;
			document.getElementById("rfqterms_"+loop).placeholder="Please fill in Terms."
			document.getElementById("rfqterms_"+loop).style.backgroundColor = '#FAA0A0';
		}
	}

	// const RemoveNewTerms= (order_no, terms_id) =>{
	// 	axios.get(`/api/remove_terms/${terms_id}`).then(function () {
	// 			vendor_terms.value.splice(order_no,1)
	// 			'/remove_terms/'+terms_id
	// 	});
	// }

	const RemoveRFQVendorTerms = (order_no, terms_id) => {
		// if(terms_id != 0 || terms_id != 'undefined'){
			axios.get(`/api/remove_terms/${terms_id}`).then(function () {
				rfq_vendor_terms.value.splice(order_no,1)
			});
		// }else{
		// 	rfq_vendor_terms.value.splice(order_no,1)
		// }
	}

	const CanvassComplete = (rfq_vendor_id) => {
		const formCanvass= new FormData()
			formCanvass.append('rfq_head_id', props.id)
			formCanvass.append('rfq_vendor_id', rfq_vendor_id)
			// formCanvass.append('vendor_offers', JSON.stringify(RFQOffers.value))

			var count_offers=document.getElementsByClassName('offers_');
			for(var o=0;o<count_offers.length;o++){
				var rfq_offer_id =document.getElementsByClassName("offerid_")[o].value;
				var offers =document.getElementsByClassName("offers_")[o].value;
				var unit_price=document.getElementsByClassName("unitprice_")[o].value;
				var currency=document.getElementsByClassName("currency_")[o].value;
					const vendor_o = {
						rfq_offer_id:rfq_offer_id,
						offer:offers,
						unit_price:unit_price,
						currency:currency,
					}
					vendoroffers.value.push(vendor_o)
			}
			formCanvass.append('vendor_offers', JSON.stringify(vendoroffers.value))

			// var count_vendor_terms=document.getElementsByClassName('vendorterms');
			// var count_rfq_terms=document.getElementsByClassName('rfqvendorterms');
			// if(count_rfq_terms.length != 0){
			// 	for(var i=0;i<count_rfq_terms.length;i++){
			// 		var rfq_vendor_terms_id=document.getElementsByClassName("vendortermsid")[i].value;
			// 		var terms=document.getElementsByClassName("rfqvendorterms")[i].value;
			// 			const rfq_v_terms = {
			// 				rfq_vendor_terms_id:rfq_vendor_terms_id ?? 0,
			// 				terms:terms,
			// 			}
			// 				rfqvendor_terms.value.push(rfq_v_terms)
			// 	}
			// }else{
			// 	for(var i=0;i<count_vendor_terms.length;i++){
			// 		var rfq_vendor_terms_id=document.getElementsByClassName("new_vendortermsid")[i].value;
			// 		var terms=document.getElementsByClassName("vendorterms")[i].value;
			// 			const rfq_v_terms = {
			// 				rfq_vendor_terms_id:rfq_vendor_terms_id,
			// 				terms:terms,
			// 			}
			// 			rfqvendor_terms.value.push(rfq_v_terms)
			// 	}
			// }
			// formCanvass.append('rfqvendorterms', JSON.stringify(rfqvendor_terms.value))
			axios.post("/api/canvass_complete_vendor", formCanvass).then(function () {
				CanvassCompleteAlert.value = !CanvassCompleteAlert.value
				GetDraftCanvassDetails()
			});
	}

	const openDraftAlert = (rfq_vendor_id) => {
		const formOffers= new FormData()
			formOffers.append('rfq_head_id', props.id)
			formOffers.append('rfq_vendor_id', rfq_vendor_id)
			// formOffers.append('vendor_offers', JSON.stringify(RFQOffers.value))

			var count_offers=document.getElementsByClassName('offers_');
			for(var o=0;o<count_offers.length;o++){
				var rfq_offer_id =document.getElementsByClassName("offerid_")[o].value;
				var offer =document.getElementsByClassName("offers_")[o].value;
				var unit_price=document.getElementsByClassName("unitprice_")[o].value;
				var currency=document.getElementsByClassName("currency_")[o].value;
					const vendor_o = {
						rfq_offer_id:rfq_offer_id,
						offer:offer,
						unit_price:unit_price,
						currency:currency,
					}
					vendoroffers.value.push(vendor_o)
			}
			formOffers.append('vendor_offers', JSON.stringify(vendoroffers.value))

			// var count_vendor_terms=document.getElementsByClassName('vendorterms');
			// var count_rfq_terms=document.getElementsByClassName('rfqvendorterms');
			
			// if(count_rfq_terms.length != 0){
			// 	for(var i=0;i<count_rfq_terms.length;i++){
			// 		var rfq_vendor_terms_id=document.getElementsByClassName("vendortermsid")[i].value;
			// 		var terms=document.getElementsByClassName("rfqvendorterms")[i].value;
			// 			const rfq_v_terms = {
			// 				rfq_vendor_terms_id:rfq_vendor_terms_id ?? 0,
			// 				terms:terms,
			// 			}
			// 				rfqvendor_terms.value.push(rfq_v_terms)
			// 	}
			// }else{
			// 	for(var i=0;i<count_vendor_terms.length;i++){
			// 		var rfq_vendor_terms_id=document.getElementsByClassName("new_vendortermsid")[i].value;
			// 		var terms=document.getElementsByClassName("vendorterms")[i].value;
			// 			const rfq_v_terms = {
			// 				rfq_vendor_terms_id:rfq_vendor_terms_id,
			// 				terms:terms,
			// 			}
			// 			rfqvendor_terms.value.push(rfq_v_terms)
			// 	}
			// }
			// formOffers.append('rfqvendorterms', JSON.stringify(rfqvendor_terms.value))
			axios.post("/api/draft_vendor", formOffers).then(function () {
				GetDraftCanvassDetails()
				DraftAlert.value = !DraftAlert.value
			});
	}
</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Request for Quotation <small>View</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/pur_quote">Request for Quotation</a></li>
                            <li class="breadcrumb-item active" aria-current="page">View</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
				<div class="card-body">
						
					<hr class="border-dashed mt-2">
					<div class="pt-1">
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
									<button class="btn btn-sm p-1 px-3 !text-xs btn-primary" @click="openAddItem()" v-if="(count_pritems != 0 && count_ccr == 0)">Add Items</button>
									<button class="btn btn-sm p-1 px-3 !text-xs btn-primary" @click="ShowPrintView()">Show Print View</button>
								</div>
							</div>
						</div>
						
						<br>
						<div>
							<div class="w-full flex justify-between space-x-1 po_buttons">
								<button class="btn btn-sm !text-xs !leading-tight w-full !border !rounded-b-none !font-bold !text-orange-900 !border-orange-300 !bg-orange-300" v-for="rv in RFQVendors" v-on:click="vendor = rv.rfq_vendor_id">{{ rv.vendor_name }} {{ (rv.vendor_identifier != '') ? '('+rv.vendor_identifier+')' : '' }}</button>
								<button @click="openVendorModel()" class="btn btn-primary p-1">
									<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
								</button>
							</div>
							<div class="page-bg">
								<div v-for="rvi in RFQVendors">
									<div class="page" v-if="vendor == rvi.rfq_vendor_id">
										<div class="subpage">
											<!-- <div class="border w-full text-center p-4 bg-blue-100"> Header here</div> -->
											<table class="table-bsordered w-full !text-xs mb-2">
												<tr>
													<td class="" width="10%">Date: </td>
													<td class="">{{ RFQHead.rfq_date }}</td>
													<td class="" width="8%">RFQ No.:</td>
													<td class="" >{{ RFQHead.rfq_no }}</td>
													<td class="">Urg:</td>		    			
													<td class="">{{ RFQHead.urgency }}</td>
												</tr>
												<tr>
													<td class="">PR No:</td>
													<td class="" width="50%">{{ RFQHead.pr_no }}</td>
													<td class="" width="10%">RFQ Name:</td>
													<td class="" colspan="3">{{ RFQHead.rfq_name }}</td>
												</tr>
												<tr>
													<td class="">Supplier: </td>
													<td class="">{{ rvi.vendor_name }}</td>
													<td class="">Tel. No.:</td>
													<td class="" colspan="3">{{ rvi.phone_no }}</td>
												</tr>
											</table>
											<table class="table-bordered w-full !text-xs">
												<tr class="bg-gray-100">
													<td class="p-1 text-center" width="5%">No</td>
													<td class="p-1 text-center" width="10%">Qty</td>
													<td class="p-1" width="35%">Item Description</td>
													<td class="p-1" width="35%">Brand/Offer</td>
													<td class="p-1 text-center" width="15%">Unit Price</td>
												</tr>
												<span hidden>{{ item_no=0 }}</span>
												<tbody v-for="(rd, index) in RFQDetails" class="p-0">
												<tr  v-if="rd.rfq_vendor_id == rvi.rfq_vendor_id">
														<td class="p-1 align-top text-center">{{ item_no+1 }}</td>
														<td class="p-1 align-top text-center">{{ parseFloat(rd.quantity).toFixed(2) }}</td>
														<td class="p-1 align-top item_desc">{{ rd.item_description }}</td>
														<span hidden>{{ item_no++ }}</span>
													<td class="align-top">
														<div v-for="ro in RFQOffers">
															<textarea type="text" class="border-b p-1 w-full h-14 !align-top offers_" v-if="ro.rfq_details_id == rd.rfq_details_id && rvi.canvassed == 0" v-model="ro.offer"></textarea>
															<textarea type="text" class="border-b p-1 w-full h-14 !align-top offers_" v-if="ro.rfq_details_id == rd.rfq_details_id && rvi.canvassed == 1" v-model="ro.offer" readonly></textarea>
															<input type="hidden" class="offerid_" v-if="ro.rfq_details_id == rd.rfq_details_id" v-model="ro.rfq_offer_id" >
														</div>
													</td>
													<td class="align-top">
														<div  v-for="ro in RFQOffers">
															<div class="!h-14 border-b" v-if="ro.rfq_details_id == rd.rfq_details_id">
																<input type="number" class="border-b p-1 w-full !align-top text-center unitprice_" placeholder="00.00" v-model="ro.unit_price" v-if="(rvi.canvassed == 0)">
																<div class="border-b p-1 w-full h-7 !align-top text-center"  v-else> {{ parseFloat(ro.unit_price).toFixed(2) }}</div>
																<select class="p-1 m-0 leading-none w-full text-center  block text-xs whitespace-nowrap currency_" v-model="ro.offer_currency" v-if="(rvi.canvassed == 0)">
																	<option v-for="cur in currency" v-bind:key="cur" v-bind:value="cur">{{ cur }}</option>
																</select>
																<div class="border-b p-1 w-full h-7 !align-top text-center" v-else>{{ ro.offer_currency }}</div>
																<input type="hidden" v-if="ro.rfq_details_id == rd.rfq_details_id" v-model="ro.rfq_offer_id" >
																<input type="hidden" class="border-b p-1 w-full !align-top text-center offerid_" v-model="ro.offer_currency" v-else readonly>
																
															</div>
														</div>
														<!-- <div class="!h-14 border-b">
															<input type="text" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
															<select name="" id="" class=" p-1 w-full !align-top text-center">
																<option value="">PHP</option>
															</select>
														</div>
														<div class="!h-14 border-b">
															<input type="text" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
															<select name="" id="" class=" p-1 w-full !align-top text-center">
																<option value="">PHP</option>
															</select>
														</div> -->
													</td>
												</tr>
												</tbody>
											</table>
											<br>
											<table class="table-bordesred w-full text-xs">
												<tr>
													<td colspan="4">1. Quotation must be submitted on or before {{ rvi.due_date }}</td>
												</tr>
												<tr>
													<td colspan="4">2. Please Fill - Up :</td>
												</tr>
												<tbody v-if="(rvi.canvassed==0)">
													<tr>
														<td width="10%"></td>
														<td width="40%" colspan="2">
															<div class="flex justify-between space-x-1">
																<input type="text" class="p-1 w-full bg-yellow-50" id="newterms" v-model="term">
																<button type="button" class="btn btn-primary p-1" @click="AddRFQTerms(rvi.vendor_details_id, rvi.rfq_vendor_id)">
																<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon></button>
															</div>
														</td>
														<td width="10%"></td>
													</tr>
													<span hidden>{{ orderno=1 }}</span>
													<tr v-for="(vt, order_no) in rfq_vendor_terms">
														<td width="10%"></td>
														<td width="40%" colspan="2">
															<div v-if="vt.rfq_vendor_id == rvi.rfq_vendor_id">
																<div class="flex justify-between space-x-1">
																	{{ letters[orderno-1] }}.
																	<input type="text" class="p-1 w-full bg-yellow-50 rfqterms" :id="'rfqterms_'+ order_no" v-model="vt.terms" @blur="UpdateRFQTerms(order_no,vt.id)">
																	<button class="btn btn-danger p-1" @click="RemoveRFQVendorTerms(order_no,vt.id)">
																		<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
																	</button>
																</div>
																<input type="hidden" class="p-1 w-full bg-yellow-50" v-model="vt.rfq_vendor_id">
																<input type="hidden" class="p-1 w-full bg-yellow-50 vendortermsid" v-model="vt.id">
																<span hidden>{{ orderno++ }}</span>
															</div>
														</td>
														<td width="10%"></td>
													</tr>
												</tbody>
												<tbody v-else>
													<tr v-for="(vt, index) in rfq_vendor_terms">
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
													<td class="text-center p-1">{{  rvi.noted_by_name }}</td>
													<td></td>
													<td class="text-center p-1">{{  rvi.approved_by_name }}</td>
												</tr>
													<tr>
														<td class="text-center"><br><br></td>
														<td></td>
														<td class="text-center"></td>
														<td></td>
														<td class="text-center"></td>
													</tr>
													<tr>
														<td class="text-right" colspan="2">Conforme: </td>
														<td class="text-center border-b" colspan="2"></td>
														<td class="text-center"></td>
													</tr>
													<tr>
														<td class="text-right" colspan="2"></td>
														<td class="text-center p-1" colspan="2">Signature over Printed Name</td>
														<td class="text-center"></td>
													</tr>
												</table>
										</div>
									</div>
									<div class="row my-2 po_buttons"> 
										<div class="col-lg-12 col-md-12">
											<div class="flex justify-center space-x-2" v-if="vendor == rvi.rfq_vendor_id && rvi.canvassed == 0">
												<button type="submit" class="btn btn-primary" id = "canvasscompletebtn" @click="CanvassComplete(rvi.rfq_vendor_id)">Canvass Complete</button>
												<button type="submit" class="btn btn-warning text-white mr-2 w-" id = "draftbtn" @click="openDraftAlert(rvi.rfq_vendor_id)">Save as Draft</button>
											</div>
											<div class="flex justify-center space-x-2" v-if="vendor == rvi.rfq_vendor_id && rvi.canvassed == 1">
												<button type="submit" class="btn btn-primary mr-2 w-44"  @click="printDiv()">Print</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<br>
						<div class="row my-2"> 
							<!-- <div class="col-lg-4 col-md-3">
								<div class="flex justify-start space-x-1">
									
								</div>
							</div> -->
							<div class="col-lg-12 col-md-3">
								<ol class="flex items-center w-full">
									<li class="w-full" v-for="(vc, index) in RFQVendors">
										<li class="flex w-full items-center text-white after:w-full after:h-1 after:border-b after:border-green-500 after:border-4 after:inline-block-800" v-if="(vc.canvassed == 1 && vc.status != 'Draft')">
											<span class="flex items-center font-bold justify-center w-10 h-10 bg-green-500 rounded-full lg:h-10 lg:w-10 shrink-0">
												V{{ index+1 }}
											</span>
										</li>
										<li class="flex w-full items-center text-gray-600 after:w-full after:h-1 after:border-b after:border-yellow-300 after:border-4 after:inline-block-700"v-if="(vc.canvassed == 0 && vc.status == 'Draft')">
											<span class="flex items-center font-bold justify-center w-10 h-10 bg-yellow-300 rounded-full lg:h-10 lg:w-10 shrink-0">
												V{{ index+1 }}
											</span>
										</li>
										<li class="flex w-full items-center text-gray-600 after:w-full after:h-1 after:border-b after:border-gray-200 after:border-4 after:inline-block-700"v-if="(vc.canvassed != 1 && vc.status != 'Draft')">
											<span class="flex items-center font-bold justify-center w-10 h-10 bg-gray-200 rounded-full lg:h-10 lg:w-10 shrink-0">
												V{{ index+1 }}
											</span>
										</li>
									</li>
									<li class="!w-30">
										<!-- <a href="" class="btn !bg-gray-200 !w-36">Print TE</a> -->
										<a href="/pur_aoq/print_te" class="btn !bg-green-500 text-white !w-36" v-if="(count_ccr != 0)">Create AOQs</a>
										<a href="/pur_aoq/print_te" class="btn !bg-green-500 text-white !w-36"  style="pointer-events: none;" v-else>Create AOQs</a>
										<!-- <a href="/pur_aoq/print_te" class="btn !bg-green-500 text-white  !w-36" v-else>Create AOQs</a> -->
									</li>
								</ol>
							</div>
						</div>
						<!-- <hr class="border-dashed">	
						<div class="row my-2"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-between space-x-2">
									<button type="submit" class="btn btn-primary mr-2 w-">Canvass Complete</button>
								</div>
							</div>
						</div> -->
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
			<div class="modal p-0 !bg-transparent" :class="{ show:CanvassCompleteAlert }">
				<div @click="closeModal" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-blue-500 border-8 bg-blue-500 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
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
									<h2 class="mb-2  font-bold text-blue-400">Success!</h2>
									<h5 class="leading-tight">You have successfully completed your canvass.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button @click="closeModal()" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close</button>
									<a href="/pur_quote/new/0" class="btn !text-white !bg-blue-400 btn-sm !rounded-full w-full">Create New RFQ</a>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:DraftAlert }">
				<div @click="closeModal()" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-96 p-0">
					<div class="flex justify-center">
						<div class="!border-yellow-400 border-8 bg-yellow-400 !h-32 !w-32 -top-16 absolute rounded-full text-center shadow">
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
									<h2 class="mb-2  font-bold text-yellow-400">Success!</h2>
									<h5 class="leading-tight">You have successfully saved a RFQ as draft.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button @click="closeModal()" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close</button>
									<!-- <a href="/pur_quote/new" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full">Proceed</a> -->
									<a href="/pur_quote/new" class="btn !text-white !bg-yellow-400 btn-sm !rounded-full w-full">Create New RFQ</a>
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
									<span class="text-sm text-gray-700 font-bold pr-1">Purchase Request: </span>
									<span class="text-sm text-gray-700">{{ RFQHead.location}}</span>
								</div>
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">Prepared Date: </span>
									<span class="text-sm text-gray-700">{{ RFQHead.date_prepared}}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">PR Number: </span>
									<span class="text-sm text-gray-700">{{ RFQHead.pr_no}}</span>
								</div>
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">Site PR Number: </span>
									<span class="text-sm text-gray-700">{{ RFQHead.site_pr}}</span>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">Department: </span>
									<span class="text-sm text-gray-700">{{ RFQHead.department_name}}</span>
								</div>
								<div class="col-lg-4">
									<span class="text-sm text-gray-700 font-bold pr-1">Process Code: </span>
									<span class="text-sm text-gray-700">{{ RFQHead.process_code}}</span>
								</div>
								<div class="col-lg-2">
									<span class="text-sm text-gray-700 font-bold pr-1">Urgency: </span>
									<span class="text-sm text-gray-700">{{ RFQHead.urgency}}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<span class="text-sm text-gray-700 font-bold pr-1">End-Use: </span>
									<span class="text-sm text-gray-700">{{ RFQHead.enduse}}</span>
								</div>
								<div class="col-lg-12">
									<span class="text-sm text-gray-700 font-bold pr-1">Purpose: </span>
									<span class="text-sm text-gray-700">{{ RFQHead.purpose}}</span>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-lg-12">
									<table class="w-full table-bordered !text-xs mb-3">
										<tr class="bg-gray-100">
											<td class="p-1 uppercase text-center" width="5%">
												<input type="checkbox" id="checkall" @click="CheckAll" :checked="allSelected">
											</td>
											<td class="p-1 uppercase text-center" width="7%">Qty</td>
											<td class="p-1 uppercase text-center" width="7%">UOM</td>
											<td class="p-1 uppercase" width="20%">PN No.</td>
											<td class="p-1 uppercase" width="">Item Description</td>
											<td class="p-1 uppercase" width="10%">WH Stocks</td>
											<td class="p-1 uppercase" width="15%">Date Needed</td>
										</tr>
										<tr v-for="pri in pritem_list">
											<td class="p-1 text-center">
												<input type="checkbox" class='checkboxes' v-model="pri.checkbox" :checked="checkall" :true-value="1" :false-value="0" @change="CountCheckbox">
											</td>
											<td class="p-1 text-center">{{ parseFloat(pri.quantity).toFixed(2) }}</td>
											<td class="p-1 text-center">{{ pri.uom }}</td>
											<td class="p-1">{{ pri.pn_no }}</td>
											<td class="p-1">{{ pri.item_description }}</td>
											<td class="p-1">{{ pri.wh_stocks }}</td>
											<td class="p-1">{{ pri.date_needed }}</td>
											<input type="hidden" v-model="pri.pr_details_id" >
										</tr>
									</table>
								</div>
							</div>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <a href="/pur_quote/print" class="btn btn-primary mr-2 w-44">Add</a> -->
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
									<h5 class="leading-tight">Are you sure you want to add this item/s?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full" @click="closeModal()">No</button>
									<button class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full" @click="AdditionalItems()">Yes</button>
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
			<div class="modal pt-4 px-3" :class="{ show:addVendorModal }">
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
										<option value="" disabled selected>Select Vendor</option>
										<option :value="v.id+'_'+v.vendor_head.vendor_name+'_'+v.identifier" v-for="v in vendorlist" :key="v.id">{{ v.vendor_head.vendor_name }} {{ (v.identifier != '') ? '('+v.identifier+')' : '' }}</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <a href="/pur_quote/print" class="btn btn-primary mr-2 w-44">Add Vendor</a> -->
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
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full" @click="closeModal()">No</button>
									<button class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full" @click="AdditionalVendor()">Yes</button>
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
				<div class="modal__content w-8/12 mb-5">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">Add Vendor</span>
							<a href="#" class="text-gray-600" @click="closeModal">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<hr class="mt-0">
					<div class="modal_s_items">
						<div class="row">
							<div class="col-lg-12 col-md-3">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Vendor</label>
									<select class="form-control" v-model="vendor_details">
										<option :value="v.id+'_'+v.vendor_head.vendor_name+'_'+v.identifier" v-for="v in vendorlist" :key="v.id">{{ v.vendor_head.vendor_name }} {{ (v.identifier != '') ? '('+v.identifier+')' : '' }}</option>
									</select>
								</div>
								<table class="table-bordered w-full !text-xs">
									<tr class="bg-gray-100">
										<td class="p-1 text-center" width="5%">No</td>
										<td class="p-1 text-center" width="10%">Qty</td>
										<td class="p-1" width="35%">Item Description</td>
										<td class="p-1" width="35%">Brand/Offer</td>
										<td class="p-1 text-center" width="15%">Unit Price</td>
									</tr>
									<tr v-for="(i, item_no) in pritem_list">
										<td class="p-1 align-top text-center">{{  item_no+1 }}</td>
										<td class="p-1 align-top text-center">{{ parseFloat(i.quantity).toFixed(2) }}</td>
										<td class="p-1 align-top">{{ i.item_description }}</td>
										<td class="align-top">
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top" ></textarea>
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top" ></textarea>
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top" ></textarea>
										</td>
										<td class="align-top">
											<div class="!h-14 border-b">
												<input type="number" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
												<select name="" id="" class=" p-1 w-full !align-top text-center" >
													<option value="">PHP</option>
												</select>
											</div>
											<div class="!h-14 border-b">
												<input type="number" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
												<select name="" id="" class=" p-1 w-full !align-top text-center" >
													<option value="">PHP</option>
												</select>
											</div>
											<div class="!h-14 border-b">
												<input type="number" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
												<select name="" id="" class=" p-1 w-full !align-top text-center">
													<option value="">PHP</option>
												</select>
											</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<a href="/pur_quote/print" class="btn btn-primary mr-2 w-44">Save</a>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
</template>