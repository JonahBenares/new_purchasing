<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, CheckIcon} from '@heroicons/vue/24/solid'
	import axios from 'axios';
    import { onMounted, ref } from "vue"
	import { useRouter } from "vue-router";
	const router = useRouter();
	const vendor =  ref();
	const preview =  ref();

	let head=ref([]);
	let vendordets=ref([]);
	let aoq_labor=ref([]);
	let aoq_labor_offers=ref([]);
	let aoq_material=ref([]);
	let aoq_material_offers=ref([]);
	let letters=ref([]);
	let vendor_terms=ref([]);
	let previous=ref([]);
	let next=ref([]);
	let max_id=ref('');
	let latest_jo_aoq_details_id=ref('');
	let count_labor_awarded=ref(0);
	let count_material_awarded=ref(0);
	let count_canvassed_aoq_v=ref(0);
	let count_aoq_vendors=ref(0);

	let previewhead=ref([]);
	let aoq_vendor=ref([]);
	let preview_labor_data=ref([]);
	let preview_first_labor_offers=ref([]);
	let preview_second_labor_offers=ref([]);
	let preview_third_labor_offers=ref([]);
	let preview_material_data=ref([]);
	let first_offers=ref([]);
	let second_offers=ref([]);
	let third_offers=ref([]);
	let preview_vendor_terms=ref([]);
	let all_terms=ref([]);

	const props = defineProps({
        id:{
            type:String,
            default:''
        },
		jo_aoq_details_id:{
            type:String,
            default:''
        },
    })

	onMounted(async () => {
		getAOQDoneTEDetails()
		getAOQPreviewDetails()
		ReloadAwarded()
	})

	const getAOQDoneTEDetails = async (jo_aoq_details_id) => {
		if(jo_aoq_details_id != undefined){
			let response = await axios.get(`/api/joaoq_donete_details/${props.id}/${jo_aoq_details_id}`)
			head.value = response.data.aoq_head_data
			vendordets.value = response.data.aoq_vendor_data
			aoq_labor.value = response.data.aoq_labor_data
			aoq_labor_offers.value = response.data.aoq_labor_offers_data
			aoq_material.value = response.data.aoq_material_data
			aoq_material_offers.value = response.data.aoq_material_offers_data
			letters.value=response.data.letters
			vendor_terms.value = response.data.vendor_terms
			max_id.value = response.data.max_id
			previous.value = response.data.previous
			next.value = response.data.next
			latest_jo_aoq_details_id.value = jo_aoq_details_id
			count_labor_awarded.value = response.data.count_labor_awarded
			count_material_awarded.value = response.data.count_material_awarded
			count_canvassed_aoq_v.value = response.data.count_canvassed_aoq_v
			count_aoq_vendors.value = response.data.count_aoq_vendors
		}else{
			let response = await axios.get(`/api/joaoq_donete_details/${props.id}/${props.jo_aoq_details_id}`)
			head.value = response.data.aoq_head_data
			vendordets.value = response.data.aoq_vendor_data
			aoq_labor.value = response.data.aoq_labor_data
			aoq_labor_offers.value = response.data.aoq_labor_offers_data
			aoq_material.value = response.data.aoq_material_data
			aoq_material_offers.value = response.data.aoq_material_offers_data
			letters.value=response.data.letters
			vendor_terms.value = response.data.vendor_terms
			max_id.value = response.data.max_id
			previous.value = response.data.previous
			next.value = response.data.next
			latest_jo_aoq_details_id.value = props.jo_aoq_details_id
			count_labor_awarded.value = response.data.count_labor_awarded
			count_material_awarded.value = response.data.count_material_awarded
			count_canvassed_aoq_v.value = response.data.count_canvassed_aoq_v
			count_aoq_vendors.value = response.data.count_aoq_vendors
		}

		if(count_aoq_vendors.value != count_canvassed_aoq_v.value ){
			openAOQAlert.value = !openAOQAlert.value
		}else{
			openAOQAlert.value = !hideModal.value
		}

	}

	const ReloadAwarded = async (jo_aoq_details_id) => {
		if(jo_aoq_details_id != undefined){
			var details_id = jo_aoq_details_id
		}else{
			var details_id = props.jo_aoq_details_id
		}
		let response = await axios.get(`/api/joaoq_donete_details/${props.id}/${details_id}`)
		vendordets.value = response.data.aoq_vendor_data
		aoq_labor.value = response.data.aoq_labor_data
		aoq_labor_offers.value = response.data.aoq_labor_offers_data
		aoq_material.value = response.data.aoq_material_data
		aoq_material_offers.value = response.data.aoq_material_offers_data
		letters.value=response.data.letters
		vendor_terms.value = response.data.vendor_terms
		count_labor_awarded.value = response.data.count_labor_awarded
		count_material_awarded.value = response.data.count_material_awarded
		latest_jo_aoq_details_id.value = details_id
		max_id.value = response.data.max_id
		previous.value = response.data.previous
		next.value = response.data.next

		// if(count_labor_awarded.value != 0 || count_material_awarded.value != 0){
		// 	document.getElementById("savejoaoqbtn").disabled = false;
		// }else{
		// 	document.getElementById("savejoaoqbtn").disabled = true;
		// }
	}

	const getAOQPreviewDetails = async () => {
		let response = await axios.get(`/api/jo_aoq_head_details/${props.id}`)
		previewhead.value = response.data.aoq_head_data
		aoq_vendor.value = response.data.aoq_vendor_data
		// preview_aoq_items.value = response.data.aoq_items_data
		preview_material_data.value = response.data.material_data
		first_offers.value = response.data.first_offers
		second_offers.value = response.data.second_offers
		third_offers.value = response.data.third_offers
		preview_labor_data.value = response.data.labor_data
		preview_first_labor_offers.value = response.data.first_labor_offers
		preview_second_labor_offers.value = response.data.second_labor_offers
		preview_third_labor_offers.value = response.data.third_labor_offers
		preview_vendor_terms.value = response.data.vendor_terms
		all_terms.value = response.data.all_terms
	}

	const UpdateLaborOffersAwarded= (loop, jo_rfq_labor_offer_id, jor_labor_details_id,latest_jo_aoq_details_id) => {
		const labor_award = document.getElementById("laborawarded_"+loop).value;
			const formLaborOffers= new FormData()
			formLaborOffers.append('jo_rfq_head_id', head.value.jo_rfq_head_id)
			formLaborOffers.append('jo_rfq_labor_offer_id', jo_rfq_labor_offer_id)
			formLaborOffers.append('jor_labor_details_id', jor_labor_details_id)
			formLaborOffers.append('awarded', labor_award ?? 0)
			axios.post("/api/update_labor_offers_awarded/", formLaborOffers).then(function (response) {
				ReloadAwarded(latest_jo_aoq_details_id)
				getAOQPreviewDetails()
			});
	}

	const UpdateLaborOffersComments= (loop, jo_rfq_labor_offer_id, latest_jo_aoq_details_id) => {
		const labor_comments = document.getElementById("laborcomments_"+loop).value;
			const formLaborOffers= new FormData()
			formLaborOffers.append('jo_rfq_labor_offer_id', jo_rfq_labor_offer_id)
			formLaborOffers.append('comments', labor_comments)
			axios.post("/api/update_labor_offers_comments/", formLaborOffers)
			ReloadAwarded(latest_jo_aoq_details_id)
			getAOQPreviewDetails()
	}

	const UpdateMaterialOffersAwarded= (loop, jo_rfq_material_offer_id, jor_material_details_id,latest_jo_aoq_details_id) => {
		const material_award = document.getElementById("materialawarded_"+loop).value;
			const formMaterialOffers= new FormData()
			formMaterialOffers.append('jo_rfq_head_id', head.value.jo_rfq_head_id)
			formMaterialOffers.append('jo_rfq_material_offer_id', jo_rfq_material_offer_id)
			formMaterialOffers.append('jor_material_details_id', jor_material_details_id)
			formMaterialOffers.append('awarded', material_award ?? 0)
			axios.post("/api/update_material_offers_awarded/", formMaterialOffers).then(function (response) {
				ReloadAwarded(latest_jo_aoq_details_id)
				getAOQPreviewDetails()
			});
	}

	const UpdateMaterialOffersComments= (loop, jo_rfq_material_offer_id, latest_jo_aoq_details_id) => {
		const material_comments = document.getElementById("materialcomments_"+loop).value;
			const formMaterialOffers= new FormData()
			formMaterialOffers.append('jo_rfq_material_offer_id', jo_rfq_material_offer_id)
			formMaterialOffers.append('comments', material_comments)
			axios.post("/api/update_material_offers_comments/", formMaterialOffers)
			ReloadAwarded(latest_jo_aoq_details_id)
			getAOQPreviewDetails()
	}

	const dangerAlert = ref(false)
	const successAlert = ref(false)
	const saveAlert = ref(false)
	const draftAlert = ref(false)
    const infoAlert = ref(false)
	const hideAlert = ref(true)
	const openAOQAlert = ref(false)
	
	const openDangerAlert = () => {
		dangerAlert.value = !dangerAlert.value
	}
    const openSuccessAlert = () => {
		successAlert.value = !successAlert.value
		chooseVendor.value = !hideModal.value
	}

	const openSaveAlert = () => {
		saveAlert.value = !saveAlert.value
	}

	const SaveAOQ = () => {
		saveAlert.value = !hideAlert.value
		axios.post(`/api/save_jo_aoq/${props.id}`).then(function (response) {
			router.push(`/job_aoq/print_te/${props.id}`)
		});
	}

	const openAOQ = (jo_rfq_head_id) => {
		axios.post(`/api/open_jo_aoq/${props.id}`).then(function () {
			router.push('/job_quote/view/'+jo_rfq_head_id+'/'+props.id)
		});
	}

	const openDraftAlert = () => {
		axios.post(`/api/update_jo_aoq_draft/${props.id}`)
		draftAlert.value = !draftAlert.value
	}
	const closeAlert = () => {
		successAlert.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
		draftAlert.value = !hideAlert.value
		infoAlert.value = !hideAlert.value
		saveAlert.value = !hideAlert.value
		openAOQAlert.value = !hideModal.value
	}


	const showAddVendor = ref(false)
	const cancelAlert = ref(false)
	const chooseVendor = ref(false)
	const showPreview = ref(false)
	const hideModal = ref(true)
	

	const openChooseVendor = () => {
		chooseVendor.value = !chooseVendor.value
	}
	const openAddVendor = () => {
		showAddVendor.value = !showAddVendor.value
	}
	const openPreview = () => {
		showPreview.value = !showPreview.value
	}

	const CancelAlert = () => {
		cancelAlert.value = !cancelAlert.value
	}

	const CancelTransaction = () => {
		axios.get(`/api/cancel_jo_aoq/${props.id}`).then(function () {
			router.push('/job_aoq')
		});
	}

	const closeModal = () => {
		showAddVendor.value = !hideModal.value
		showPreview.value = !hideModal.value
		chooseVendor.value = !hideModal.value
		saveAlert.value = !hideModal.value
		cancelAlert.value = !hideModal.value
		
	}

