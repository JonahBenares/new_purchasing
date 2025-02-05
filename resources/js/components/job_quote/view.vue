<script setup>
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
	// let noted_by=ref(3);
	// let approved_by=ref(1);
	let count_jorlabor=ref(0);
	let count_jormaterial=ref(0);
	let term=ref('');
	let letters=ref([]);
	let rfqvendorid=ref('');
	let count_ccr=ref(0);
	let LaborDetails=ref([]);
	let MaterialDetails=ref([]);
	let currency=ref([]);
	let labor_offers=ref([]);
	let material_offers=ref([]);
	let count_vendor_labor_offers=ref(0);
	let count_vendor_material_offers=ref(0);
	let all_vendor_checkbox=ref(0);
	let all_labor_checkbox=ref(0);
	let all_material_checkbox=ref(0);
	let due_date=ref('');

	let aoq_status=ref('');
	let aoq_details_id=ref(0);
	let count_aoq_vendor=ref(0);
	let count_canvassed_aoq_v=ref(0);

	let aoq_no=ref('');
	let head=ref([]);
	let vendors=ref([]);
	let aoq_signatories=ref([]);
	let date_needed=ref('');
	let received_by=ref('');
	let award_recommended_by=ref('');
	let recommended_by=ref('');
	let approved_by=ref(6);

	const props = defineProps({
        id:{
            type:String,
            default:''
        },
		aoq_id:{
            type:String,
            default:''
        }
    })

	onMounted(async () => {
		GetRFQDetails()
		GetRFQTermsDetails()
		GetAdditionalItems()
		GetAdditionalVendors()
		getAOQHeadDetails()
		GetAOQStatus()
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
		currency.value = response.data.currency
		count_vendor_labor_offers.value=response.data.count_vendor_labor_offers
		count_vendor_material_offers.value=response.data.count_vendor_material_offers
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
		currency.value = response.data.currency
		count_vendor_labor_offers.value=response.data.count_vendor_labor_offers
		count_vendor_material_offers.value=response.data.count_vendor_material_offers
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

	const RemoveRFQVendorTerms = (order_no, terms_id) => {
		axios.get(`/api/remove_jo_terms/${terms_id}`).then(function () {
			rfq_vendor_terms.value.splice(order_no,1)
		});
	}

	const ShowPrintView = () => {
		router.push(`/job_quote/print/${props.id}`)
	}

	const printDiv = () => {
		window.print();
	}

	const GetAdditionalVendors = async () => {
		let response = await axios.get(`/api/get_jo_rfq_vendor_list/${props.id}`)
		vendorlist.value=response.data
	}

	const AdditionalVendorBtn= () => {
		if(due_date.value == ''){
			document.getElementById('duedate_').placeholder="Please fill in Due date."
			document.getElementById('duedate_').style.backgroundColor = '#FAA0A0';
		}else if(vendor_details.value == ''){
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
			formVendor.append('due_date', due_date.value)
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

				var count_check_material=document.getElementsByClassName('checkboxesmaterial');
				for(var x=0;x<count_check_material.length;x++){
					var check_material=document.getElementsByClassName('checkboxesmaterial')[x].checked;
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

	const CanvassCompleteBtn = () => {
		var count_labor_offers=document.getElementsByClassName('laboroffer_');
		var not_empty_labor_offers = 0;
			for(var l=0;l<count_labor_offers.length;l++){
				var labor_offers = document.getElementsByClassName('laboroffer_')[l].value
				if(labor_offers != "") {
					not_empty_labor_offers++;
				}
			}

		var count_material_offers=document.getElementsByClassName('materialoffer_');
		var not_empty_material_offers = 0;
			for(var l=0;l<count_material_offers.length;l++){
				var material_offers = document.getElementsByClassName('materialoffer_')[l].value
				if(material_offers != "") {
					not_empty_material_offers++;
				}
			}

			if(not_empty_labor_offers>=1 || not_empty_material_offers>=1){
				document.getElementById("canvasscompletebtn").disabled = false;
			}else{
				document.getElementById("canvasscompletebtn").disabled = true;
			}
	}

	const openDraftAlert = (jo_rfq_vendor_id) => {
		const formOffers= new FormData()
			formOffers.append('jo_rfq_head_id', props.id)
			formOffers.append('jo_rfq_vendor_id', jo_rfq_vendor_id)

			// var count_labor_offers=document.getElementsByClassName('laboroffer_');
			// for(var l=0;l<count_labor_offers.length;l++){
			// 	var jo_rfq_labor_offer_id =document.getElementsByClassName("laborofferid_")[l].value;
			// 	var labor_offer =document.getElementsByClassName("laboroffer_")[l].value;
			// 	var labor_unit_price=document.getElementsByClassName("laborunitprice_")[l].value;
			// 	var labor_currency=document.getElementsByClassName("laborcurrency_")[l].value;
			// 		const labor_o = {
			// 			jo_rfq_labor_offer_id:jo_rfq_labor_offer_id,
			// 			labor_offer:labor_offer,
			// 			labor_unit_price:labor_unit_price,
			// 			labor_currency:labor_currency,
			// 		}
			// 		labor_offers.value.push(labor_o)
			// }
			formOffers.append('laboroffers', JSON.stringify(RFQLaborOffers.value))

			// var count_material_offers=document.getElementsByClassName('materialoffer_');
			// for(var m=0;m<count_material_offers.length;m++){
			// 	var jo_rfq_material_offer_id =document.getElementsByClassName("materialofferid_")[m].value;
			// 	var material_offer =document.getElementsByClassName("materialoffer_")[m].value;
			// 	var material_unit_price=document.getElementsByClassName("materialunitprice_")[m].value;
			// 	var material_currency=document.getElementsByClassName("materialcurrency_")[m].value;
			// 		const material_o = {
			// 			jo_rfq_material_offer_id:jo_rfq_material_offer_id,
			// 			material_offer:material_offer,
			// 			material_unit_price:material_unit_price,
			// 			material_currency:material_currency,
			// 		}
			// 		material_offers.value.push(material_o)
			// }
			formOffers.append('materialoffers', JSON.stringify(RFQMaterialOffers.value))

			axios.post("/api/draft_jo_vendor", formOffers).then(function () {
				GetPerVendorDetails()
				DraftAlert.value = !DraftAlert.value
			});
	}

	const CanvassComplete = (jo_rfq_vendor_id) => {
		const formCanvass= new FormData()
		formCanvass.append('jo_rfq_head_id', props.id)
		formCanvass.append('jo_rfq_vendor_id', jo_rfq_vendor_id)
		formCanvass.append('laboroffers', JSON.stringify(RFQLaborOffers.value))
		formCanvass.append('materialoffers', JSON.stringify(RFQMaterialOffers.value))

		axios.post("/api/canvass_complete_jo_vendor", formCanvass).then(function () {
			CanvassCompleteAlert.value = !CanvassCompleteAlert.value
			GetPerVendorDetails()
			getAOQHeadDetails()
			GetAOQStatus()
		});
	}

	const getAOQHeadDetails = async () => {
		let response = await axios.get(`/api/create_new_jo_aoq_details/${props.id}`)
		aoq_no.value = response.data.aoq_no
		head.value = response.data.aoq_head_data
		vendors.value = response.data.rfq_vendor
		aoq_signatories.value=response.data.signatories
	}

	const allSelectedVendor=ref(false);
	const checkallven=ref([]);
	const CheckAllVendor = () => {
		var count_vendor=document.getElementsByClassName('vendor_checkboxes');
		for(var x=0;x<count_vendor.length;x++){
			var check_vendor=document.getElementsByClassName('vendor_checkboxes')[x].checked;
			if(!check_vendor){
				checkallven.value=allSelectedVendor
				if(all_vendor_checkbox.value == 0){
					vendors.value[x].vendor_checkbox=1;
				}
				document.getElementById("CreateAOQBtn").disabled = false;
			}else{
				checkallven.value=!allSelectedVendor
				if(all_vendor_checkbox.value == 1){
					vendors.value[x].vendor_checkbox=0;
				}
				document.getElementById("CreateAOQBtn").disabled = true;
			}
		}
	}

	const CountVendorCheckbox= () =>{
		var VendorisChecked = document.getElementsByClassName("vendor_checkboxes");
		var count=0;
		for(var x=0;x<VendorisChecked.length;x++){
			if(VendorisChecked[x].checked === true){
				count++;
			}
		}
			if(count>=1){
				document.getElementById("CreateAOQBtn").disabled = false;
			}else{
				document.getElementById("CreateAOQBtn").disabled = true;
			}
	}

	const CreateNewAOQAlert = () =>{
		if(date_needed.value == ''){
			document.getElementById('dateneeded_').style.backgroundColor = '#FAA0A0';
			document.getElementById("DateNeededAlert").style.display="block"
		}else if(recommended_by.value == ''){
			document.getElementById("DateNeededAlert").style.display="none"
			document.getElementById('dateneeded_').style.backgroundColor = '#FEFCE8';
			document.getElementById('approvedby_').style.backgroundColor = '#FEFCE8';
			document.getElementById('recommendedby_').style.backgroundColor = '#FAA0A0';
			document.getElementById("DropdownAlert").style.display="block"
		}else if(approved_by.value == ''){
			document.getElementById("DateNeededAlert").style.display="none"
			document.getElementById('dateneeded_').style.backgroundColor = '#FEFCE8';
			document.getElementById('recommendedby_').style.backgroundColor = '#FEFCE8';
			document.getElementById('approvedby_').style.backgroundColor = '#FAA0A0';
			document.getElementById("DropdownAlert").style.display="block"
		}else{
			document.getElementById('dateneeded_').style.backgroundColor = '#FEFCE8';
			document.getElementById('recommendedby_').style.backgroundColor = '#FEFCE8';
			document.getElementById('approvedby_').style.backgroundColor = '#FEFCE8';
			document.getElementById("DropdownAlert").style.display="none"
			document.getElementById("DateNeededAlert").style.display="none"
			CreateNewAOQModal.value = !CreateNewAOQModal.value
		}
	}

	const CreateNewAOQ= () =>{
		document.getElementById("YesCreate").disabled = true;
		document.getElementById("NoCreate").disabled = true;
		const formAOQHead= new FormData()
		formAOQHead.append('aoq_no', aoq_no.value)
		formAOQHead.append('jo_rfq_head_id', props.id)
		formAOQHead.append('jor_no', head.value.jor_no)
		formAOQHead.append('aoq_date', head.value.aoq_date)
		formAOQHead.append('date_needed', date_needed.value)
		formAOQHead.append('prepared_by', head.value.prepared_by)
		formAOQHead.append('received_by', head.value.requestor_id)
		formAOQHead.append('award_recommended_by', award_recommended_by.value)
		formAOQHead.append('recommended_by', recommended_by.value)
		formAOQHead.append('approved_by', approved_by.value)
		formAOQHead.append('aoq_vendors', JSON.stringify(vendors.value))
			axios.post("/api/add_jo_aoq_head", formAOQHead).then(function (response) {
			router.push('/job_aoq/print_te/'+response.data)
			});
	}

	const GetAOQStatus = async () => {
		let response = await axios.get(`/api/joaoq_status/${props.aoq_id}`)
		aoq_status.value=response.data.aoq_status
		aoq_details_id.value=response.data.aoq_details_id
		count_aoq_vendor.value=response.data.count_aoq_vendor
		count_canvassed_aoq_v.value=response.data.count_canvassed
	}

	const openAOQ = () => {
		if(aoq_status.value == 'Done TE'){
            router.push('/job_aoq/view/'+props.aoq_id+'/'+aoq_details_id.value)
        }else{
            router.push(`/job_aoq/print_te/${props.aoq_id}`)
        }
	}

	const vendor =  ref(rfqvendorid);
	const showModal = ref(false)
	const addItems = ref(false)
	const addVendorModal = ref(false)
	const DraftAlert = ref(false)
	const CanvassCompleteAlert = ref(false)
	const AdditionalVendorAlert = ref(false)
	const AdditionalItemsAlert = ref(false)
	const CreateNewAOQModal = ref(false)
	const hideModal = ref(true)
	const successAlert = ref(false)
	const hideAlert = ref(true)
	const openSuccessAlert = () => {
			successAlert.value = !successAlert.value
			chooseVendor.value = !hideModal.value
		}
	const chooseVendor = ref(false)
	
	const openChooseVendor = () => {
			chooseVendor.value = !chooseVendor.value
		}
	const openModel = () => {
		showModal.value = !showModal.value
	}

	const openAddItem = () => {
		addItems.value = !addItems.value
	}

	const openVendorModel = () => {
		addVendorModal.value = !addVendorModal.value
	}
	const closeModal2 = () => {
		chooseVendor.value = !hideModal.value
	}
	const closeAlert = () => {
		successAlert.value = !hideModal.value
	}

	const CloseAOQAlert = () => {
		CreateNewAOQModal.value = !hideModal.value
	}
	const closeModal = () => {
		// GetDraftCanvassDetails()
		GetAdditionalItems()
		GetAdditionalVendors()
		// getAOQHeadDetails()
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
</script>
<template>
	<navigation>
		<div class="row" id="breadcrumbs">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Request for Quotation <small>View</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/job_quote">JO Request for Quotation</a></li>
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
										<button class="btn btn-sm p-1 px-3 !text-xs btn-primary" @click="openAddItem()" v-if="((count_jorlabor != 0 || count_jormaterial != 0) && count_ccr == 0)">Add Items</button>
										<button class="btn btn-sm p-1 px-3 !text-xs btn-primary" @click="ShowPrintView()">Show Print View</button>
									</div>
								</div>
							</div>
							<br>
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
								<div class="bg-gray-200 !border !border-orange-200" >
									<div v-for="rvi in RFQVendors">
										<div class="page">
											<div v-if="(vendor == rvi.jo_rfq_vendor_id)">
												<div class="bg-white !mx-auto w-[21cm] print:!w-full" >
													<div class="subpage">
														<table class="table-bosrdered w-full text-xs mb-0">
															<tbody>
																<tr>
																	<td class="" width="8%">Date: </td>
																	<td class="">{{ RFQHead.rfq_date }}</td>
																	<td class="" width="10%">RFQ No.:</td>
																	<td class="" >{{ RFQHead.rfq_no }}</td>
																</tr>
																<tr>
																	<td class="">JOR No:</td>
																	<td class="" width="55%">{{ RFQHead.jor_no }}</td>
																	<td class="" >RFQ Name:</td>
																	<td class="" >{{ RFQHead.rfq_name }}</td>
																</tr>
															</tbody>
														</table>
														<table class="table-bsordered w-full text-xs mb-2">
															<tbody>
																<tr>
																	<td class="" width="8%">Supplier: </td>
																	<td class="" width="55%">{{ rvi.vendor_name }}</td>
																	<td class="" width="10%">Contact No.:</td>
																	<td class="" >{{ rvi.phone_no }}</td>
																</tr>
															</tbody>
														</table>
														<div class="border-y-2 py-1 mb-2">
															<p class="text-sm font-bold text-gray-600 text-center m-0">{{ RFQHead.project_activity }}</p>
															<p class="text-xs text-gray-600 text-center m-0">Project Title/Description</p>
														</div>
														<table class="table-bordered w-full !text-xs mb-2" v-if = "RFQLaborDetails.length != 0">
															<thead>
																<tr class="bg-gray-100">
																	<td class="p-1 text-center" width="5%">#</td>
																	<td class="p-1" width="37%">Scope of Work</td>
																	<td class="p-1" width="5%">UOM</td>
																	<td class="p-1" width="31%">Offer</td>
																	<td class="p-1 text-center" width="10%">Unit Price</td>
																	<td class="p-1 text-center" width="10%">Total Price</td>
																</tr>
																<tr>
																	<td class="p-1 align-top" colspan="6">{{ RFQHead.general_description }}</td>
																</tr>
																<tr hidden><td hidden><span hidden>{{ labor_no=1 }}</span></td></tr>
															</thead>
															<tbody v-for="rld in RFQLaborDetails" class="p-0">
																<tr v-if="rld.jo_rfq_vendor_id == rvi.jo_rfq_vendor_id">
																	<td class="p-1 align-top text-center">{{ labor_no }}</td>
																	<td class="p-1 align-top">{{ rld.scope_of_work }} </td>
																	<td class="p-1 align-top text-center">{{ rld.uom }}</td>
																	<td class="align-top">
																		<template v-for="rlo in RFQLaborOffers">
																			<textarea name="" id="" class="border-b resize w-full  h-screen !max-h-[62px] !min-h-[100] p-1 laboroffer_" v-model="rlo.offer" v-if="(rld.jo_rfq_labor_details_id == rlo.jo_rfq_labor_details_id && rvi.canvassed == 0)" @change="CanvassCompleteBtn"></textarea>
																			<textarea name="" id="" class="border-b resize w-full  h-screen !max-h-[62px] !min-h-[100] p-1 laboroffer_" v-model="rlo.offer" v-if="(rld.jo_rfq_labor_details_id == rlo.jo_rfq_labor_details_id && rvi.canvassed == 1)" readonly></textarea>
																			<input type="hidden" class="laborofferid_" v-model="rlo.jo_rfq_labor_offer_id" >
																		</template>
																	</td>
																	<td hidden><span hidden>{{ labor_no++ }}</span></td>
																	<td class="align-top">
																		<template v-for="rlo in RFQLaborOffers">
																			<div class="">
																				<template v-if="(rld.jo_rfq_labor_details_id == rlo.jo_rfq_labor_details_id && rvi.canvassed == 0)">
																					<input type="number" class="border-b p-1 w-full !align-top !h-8 text-center laborunitprice_" placeholder="00.00" v-model="rlo.unit_price">
																					<select class="p-1 m-0 leading-none w-full text-center border-b  !h-8 block text-xs whitespace-nowrap laborcurrency_" v-model="rlo.labor_currency">
																						<option v-for="cur in currency" v-bind:key="cur" v-bind:value="cur">{{ cur }}</option>
																					</select>
																					<input type="hidden" class="laborofferid_" v-model="rlo.jo_rfq_labor_offer_id" >
																				</template>
																			</div>

																			<template v-if="(rld.jo_rfq_labor_details_id == rlo.jo_rfq_labor_details_id && rvi.canvassed == 1)">
																				<div class="border-b p-1 w-full !h-8 !align-top text-center laborunitprice_"> {{ parseFloat(rlo.unit_price).toFixed(2) }}</div>
																				<div class="border-b p-1 w-full !h-8 !align-top text-center laborcurrency_">{{ rlo.labor_currency }}</div>
																			</template>
																		</template>
																	</td>
																	<td>
																		<div class="border-b p-1 w-full !h-8 !align-top text-center"></div>
																		<div class="border-b p-1 w-full !h-8 !align-top text-center"></div>
																		<div class="border-b p-1 w-full !h-8 !align-top text-center"></div>
																		<div class="border-b p-1 w-full !h-8 !align-top text-center"></div>
																		<div class="border-b p-1 w-full !h-8 !align-top text-center"></div>
																		<div class="border-b p-1 w-full !h-8 !align-top text-center"></div>
																	</td>
																</tr>
															</tbody>
														</table>
														<table class="table-bordered w-full !text-xs mb-2" v-if = "RFQMaterialDetails.length != 0">
															<thead>
																<tr class="bg-gray-100">
																	<td class="p-1 text-center" width="5%">No</td>
																	<td class="p-1 text-center" width="5%">Qty</td>
																	<td class="p-1" width="33%">Item Description</td>
																	<td class="p-1" width="5%">UOM</td>
																	<td class="p-1" width="31%">Brand/Offer</td>
																	<td class="p-1 text-center" width="10%">Unit Price</td>
																	<td class="p-1 text-center" width="10%">Total Price</td>
																</tr>
																<tr hidden><span hidden>{{ item_no=1 }}</span></tr>
															</thead>
															<tbody v-for="rmd in RFQMaterialDetails" class="p-0">
																<tr v-if="rmd.jo_rfq_vendor_id == rvi.jo_rfq_vendor_id">
																	<td class="p-1 align-top text-center">{{ item_no }}</td>
																	<td class="p-1 align-top text-center">{{ parseFloat(rmd.quantity).toFixed(2) }}</td>
																	<td class="p-1 align-top">{{ rmd.item_description }}</td>
																	<td class="p-1 align-top text-center">{{ rmd.uom }}</td>
																	<td hidden><span hidden>{{ item_no++ }}</span></td>
																	<td class="align-top">
																		<template v-for="rmo in RFQMaterialOffers">
																			
																			<template v-if="(rmo.jo_rfq_material_details_id == rmd.jo_rfq_material_details_id && rvi.canvassed == 0)">
																				<textarea class="border-b p-1 w-full h-16 !align-top materialoffer_" v-model="rmo.offer" @change="CanvassCompleteBtn"></textarea>
																				<input type="hidden" class="materialofferid_" v-model="rmo.jo_rfq_material_offer_id" >
																			</template>
																			<template v-if="(rmo.jo_rfq_material_details_id == rmd.jo_rfq_material_details_id && rvi.canvassed == 1)">
																				<textarea class="border-b p-1 w-full h-16 !align-top" v-model="rmo.offer" readonly></textarea>
																			</template>
																		</template>
																	</td>
																	<td class="align-top p-0">
																		<template v-for="rmo in RFQMaterialOffers">
																			<div class="border-b" v-if="rmo.jo_rfq_material_details_id == rmd.jo_rfq_material_details_id">
																				<template v-if="(rvi.canvassed == 1)">
																					<input type="number" class=" h-8 p-1 w-full !align-top text-center text-xs border-b materialunitprice_" placeholder="00.00" v-model="rmo.unit_price">
																					<select class=" h-8 p-1 m-0 leading-none w-full text-center text-xs whitespace-nowrap materialcurrency_" v-model="rmo.material_currency">
																						<option v-for="cur in currency" v-bind:key="cur" v-bind:value="cur">{{ cur }}</option>
																					</select>
																					<input type="hidden" class="materialofferid_" v-model="rmo.jo_rfq_material_offer_id" >
																				</template>
																				<template v-if="(rvi.canvassed == 0)">
																					<div class="border-b p-1 w-full h-8 !align-top text-center"> {{ parseFloat(rmo.unit_price).toFixed(2) }}</div>
																					<div class="border-b p-1 w-full h-8 !align-top text-center"> {{ rmo.material_currency }}</div>
																				</template>
																			</div>
																		</template>
																	</td>
																	<td>
																		<div class="border-b p-1 w-full !h-8 !align-top text-center"></div>
																		<div class="border-b p-1 w-full !h-8 !align-top text-center"></div>
																		<div class="border-b p-1 w-full !h-8 !align-top text-center"></div>
																		<div class="border-b p-1 w-full !h-8 !align-top text-center"></div>
																		<div class="border-b p-1 w-full !h-8 !align-top text-center"></div>
																		<div class="border-b p-1 w-full !h-8 !align-top text-center"></div>
																	</td>
																</tr>
															</tbody>
														</table>

														<table class="table-bordered w-full !text-xs mb-2">
															<tbody>
																<tr class="bg-gray-100">
																	<td class="p-1" width="">Notes</td>
																</tr>
																<tr v-for="hn in HeadNotes">
																	<td class="p-1 align-top">{{ hn.notes }}</td>
																</tr>
															</tbody>
														</table>
														<table class="table-bordesred w-full text-xs">
															<tbody>
																<tr>
																	<td colspan="4">1. Quotation must be submitted on or before {{ rvi.view_due_date }}</td>
																</tr>
																<tr>
																	<td colspan="4">2. Please Fill - Up :</td>
																</tr>
															</tbody>
															<tbody v-if="(rvi.canvassed==0)">
																<tr class="po_buttons">
																	<td width="10%"></td>
																	<td width="40%" colspan="2">
																		<div class="flex justify-between space-x-1">
																			<input type="text" class="p-1 w-full bg-yellow-50" id="newterms" v-model="term">
																			<button type="button" class="btn btn-primary p-1" @click="AddRFQTerms(rvi.jo_rfq_vendor_id)">
																			<PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon></button>
																		</div>
																	</td>
																	<td width="10%"></td>
																</tr>
																<tr hidden><span hidden>{{ orderno=1 }}</span></tr>
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
																<tr>
																	<td width="10%"></td>
																	<td width="1%">{{ letters[index] }}.</td>
																	<td width="40%">{{ vt.terms }}</td>
																	<td width="10%"></td>
																</tr>
															</tbody>
														</table>
														<br>
														<!-- <div class="border w-full text-center p-4 bg-blue-100"> Signatories here</div> -->
														<table class="w-full text-xs">
															<tbody>
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
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
										<div class="row my-2 po_buttons" v-if="vendor == rvi.jo_rfq_vendor_id"> 
											<div class="col-lg-12 col-md-12">
												<div class="flex justify-center space-x-2" v-if="vendor == rvi.jo_rfq_vendor_id && rvi.canvassed == 0">
													<button type="submit" class="btn btn-warning text-white mr-2 w-" id = "draftbtn" @click="openDraftAlert(rvi.jo_rfq_vendor_id)">Save as Draft</button>
													<button type="submit" class="btn btn-primary" id = "canvasscompletebtn" @click="CanvassComplete(rvi.jo_rfq_vendor_id)" v-if="count_vendor_labor_offers != 0 || count_vendor_material_offers != 0">Canvass Complete</button>
													<button type="submit" class="btn btn-primary" id = "canvasscompletebtn" @click="CanvassComplete(rvi.jo_rfq_vendor_id)" v-else disabled>Canvass Complete</button>
												</div>
												<div class="flex justify-center space-x-2" v-if="vendor == rvi.jo_rfq_vendor_id && rvi.canvassed == 1">
													<button type="submit" class="btn btn-primary mr-2 w-44"  @click="printDiv()">Print</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<br>
								<!-- <div class="col-lg-4 col-md-3">
									<div class="flex justify-start space-x-1">
										<button type="submit" class="btn btn-primary">Canvass Complete</button>
										<button type="submit" class="btn btn-warning text-white mr-2 w-" @click="openWarningAlert()">Save as Draft</button>
									</div>
								</div> -->
							<div class="row my-2 po_buttons"> 
								<div class="col-lg-12 col-md-12" >
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
											<!-- <a href="" class="btn !bg-gray-200 !w-36">Pasdrint TE</a> -->
											<template v-if="props.aoq_id != 0">
												<button type="submit" class="btn !bg-green-500 text-white !w-36"  v-if="(count_aoq_vendor == count_canvassed_aoq_v && count_canvassed_aoq_v != 0)" @click="openAOQ()">Proceed AOQ</button>
												<button type="submit" class="btn !bg-green-500 text-white !w-36"  style="pointer-events: none;" v-else @click="openAOQ()">Proceed AOQ</button>
											</template>
											<template v-if="props.aoq_id == 0">
												<a href="#" @click="openChooseVendor"  class="btn !bg-green-500 text-white !w-36" v-if="(count_ccr != 0)">Create AOQs</a>
												<a href="#" class="btn !bg-green-500 text-white !w-36"  style="pointer-events: none;" v-else>Create AOQs</a>
												<!-- <a href="/pur_aoq/print_te" class="btn !bg-green-500 text-white  !w-36" v-else>Create AOQs</a> -->
											</template>
											
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
										<thead>
											<tr class="bg-gray-100">
												<td class="p-1 uppercase text-center" width="2%">
													<input type="checkbox" id="checkalllabor" @click="CheckAllLabor" :checked="allSelectedLabor" v-model="all_labor_checkbox" :true-value="1" :false-value="0">
												</td>
												<td class="p-1 uppercase text-center" width="2%">#</td>
												<td class="p-1 uppercase" width="">Scope Of Works</td>
												<td class="p-1 uppercase text-center" width="10%">Qty</td>
												<td class="p-1 uppercase text-center" width="10%">UOM</td>
											</tr>
										</thead>
										<tbody>
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
										</tbody>
									</table>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-lg-12">
									<table class="w-full table-bordered !text-xs mb-3">
										<thead>
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
										</thead>
										<tbody>
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
										</tbody>
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
									<a href="/job_quote/new/0" class="btn !text-white !bg-yellow-400 btn-sm !rounded-full w-full">Create New RFQ</a>
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
									<label class="text-gray-500 m-0" for="">Due Date</label>
									<input type="text" class="p-2 border w-full text-sm" placeholder="Due Date"  onfocus="(this.type='date')" v-model="due_date" id="duedate_">
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
									<a href="/job_quote/new/0" class="btn !text-white !bg-blue-400 btn-sm !rounded-full w-full">Create New RFQ</a>
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
			<div class="modal pt-4 px-3" :class="{ show:chooseVendor }">
				<div @click="closeModal2()" class="w-full h-full fixed"></div>
				<div class="modal__content w-8/12 mb-5">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">Choose Vendor and please fill out the following fields</span>
							<a href="#" class="text-gray-600" @click="closeModal2()">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<hr class="mt-0">
					<div class="modal_s_items">
						<div class="row">
							<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">JOR No: </span>
									<span class="text-sm text-gray-700">{{ head.jor_no }}</span>
								</div>
								<div class="col-lg-3">
									<span class="text-sm text-gray-700 font-bold pr-1">AOQ No: </span>
									<span class="text-sm text-gray-700">{{ aoq_no }}</span>
									<!-- <input type="text" class="form-control" placeholder="RFQ No" v-model="aoq_no" readonly> -->
								</div>
								<div class="col-lg-3">
									<span class="text-sm text-gray-700 font-bold pr-1">Date: </span>
									<span class="text-sm text-gray-700">{{ head.aoq_date}}</span>
								</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">Department: </span>
									<span class="text-sm text-gray-700">{{ head.department }}</span>
								</div>
								<div class="col-lg-3">
									<span class="text-sm text-gray-700 font-bold pr-1">RFQ No: </span>
									<span class="text-sm text-gray-700">{{ head.rfq_no }}</span>
								</div>
						</div>
						<div class="row">
								<!-- <div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">End-Use:</span>
									<span class="text-sm text-gray-700">{{ head.enduse }}</span>
								</div> -->
								<div class="col-lg-3">
									<span class="text-sm text-gray-700 font-bold pr-1">Requested By: </span>
									<span class="text-sm text-gray-700">{{ head.requestor}}</span>
								</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
									<span class="text-sm text-gray-700 font-bold pr-1">Purpose: </span>
									<span class="text-sm text-gray-700">{{ head.purpose }}</span>
								</div>
						</div>
						<br>
						<div class="row">
							<div class="col-lg-12">
								<table class="w-full table-bordered text-sm" >
									<tbody>
										<tr class="bg-gray-100">
											<td class="p-1" width="2%"><input type="checkbox" id="checkallven" @click="CheckAllVendor" :checked="allSelectedVendor" v-model="all_vendor_checkbox" :true-value="1" :false-value="0"></td>
											<td class="p-1">List of Vendors</td>
										</tr>
										<tr class="bg-yellow-50" v-for="(v, i) in vendors" >
											<td class="p-1">
												<input type="checkbox" class='vendor_checkboxes' v-model="v.vendor_checkbox" :checked="checkallven" :true-value="1" :false-value="0" @change="CountVendorCheckbox">
											</td>
											<td class="p-1">{{ v.vendor_name }} ({{v.vendor_identifier}})</td>
											<td><input type="" class="form-control" v-model="v.jo_rfq_vendor_id"></td>
											<td hidden ><input type="hidden" class="form-control" v-model=
												"v.jo_rfq_vendor_id"></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<br>
						<hr>
						<div class="bg-red-100 border-2 border-red-200 w-full p-2 text-red-500 my-1 mb-2 hidden"  id="DateNeededAlert">
						<div class="flex justify-start space-x-2">
								<ExclamationTriangleIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></ExclamationTriangleIcon>
								<span>Please fill in date needed.</span>
							</div>
						</div>
						<div class="bg-red-100 border-2 border-red-200 w-full p-2 text-red-500 my-1 mb-2 hidden"  id="DropdownAlert">
						<div class="flex justify-start space-x-2">
								<ExclamationTriangleIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></ExclamationTriangleIcon>
								<span>Please select an option from the dropdown.</span>
							</div>
						</div>
						<div class="modal_s_items">
							<div class="row">
								<div class="col-lg-4 col-md-4">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Date Needed</label>
										<input type="text" class="p-2 border w-full bg-yellow-50 text-sm" onfocus="(this.type='date')" placeholder="Date Needed" id= "dateneeded_" v-model="date_needed">
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Prepared by</label>
										<input type="text" class="form-control" v-model="head.prepared_by_name" readonly>
										<input type="hidden" class="form-control" v-model="head.prepared_by">
										<!-- <select class="form-control" placeholder="" >
											<option value="">--Select Employee--</option>
										</select> -->
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Received and Checked by</label>
										<!-- <select class="p-2 border w-full bg-yellow-50 text-sm" v-model="received_by" id= "receivedby_">
											<option value="">--Select Employee--</option>
											<option :value="s.id" v-for="s in signatories" :key="s.id">{{ s.name }}</option>
										</select> -->
										<input type="text" class="form-control" v-model="head.requestor" readonly>
										<input type="hidden" class="form-control" v-model="head.requestor_id">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-4">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Award Recommended by</label>
										<select class="p-2 border w-full bg-yellow-50 text-sm" v-model="award_recommended_by" id= "awardrecommendedby_">
											<option value="">--Select Employee--</option>
											<option :value="s.id" v-for="s in signatories" :key="s.id">{{ s.name }}</option>
										</select>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Recommending Approval</label>
										<select class="p-2 border w-full bg-yellow-50 text-sm" v-model="recommended_by" id= "recommendedby_">
											<option value="">--Select Employee--</option>
											<option :value="s.id" v-for="s in signatories" :key="s.id">{{ s.name }}</option>
										</select>
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
										<label class="text-gray-500 m-0" for="">Approved by</label>
										<select class="p-2 border w-full bg-yellow-50 text-sm" v-model="approved_by" id= "approvedby_">
											<option value="">--Select Employee--</option>
											<option :value="s.id" v-for="s in signatories" :key="s.id">{{ s.name }}</option>
										</select>
									</div>
								</div>
							</div>
							<hr>
						</div> 
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn btn-primary mr-2 w-44" id="CreateAOQBtn" @click="CreateNewAOQAlert()" disabled>Save</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:CreateNewAOQModal }">
				<div @click="CloseAOQAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h5 class="leading-tight">Are you sure you want to save this new AOQ?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full" id="NoCreate"  @click="CloseAOQAlert()">No</button>
									<button class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full" id ="YesCreate" @click="CreateNewAOQ()">Yes</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</Transition>
		
	</navigation>
</template>