</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Abstract of Quotation <small>Award</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/job_aoq">JO Abstract of Quotation</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Award</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="bg-yellow-400 text-white px-3 py-2 font-bold" v-if="(head.status != 'Cancelled' && head.aoq_status == 'For TE')">For Technical Evaluation (AOQ - {{props.id}})</div>
		<div class="bg-blue-400 text-white px-3 py-2 font-bold" v-if="(head.status != 'Cancelled' && head.aoq_status == 'Done TE')">Done Technical Evaluation (AOQ - {{props.id}})</div>
		<div class="bg-lime-500 text-white px-3 py-2 font-bold" v-if="(head.status != 'Cancelled' && head.aoq_status == 'Awarded')">Awarded (AOQ - {{props.id}})</div>
		<div class="bg-red-500 text-white px-3 py-2 font-bold" v-if="(head.status == 'Cancelled')">CANCELLED (AOQ - {{props.id}}) Cancelled date: {{head.cancelled_date}}, Cancelled by: {{head.cancelled_name}}</div>
		<div class="row">
			
			<div class="col-12 grid-margin stretch-card">
				
				<div class="card">
				<div class="card-body">
					<!-- <span class="pt-2">
						<h3 class="card-title !text-lg m-0">Request for Quotation <small>New</small></h3>
					</span> -->
					<hr class="border-dashed mt-0">
					<div class="pt-1">
						<div>
							<div class="row">
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">JOR No: </span>
									<span class="text-sm text-gray-700">{{ head.jor_no }}</span>
								</div>
								<div class="col-lg-3">
									<span class="text-sm text-gray-700 font-bold pr-1">AOQ No: </span>
									<span class="text-sm text-gray-700">{{ head.aoq_no }}</span>
								</div>
								<div class="col-lg-3">
									<span class="text-sm text-gray-700 font-bold pr-1">Requested By: </span>
									<span class="text-sm text-gray-700">{{ head.requestor }}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<span class="text-sm text-gray-700 font-bold pr-1">Department: </span>
									<span class="text-sm text-gray-700">{{ head.department }}</span>
								</div>
								<div class="col-lg-3">
									<span class="text-sm text-gray-700 font-bold pr-1">Date: </span>
									<span class="text-sm text-gray-700">{{ head.aoq_date }}</span>
								</div>
								<div class="col-lg-3">
									<span class="text-sm text-gray-700 font-bold pr-1">Date Needed: </span>
									<span class="text-sm text-gray-700">{{ head.date_needed }}</span>
								</div>
							</div>
							<!-- <div class="row">
								<div class="col-lg-12">
									<span class="text-sm text-gray-700 font-bold pr-1">End-Use:</span>
									<span class="text-sm text-gray-700">February 16, 2024</span>
								</div>
							</div> -->
							<div class="row">
								<div class="col-lg-12">
									<span class="text-sm text-gray-700 font-bold pr-1">Purpose: </span>
									<span class="text-sm text-gray-700">{{ head.purpose }}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<span class="text-sm text-gray-700 font-bold pr-1">Project Title: </span>
									<span class="text-sm text-gray-700">{{ head.project_activity }}</span>
								</div>
							</div>

							<div class="second_one">
								<br>
								<div class="row">
									<div class="col-lg-12">
										<table class="table-bordered w-full !text-xs">
											<tr>
												<td class="bg-gray-50 " colspan="4"></td>
												<!-- loop vendors here start -->
												<td class="bg-gray-50 p-1 text-center py-2" colspan="5">
													<p class="m-0 text-xs font-bold">{{ vendordets.vendor_name }} ({{ vendordets.vendor_identifier }})</p>
													<!-- <p class="m-0 text-xs font-bold">MF Computer Solutions, Inc.</p>
													<p class="m-0 text-xs font-bold">Nexus Industrial Prime Solutions Corp.</p> -->
													<div class="flex justify-center space-x-2">
														<span>{{ vendordets.contact_person }}</span>
														<span>|</span>
														<span>{{ vendordets.phone }}</span>
													</div>
												</td>
												<!-- loop vendors here end-->
											</tr>
											<tr>
												<td class="uppercase bg-gray-100 p-1 align-top text-center" width="3%">#</td>
												<td class="uppercase bg-gray-100 p-1 align-top" width="30%">Description</td>
												<td class="uppercase bg-gray-100 p-1 align-top text-center" width="5%">Qty</td>
												<td class="uppercase bg-gray-100 p-1 align-top text-center" width="5%">UOM</td>
												<!-- loop offer header per vendor here start -->
												<td class="uppercase bg-gray-100 p-1 " >Offer</td>
												<td class="uppercase bg-gray-100 p-1 text-center" >Unit Price</td>
												<td class="uppercase bg-gray-100 p-1 text-center" colspan="2">Amount</td>
												<td class="uppercase bg-gray-100 p-1 text-center" >Comment</td>
												<!-- loop offer header per vendor here end-->
											</tr>
											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
											<tr>
												<td class="bg-gray-50 p-1 uppercase" colspan="19">
													{{ head.general_description }}
												</td>
												<!-- loop vendors here end-->
											</tr>
											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
											<template v-for="(al, laborno) in aoq_labor">
												<tr>
													<td class="p-1 align-top text-center" rowspan="4">{{ laborno+1 }}</td>
													<td class="p-1 align-top" rowspan="4">{{ al.scope_of_work }}</td>
													<td class="p-1 align-top text-center" rowspan="4">{{ parseFloat(al.quantity).toFixed(2) }}</td>
													<td class="p-1 align-top text-center" rowspan="4">{{ al.uom }}</td>
												</tr>
												<!-- loop here if 3 and below offers here -->
												 <template v-for="(alo, l) in aoq_labor_offers">
													<tr v-if="(alo.jo_rfq_labor_details_id == al.jo_rfq_labor_details_id)">
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%">{{ alo.offer }}</td>
															<td width="11%" :class="(alo.min_price == alo.unit_price && head.status != 'Cancelled') ? 'p-1 align-top bg-yellow-300' : 'p-1 align-top '">
																<div class="flex justify-between space-x-1">
																	<span>{{ (alo.unit_price != 0) ? alo.labor_currency : '' }}</span>
																	<span>{{  (alo.unit_price != 0) ? parseFloat(alo.unit_price).toFixed(2) : '' }}</span>
																</div>
															</td>
															<td width="11%" :class="(alo.awarded == 1 && head.status != 'Cancelled') ? 'p-1 align-top bg-lime-500' : 'p-1 align-top'">
																<div class="flex justify-between space-x-1">
																	<span>{{ (alo.unit_price != 0) ? alo.labor_currency : '' }}</span>
																	<span>{{  (alo.unit_price != 0) ? parseFloat(alo.unit_price * al.quantity).toFixed(2) : '' }}</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%"  v-if="(head.status != 'Awarded' && alo.unit_price != 0)">
																<input type="radio" :name="'laborawarded'+ laborno" :id="'laborawarded_'+ l" v-model = "alo.awarded" value="1" @click="UpdateLaborOffersAwarded(l,alo.jo_rfq_labor_offer_id,al.jor_labor_details_id,latest_jo_aoq_details_id)">
															</td>
															<td class="p-1 align-top text-center" width="3%" v-else>
															</td>
															
															<td class="p-1 align-top" width="10%" v-if="(head.status != 'Awarded')">
																<textarea placeholder="Comments" class="w-full" :id="'laborcomments_'+ l" v-model = "alo.comments" @blur="UpdateLaborOffersComments(l,alo.jo_rfq_labor_offer_id,latest_jo_aoq_details_id)"></textarea>
															</td>
															<td class="p-1 align-top" width="10%" v-else>
																<textarea placeholder="Comments" class="w-full" :id="'laborcomments_'+ l" v-model = "alo.comments" readonly></textarea>
															</td>
														<!-- loop offers per vendor here -->
													</tr>
												</template>
											</template>
												<!-- loop here if 3 and below offers here -->
												<tr>
													<td class="bg-gray-50 p-1 uppercase" colspan="19">
														Materials
													</td>
												</tr>
											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
											<template v-for="(am, materialno) in aoq_material">
												<tr>
													<td class="p-1 align-top text-center" rowspan="4">{{ materialno + 1 }}</td>
													<td class="p-1 align-top" rowspan="4">{{ am.item_description }}</td>
													<td class="p-1 align-top text-center" rowspan="4">{{ parseFloat(am.quantity).toFixed(2) }}</td>
													<td class="p-1 align-top te</template>xt-center" rowspan="4">{{ am.uom }}</td>
												</tr>
												<!-- loop here if 3 and below offers here -->
													<template v-for="(amo, m) in aoq_material_offers">
														<tr v-if="(amo.jo_rfq_material_details_id == am.jo_rfq_material_details_id)">
															<!-- loop offers per vendor here -->
																<td class="p-1" width="30%">{{ amo.offer }}</td>
																<td width="11%" :class="(amo.min_price == amo.unit_price && head.status != 'Cancelled') ? 'p-1 align-top bg-yellow-300' : 'p-1 align-top '">
																	<div class="flex justify-between space-x-1">
																	<span>{{ (amo.unit_price != 0) ? amo.material_currency : '' }}</span>
																	<span>{{  (amo.unit_price != 0) ? parseFloat(amo.unit_price).toFixed(2)  : '' }}</span>
																	</div>
																</td>
																<td width="11%" :class="(amo.awarded == 1 && head.status != 'Cancelled') ? 'p-1 align-top bg-lime-500' : 'p-1 align-top'">
																	<div class="flex justify-between space-x-1">
																	<span>{{ (amo.unit_price != 0) ? amo.material_currency : '' }}</span>
																	<span>{{  (amo.unit_price != 0) ? parseFloat(amo.unit_price * am.quantity).toFixed(2)  : '' }}</span>
																	</div>
																</td>
																<td class="p-1 align-top text-center" width="3%"  v-if="(head.status != 'Awarded' && amo.unit_price != 0)">
																	<input type="radio" :name="'materialawarded'+ materialno" :id="'materialawarded_'+ m" v-model = "amo.awarded" value="1" @click="UpdateMaterialOffersAwarded(m,amo.jo_rfq_material_offer_id,am.jor_material_details_id,latest_jo_aoq_details_id)">
																</td>
																<td class="p-1 align-top text-center" width="3%" v-else>
																	<!-- <input type="radio" :name="'awarded'+ itemno" :id="'awarded_'+ i" v-model = "ao.awarded" value="1" disbaled> -->
																</td>
																
																<td class="p-1 align-top" width="10%" v-if="(head.status != 'Awarded')">
																	<textarea placeholder="Comments" class="w-full" :id="'materialcomments_'+ m" v-model = "amo.comments" @blur="UpdateMaterialOffersComments(m,amo.jo_rfq_material_offer_id,latest_jo_aoq_details_id)"></textarea>
																</td>
																<td class="p-1 align-top" width="10%" v-else>
																	<textarea placeholder="Comments" class="w-full" :id="'materialcomments_'+ m" v-model = "amo.comments" readonly></textarea>
																</td>
															<!-- loop offers per vendor here -->
														</tr>
													</template>
												</template>
												<!-- loop here if 3 and below offers here -->
											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->

											<tr class="!border-0">
												<td class="!border-0" colspan="4"><br></td>
												<td class="!border-0" colspan="4"><br></td>
											</tr>
											<span hidden>{{ termno=0 }}</span>
											<tr class="!border-0" v-for="vt in vendor_terms">
												<template  v-if="(vt.terms_id != null)">
												<td class="!border-0 text-center">{{ letters[termno] }}.</td>
												<td class="!border-0 text-center" colspan="1"></td>
												<td class="!border-0" colspan="2"></td>	
												<td class="!border-0 !border-b" colspan="1">{{ vt.terms }}</td>
												<td class="!border-0" colspan="4"></td>
												<span hidden>{{ termno++ }}</span> 
												</template>
											</tr>
											
											<tr class="!border-0">
												<td class="!border-0" colspan="4"><br></td>
												<td class="!border-0" colspan="4"><br></td>
											</tr>
										</table>
										<br>
										<!-- <table class="!text-xs" width="100%">
											<tr>
												<td width="19%" class="text-center">Prepared by:</td>
												<td></td>
												<td width="19%" class="text-center">Received and Checked by</td>
												<td></td>
												<td width="19%" class="text-center">Award Recommended by</td>
												<td></td>
												<td width="19%" class="text-center">Recommending Approval</td>
												<td></td>
												<td width="19%" class="text-center">Aprroved by</td>
											</tr>
											<tr>
												<td class=""><br></td>
												<td></td>
												<td class=""></td>
												<td></td>
												<td class=""></td>
												<td></td>
												<td class=""></td>
												<td></td>
												<td class=""></td>
											</tr>
											<tr>
												<td class="border-b text-center pb-1">
													Employee Name
												</td>
												<td></td>
												<td class="border-b text-center pb-1">
													Employee Name
												</td>
												<td></td>
												<td class="border-b text-center pb-1">
													Employee Name
												</td>
												<td></td>
												<td class="border-b text-center pb-1">
													Employee Name
												</td>
												<td></td>
												<td class="border-b text-center pb-1">
													Employee Name
												</td>
											</tr>
											
										</table> -->
										<br>
									</div>
								</div>
								<br>
								<div class="row my-2" v-if="(count_canvassed_aoq_v == count_aoq_vendors)"> 
									<div class="col-lg-12 col-md-12">
										<div class="flex justify-between space-x-2">
											<div class="flex justify-between space-x-1">
												<button type="submit" class="btn btn-danger mr-2 w-36" @click="CancelAlert()" v-if="(head.status != 'Awarded')">Cancel</button>
												<button type="submit" @click="openPreview()" class="btn btn-info w-26">Preview</button>
												<button type="submit" @click="openAOQ(head.jo_rfq_head_id)" class="btn btn-warning ">Open AOQ</button>
												<!-- <button type="submit" @click="openAddVendor()" class="btn btn-info w-26">Add Vendor</button> -->
											</div>
											<div class="flex justify-between space-x-1" v-if="(head.status != 'Awarded')">
												<button type="submit" class="btn btn-warning w-26 !text-white" @click="openDraftAlert()">Save as Draft</button>
												<button @click="getAOQDoneTEDetails(previous.id)" type="submit" class="btn btn-primary w-26" title="Previous Vendor" v-if="(latest_jo_aoq_details_id != props.jo_aoq_details_id)">Back</button>
												<button v-if="(max_id == latest_jo_aoq_details_id) && (vendordets.count_labor_awarded == 0 && vendordets.count_material_awarded == 0)" type="submit" id="savejoaoqbtn" @click="openSaveAlert()" class="btn btn-primary w-26" style="pointer-events: none;">Save AOQ</button> 
												<button v-if="(max_id == latest_jo_aoq_details_id) && (vendordets.count_labor_awarded != 0 || vendordets.count_material_awarded != 0)" type="submit" id="savejoaoqbtn" @click="openSaveAlert()" class="btn btn-primary w-26">Save AOQ</button> 
												<button v-if="(max_id != latest_jo_aoq_details_id)" @click="getAOQDoneTEDetails(next.id)" type="submit" class="btn btn-primary w-26" title="Next Vendor">Next</button>
											</div>
											<div class="flex justify-between space-x-1" v-else>
												<button @click="getAOQDoneTEDetails(previous.id)" type="submit" class="btn btn-primary w-26" title="Previous Vendor" v-if="(latest_jo_aoq_details_id != props.jo_aoq_details_id)">Back</button>
												<button v-else @click="getAOQDoneTEDetails(next.id)" type="submit" class="btn btn-primary w-26" title="Next Vendor">Next</button>
											</div>
										</div>
									</div>
								</div>
								<div class="row" v-else>
									<div class="col-lg-12">
										<div class="flex justify-center space-x-1">
											<button type="submit" @click="openAOQ(head.jo_rfq_head_id)" class="btn btn-warning ">Update Vendor/s</button>
										</div>
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
			<div class="modal pt-4 px-3" :class="{ show:showAddVendor }">
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
									<select type="text" class="form-control" placeholder="Vendor" value="">
										<option value="">Select Vendor</option>
										<option value="">Tough Performance AutoWorkz</option>
										<option value="">2GO Express, Inc.</option>
										<option value="">4 Sisters Sack's Trading</option>
										<option value="">A-1 Gas Corporation</option>
									</select>
								</div>
								<table class="table-bordered w-full !text-xs mb-2">
									<tr class="bg-gray-100">
										<td class="p-1" width="50%">Scope of Work</td>
										<td class="p-1" width="35%">Offer</td>
										<td class="p-1">Price</td>
									</tr>
									<tr>
										<td class="p-1 align-top">
											Supply of manpower/labor, tools, equipment and technical expertise for the following:
											<br>1. 1. Standard governor overhauling/dismantling, cleaning and replacement of parts as seen necessary (i.e. gaskets, bearings, o-rings, etc.)
											<br>2. Inspection and checking of all parts for wear, cracks, corrosion and other damages.
											<br>3. Repair and replacement of parts as seen upon inspection.
											<br>4. Setting of internal parts and mounting of the governor.
											<br>5. Calibration and bench testing for:
											<br>5.1. Speed Setting and Indicator
											<br>5.2. Speed Droop Setting and Indicator
											<br>5.3. Load Limit Setting and Indicator
											<br>6. Functional test of shut-down solenoid valve
											<br>7. Testing and Commissioning
											<br>8. Submission of inspection, service, commissioning and bench testing reports.
											<br>9. Other works necessary for job completion.
										</td>
										<td class="align-top">
											<textarea name="" id="" class="resize w-full  h-screen !max-h-[300px] !min-h-[100] p-1"></textarea>
										</td>
										<td class="align-top">
											<div class="!h-14 border-b">
												<input type="text" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
												<select name="" id="" class=" p-1 w-full !align-top text-center">
													<option value="">PHP</option>
												</select>
											</div>
										</td>
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
									<tr>
										<td class="p-1 align-top text-center">1</td>
										<td class="p-1 align-top text-center">5</td>
										<td class="p-1 align-top">Monitor</td>
										<td class="align-top">
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top"></textarea>
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top"></textarea>
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top"></textarea>
										</td>
										<td class="align-top">
											<div class="!h-14 border-b">
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
											</div>
											<div class="!h-14 border-b">
												<input type="text" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
												<select name="" id="" class=" p-1 w-full !align-top text-center">
													<option value="">PHP</option>
												</select>
											</div>
										</td>
									</tr>
									<tr>
										<td class="p-1 align-top text-center">1</td>
										<td class="p-1 align-top text-center">5</td>
										<td class="p-1 align-top">Mouse</td>
										<td class="align-top">
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top"></textarea>
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top"></textarea>
											<textarea type="text" class="border-b p-1 w-full h-14 !align-top"></textarea>
										</td>
										<td class="align-top">
											<div class="!h-14 border-b">
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
											</div>
											<div class="!h-14 border-b">
												<input type="text" class="border-b p-1 w-full !align-top text-center" placeholder="00.00">
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
									<a href="/job_aoq/view" class="btn btn-primary mr-2 w-44">Save</a>
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
			<div class="modal pt-4 px-3" :class="{ show:showPreview }">
			<div @click="closeModal" class="w-full h-full fixed"></div>
				<div class="modal__content w-11/12 mb-5">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">Preview AOQ</span>
							<a href="#" class="text-gray-600" @click="closeModal">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<div class="bg-white p-4 ">
			<div class="overflow-x-scroll">
				<div class="">
					<table class="w-full !text-xs mb-3 ">
						<tr>
							<td class="font-bold pr-1" width="8%">JOR No: </td>
							<td class="">{{ previewhead.jor_no }}</td>
							<td class=" font-bold pr-1" width="8%">AOQ No: </td>
							<td class="">{{ previewhead.aoq_no }}</td>
							<td class=" font-bold pr-1" width="8%">Requested By: </td>
							<td class="">{{ previewhead.requestor }}</td>
						</tr>
						<tr>
							<td class="font-bold pr-1">Department: </td>
							<td class="">{{ previewhead.department }}</td>
							<td class=" font-bold pr-1">Date: </td>
							<td class="">{{ previewhead.aoq_date }}</td>
							<td class=" font-bold pr-1">Date Needed: </td>
							<td class="">{{ previewhead.date_needed }}</td>
						</tr>
						<tr>
							<td class="font-bold pr-1">Purpose:</td>
							<td class="">{{ previewhead.purpose }}</td>
						</tr>
						<tr>
							<td class="font-bold pr-1">Project Title:</td>
							<td class="">{{ previewhead.project_activity }}</td>
						</tr>
					</table>
					<table class="table-bordered !text-xs mb-3" width="150%">
						<tr>
							<td class="bg-gray-50 " colspan="4"></td>
							<!-- loop vendors here start -->
							<template v-for="av in aoq_vendor">
								<td class="bg-gray-50 p-1 text-center py-2" colspan="5">
								<p class="m-0 text-xs font-bold">{{ av.vendor_name }}</p>
								<div class="flex justify-center space-x-2">
									<span>{{ av.contact_person }}</span>
									<span>|</span>
									<span>{{ av.phone }}</span>
								</div>
							</td>
							</template>
							<!-- loop vendors here end-->
						</tr>
						
						<tr>
							<td class="uppercase bg-gray-100 p-1 align-top text-center" width="1%">#</td>
							<td class="uppercase bg-gray-100 p-1 align-top" width="10%">Description</td>
							<td class="uppercase bg-gray-100 p-1 align-top text-center" width="2%">Qty</td>
							<td class="uppercase bg-gray-100 p-1 align-top text-center" width="2%">UOM</td>
							<!-- loop offer header per vendor here start -->
							<template v-for="av in aoq_vendor">
								<td class="uppercase bg-gray-100 p-1 " width="10%">Offer</td>
								<td class="uppercase bg-gray-100 p-1 text-center"  width="3%">Unit Price</td>
								<td class="uppercase bg-gray-100 p-1 text-center" colspan="2" width="3%">Amount</td>
								<td class="uppercase bg-gray-100 p-1 text-center" width="5%">Comment</td>
							</template>
							<!-- loop offer header per vendor here end-->
						</tr>
						<tr>
							<td class="bg-gray-50 p-1 uppercase" colspan="19">
								{{  previewhead.general_description }}
							</td>
							<!-- loop vendors here end-->
						</tr>
						<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
						<template v-for="(ld, l_index) in preview_labor_data">
							<tr>
								<td class="p-1 align-top text-center" rowspan="4">{{ l_index+1 }}</td>
								<td class="p-1 align-top" rowspan="4">{{ ld.scope_of_work }}</td>
								<td class="p-1 align-top text-center" rowspan="4">{{ parseFloat(ld.quantity).toFixed(2) }}</td>
								<td class="p-1 align-top text-center" rowspan="4">{{ ld.uom }}</td>
							</tr>
							<!-- loop here if 3 and below offers here -->
							<tr>
									<!-- loop offers per vendor here -->
									<template v-for="flo in preview_first_labor_offers">
										<template v-if="flo.jor_labor_details_id == ld.jor_labor_details_id">
										<td class="p-1">{{ flo.offer }}</td>
										<td :class="(ld.min_price == flo.unit_price && head.status != 'Cancelled') ? 'p-1 align-top bg-yellow-300' : 'p-1 align-top '">
											<div class="flex justify-between space-x-1">
												<span>{{ (flo.unit_price != 0) ? flo.currency : '' }}</span>
												<span>{{  (flo.unit_price != 0) ? parseFloat(flo.unit_price).toFixed(2) : ''  }}</span>
											</div>
										</td>
										<td colspan="2" :class="(flo.awarded == 1 && head.status != 'Cancelled') ? 'p-1 align-top bg-lime-500' : 'p-1 align-top '">
											<div class="flex justify-between space-x-1">
												<span>{{ (flo.unit_price != 0) ? flo.currency : '' }}</span>
												<span>{{  (flo.unit_price != 0) ? parseFloat(flo.unit_price * ld.quantity).toFixed(2) : '' }}</span>
											</div>
										</td>
										<td class="p-1 align-top">{{ flo.remarks }}</td>
										</template>
									</template>
									<!-- loop offers per vendor here -->
								</tr>
								<tr>
									<!-- loop offers per vendor here -->
									<template v-for="slo in preview_second_labor_offers">
										<template v-if="slo.jor_labor_details_id == ld.jor_labor_details_id">
										<td class="p-1">{{ slo.offer }}</td>
										<td :class="(ld.min_price == slo.unit_price && head.status != 'Cancelled') ? 'p-1 align-top bg-yellow-300' : 'p-1 align-top '">
											<div class="flex justify-between space-x-1">
												<span>{{ (slo.unit_price != 0) ? slo.currency : '' }}</span>
												<span>{{  (slo.unit_price != 0) ? parseFloat(slo.unit_price).toFixed(2) : ''  }}</span>
											</div>
										</td>
										<td colspan="2" :class="(slo.awarded == 1 && head.status != 'Cancelled') ? 'p-1 align-top bg-lime-500' : 'p-1 align-top '">
											<div class="flex justify-between space-x-1">
												<span>{{ (slo.unit_price != 0) ? slo.currency : '' }}</span>
												<span>{{  (slo.unit_price != 0) ? parseFloat(slo.unit_price * ld.quantity).toFixed(2) : '' }}</span>
											</div>
										</td>
										<td class="p-1 align-top">{{ slo.remarks }}</td>
										</template>
									</template>
									<!-- loop offers per vendor here -->
								</tr>
								<tr>
									<!-- loop offers per vendor here -->
									<template v-for="tlo in preview_third_labor_offers">
										<template v-if="tlo.jor_labor_details_id == ld.jor_labor_details_id">
										<td class="p-1">{{ tlo.offer }}</td>
										<td :class="(ld.min_price == tlo.unit_price && head.status != 'Cancelled') ? 'p-1 align-top bg-yellow-300' : 'p-1 align-top '">
											<div class="flex justify-between space-x-1">
												<span>{{ (tlo.unit_price != 0) ? tlo.currency : '' }}</span>
												<span>{{  (tlo.unit_price != 0) ? parseFloat(tlo.unit_price).toFixed(2) : ''  }}</span>
											</div>
										</td>
										<td colspan="2" :class="(tlo.awarded == 1 && head.status != 'Cancelled') ? 'p-1 align-top bg-lime-500' : 'p-1 align-top '">
											<div class="flex justify-between space-x-1">
												<span>{{ (tlo.unit_price != 0) ? tlo.currency : '' }}</span>
												<span>{{  (tlo.unit_price != 0) ? parseFloat(tlo.unit_price * ld.quantity).toFixed(2) : '' }}</span>
											</div>
										</td>
										<td class="p-1 align-top">{{ tlo.remarks }}</td>
										</template>
									</template>
									<!-- loop offers per vendor here -->
								</tr>
							</template>
							<!-- loop here if 3 and below offers here -->
						<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
						<tr>
							<td class="bg-gray-50 p-1 uppercase" colspan="19">
								Materials
							</td>
						</tr>
						<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
						<template v-for="(md, m_index) in preview_material_data">
							<tr>
								<td class="p-1 align-top text-center" rowspan="4">{{ m_index+1 }}</td>
								<td class="p-1 align-top" rowspan="4">{{ md.item_description }}</td>
								<td class="p-1 align-top text-center" rowspan="4">{{  parseFloat(md.quantity).toFixed(2) }}</td>
								<td class="p-1 align-top text-center" rowspan="4">{{ md.uom }}</td>
							</tr>
							<!-- loop here if 3 and below offers here -->
								<tr>
									<!-- loop offers per vendor here -->
									<template v-for="fo in first_offers">
										<template v-if="md.jor_material_details_id == fo.jor_material_details_id">
											<td class="p-1">
												{{ fo.offer }}
											</td>
											<td :class="(md.min_price == fo.unit_price && head.status != 'Cancelled') ? 'p-1 align-top bg-yellow-300' : 'p-1 align-top '">
												<div class="flex justify-between space-x-1">
													<span>{{ (fo.unit_price != 0) ? fo.currency : '' }}</span>
													<span>{{  (fo.unit_price != 0) ? parseFloat(fo.unit_price).toFixed(2) : ''  }}</span>
												</div>
											</td>
											<td colspan="2" :class="(fo.awarded == 1 && head.status != 'Cancelled') ? 'p-1 align-top bg-lime-500' : 'p-1 align-top '">
												<div class="flex justify-between space-x-1">
													<span>{{ (fo.unit_price != 0) ? fo.currency : '' }}</span>
													<span>{{  (fo.unit_price != 0) ? parseFloat(fo.unit_price * md.quantity).toFixed(2) : ''  }}</span>
												</div>
											</td>
											<td class="p-1 align-top">{{ fo.remarks }}</td>
										</template>
									</template>
									<!-- loop offers per vendor here -->
								</tr>
								<tr>
									<!-- loop offers per vendor here -->
									<template v-for="so in second_offers">
										<template v-if="md.jor_material_details_id == so.jor_material_details_id">
											<td class="p-1">
												{{ so.offer }}
											</td>
											<td :class="(md.min_price == so.unit_price && head.status != 'Cancelled') ? 'p-1 align-top bg-yellow-300' : 'p-1 align-top '">
												<div class="flex justify-between space-x-1">
													<span>{{ (so.unit_price != 0) ? so.currency : '' }}</span>
													<span>{{  (so.unit_price != 0) ? parseFloat(so.unit_price).toFixed(2) : ''  }}</span>
												</div>
											</td>
											<td :class="(so.awarded == 1 && head.status != 'Cancelled') ? 'p-1 align-top bg-lime-500' : 'p-1 align-top '" colspan="2">
												<div class="flex justify-between space-x-1">
													<span>{{ (so.unit_price != 0) ? so.currency : '' }}</span>
													<span>{{  (so.unit_price != 0) ? parseFloat(so.unit_price * md.quantity).toFixed(2) : ''  }}</span>
												</div>
											</td>
											<td class="p-1 align-top">{{ so.remarks }}</td>
										</template>
									</template>
									<!-- loop offers per vendor here -->
								</tr>
								<tr>
									<!-- loop offers per vendor here -->
									<template v-for="to in third_offers">
										<template v-if="md.jor_material_details_id == to.jor_material_details_id">
											<td class="p-1">
												{{ to.offer }}
											</td>
											<td :class="(md.min_price == to.unit_price && head.status != 'Cancelled') ? 'p-1 align-top bg-yellow-300' : 'p-1 align-top'">
												<div class="flex justify-between space-x-1">
													<span>{{ (to.unit_price != 0) ? to.currency : '' }}</span>
												<span>{{  (to.unit_price != 0) ? parseFloat(to.unit_price).toFixed(2) : ''  }}</span>
												</div>
											</td>
											<!-- <td class="p-1 align-top" colspan="2"> -->
											<td :class="(to.awarded == 1 && head.status != 'Cancelled') ? 'p-1 align-top bg-lime-500' : 'p-1 align-top '" colspan="2">
												<div class="flex justify-between space-x-1">
													<span>{{ (to.unit_price != 0) ? to.currency : '' }}</span>
													<span>{{  (to.unit_price != 0) ? parseFloat(to.unit_price * md.quantity).toFixed(2) : ''  }}</span>
												</div>
											</td>
											<td class="p-1 align-top">{{ to.remarks }}</td>
										</template>
									</template>
									<!-- loop offers per vendor here -->
								</tr>
							</template>
							<!-- loop here if 3 and below offers here -->
								<tr class="!border-0">
									<td class="!border-0" colspan="4"><br></td>
									<td class="!border-0" colspan="4"><br></td>
								</tr>
						<tr>
							<td colspan="4" class="p-0 !border-0">
								<table class="w-full">
									<tr>
										<td class="!border-0 text-right px-2" colspan="2" width="40%">Legend:</td>
										<td class="!border-0"></td>
										<td class="!border-0" width="10%"></td>
									</tr>
									<tr class="!border-0">
										<td class="!border-0 text-right px-2" colspan="2">Recommended Award</td>
										<td class="!border-0 bg-lime-500" width="20%"></td>
										<td class="!border-0"></td>
									</tr>
									<tr class="!border-0">
										<td class="!border-0 text-right px-2" colspan="2">Lowest Price</td>
										<td class="!border-0 bg-yellow-300"></td>
										<td class="!border-0"></td>
									</tr>
								</table>
							</td>
							<td colspan="5" class="p-0 !border-0" v-for="av in aoq_vendor">
								<table class="w-full">
									<tr>
										<td class="!border-0 text-start font-bold">Terms and Conditions</td>
									</tr>
									<span hidden>{{ termno=0 }}</span>
									<template v-for="at in all_terms">
									<tr v-if="av.jo_rfq_vendor_id == at.jo_rfq_vendor_id">
										<td class="!border-0 text-start" >{{ letters[termno] }}. {{ at.terms }}</td>
										<span hidden>{{ termno++ }}</span> 
									</tr>
									</template>
								</table>
							</td>
						</tr>
							<tr>
								<td colspan="19" class="border-0"><br></td>
							</tr>
					</table>
					<table class="!text-xs" width="150%">
						<tr>
							<td></td>
							<td width="12%">Prepared by:</td>
							<td></td>
							<td width="12%">Received and Checked by</td>
							<td></td>
							<td width="12%">Award Recommended by</td>
							<td></td>
							<td width="12%">Recommending Approval</td>
							<td></td>
							<td width="12%">Aprroved by</td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td class=""><br></td>
							<td></td>
							<td class=""></td>
							<td></td>
							<td class=""></td>
							<td></td>
							<td class=""></td>
							<td></td>
							<td class=""></td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td class="border-b">
								{{ previewhead.prepared_by_name }}
							</td>
							<td></td>
							<td class="border-b">
								{{ previewhead.received_by_name }}
							</td>
							<td></td>
							<td class="border-b">
								{{ previewhead.award_recommended_by_name }}
							</td>
							<td></td>
							<td class="border-b">
								{{ previewhead.recommended_by_name }}
							</td>
							<td></td>
							<td class="border-b">
								{{ previewhead.approved_by_name }}
							</td>
							<td></td>
						</tr>
					</table>
					<br>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-lg-12">
					<div class="flex justify-center space-x-1">
						<a :href="'/export-jo-aoq/'+previewhead.jo_aoq_head_id" class="btn btn-primary mr-2 w-44">Export</a>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:saveAlert }">
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
									<h5 class="leading-tight">Are you sure you want to save this AOQ?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full" @click="closeModal()">No</button>
									<button class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full" @click="SaveAOQ()">Yes</button>
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
									<h5 class="leading-tight">You have successfully created an AOQ.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <a href="/job_aoq/new" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Create New</a> -->
									<a href="/job_po/new" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full">Proceed to PO</a>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:draftAlert }">
				<div @click="closeAlert()" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h5 class="leading-tight">You have successfully saved an AOQ as draft.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button @click="closeAlert()" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close</button>
									<!-- <a href="/pur_quote/new" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full">Proceed</a> -->
									<a href="/job_aoq/new" class="btn !text-white !bg-yellow-400 btn-sm !rounded-full w-full">Create New</a>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:cancelAlert }">
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
									<h5 class="leading-tight">Are you sure you want to cancel this AOQ?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn btn-gray btn-sm !rounded-full w-full"  @click="closeModal()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full"  @click="CancelTransaction()">Yes</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:openAOQAlert }">
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
									<h5 class="leading-tight">This is an open AOQ please update vendor/s to proceed.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn btn-danger btn-sm !rounded-full w-full"  @click="openAOQ(head.jo_rfq_head_id)">Update Vendor/s</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
</template>