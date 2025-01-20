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
	let aoq_items=ref([]);
	let aoq_offers=ref([]);
	let letters=ref([]);
	let vendor_terms=ref([]);
	let previous=ref([]);
	let next=ref([]);
	let max_id=ref('');
	let latest_aoq_details_id=ref('');
	let count_awarded=ref(0);
	let count_canvassed_aoq_v=ref(0);
	let count_aoq_vendors=ref(0);

	let previewhead=ref([]);
	let aoq_vendor=ref([]);
	let preview_aoq_items=ref([]);
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
		aoq_details_id:{
            type:String,
            default:''
        },
    })

	onMounted(async () => {
		getAOQDoneTEDetails()
		getAOQPreviewDetails()
		ReloadAwarded()
	})

	const getAOQDoneTEDetails = async (aoq_details_id) => {
		if(aoq_details_id != undefined){
			let response = await axios.get(`/api/aoq_donete_details/${props.id}/${aoq_details_id}`)
			head.value = response.data.aoq_head_data
			vendordets.value = response.data.aoq_vendor_data
			aoq_items.value = response.data.aoq_items_data
			aoq_offers.value = response.data.aoq_offers_data
			letters.value=response.data.letters
			vendor_terms.value = response.data.vendor_terms
			max_id.value = response.data.max_id
			previous.value = response.data.previous
			next.value = response.data.next
			latest_aoq_details_id.value = aoq_details_id
			count_awarded.value = response.data.count_awarded
			count_canvassed_aoq_v.value = response.data.count_canvassed_aoq_v
			count_aoq_vendors.value = response.data.count_aoq_vendors
		}else{
			let response = await axios.get(`/api/aoq_donete_details/${props.id}/${props.aoq_details_id}`)
			head.value = response.data.aoq_head_data
			vendordets.value = response.data.aoq_vendor_data
			aoq_items.value = response.data.aoq_items_data
			aoq_offers.value = response.data.aoq_offers_data
			letters.value=response.data.letters
			vendor_terms.value = response.data.vendor_terms
			max_id.value = response.data.max_id
			previous.value = response.data.previous
			next.value = response.data.next
			latest_aoq_details_id.value = props.aoq_details_id
			count_awarded.value = response.data.count_awarded
			count_canvassed_aoq_v.value = response.data.count_canvassed_aoq_v
			count_aoq_vendors.value = response.data.count_aoq_vendors
		}

		if(count_aoq_vendors.value != count_canvassed_aoq_v.value ){
			openAOQAlert.value = !openAOQAlert.value
		}else{
			openAOQAlert.value = !hideModal.value
		}

	}

	const ReloadAwarded = async (aoq_details_id) => {
		if(aoq_details_id != undefined){
			var details_id = aoq_details_id
		}else{
			var details_id = props.aoq_details_id
		}
		let response = await axios.get(`/api/aoq_donete_details/${props.id}/${details_id}`)
		aoq_items.value = response.data.aoq_items_data
		vendordets.value = response.data.aoq_vendor_data
		aoq_offers.value = response.data.aoq_offers_data
		letters.value=response.data.letters
		vendor_terms.value = response.data.vendor_terms
		count_awarded.value = response.data.count_awarded
		latest_aoq_details_id.value = details_id
		max_id.value = response.data.max_id
		previous.value = response.data.previous
		next.value = response.data.next

		// if(count_awarded.value != 0){
		// 	document.getElementById("saveaoqbtn").disabled = false;
		// }else{
		// 	document.getElementById("saveaoqbtn").disabled = true;
		// }
	}

	const getAOQPreviewDetails = async () => {
		let response = await axios.get(`/api/aoq_head_details/${props.id}`)
		previewhead.value = response.data.aoq_head_data
		aoq_vendor.value = response.data.aoq_vendor_data
		preview_aoq_items.value = response.data.aoq_items_data
		first_offers.value = response.data.first_offers
		second_offers.value = response.data.second_offers
		third_offers.value = response.data.third_offers
		preview_vendor_terms.value = response.data.vendor_terms
		all_terms.value = response.data.all_terms
	}

	// const getUpdatedOffers = async (aoqdetailsid) => {
	// 	let response = await axios.get(`/api/aoq_donete_details/${props.id}/${aoqdetailsid}`)
	// 	aoq_offers.value = response.data.aoq_offers_data
	// 	max_id.value = response.data.max_id
	// 	previous.value = response.data.previous
	// 	next.value = response.data.next
	// 	latest_aoq_details_id.value = aoqdetailsid
	// }


	const UpdateOffersAwarded= (loop, rfq_offer_id, pr_details_id,latest_aoq_details_id) => {
		const award = document.getElementById("awarded_"+loop).value;
		const comments = document.getElementById("comments_"+loop).value;
			const formOffers= new FormData()
			formOffers.append('rfq_head_id', head.value.rfq_head_id)
			formOffers.append('rfq_offer_id', rfq_offer_id)
			formOffers.append('pr_details_id', pr_details_id)
			formOffers.append('awarded', award ?? 0)
			formOffers.append('comments', comments)
			// axios.post("/api/update_offers_awarded/", formOffers)
			// ReloadAwarded(latest_aoq_details_id)
			axios.post("/api/update_offers_awarded/", formOffers).then(function (response) {
				ReloadAwarded(latest_aoq_details_id)
				getAOQPreviewDetails()
			});
	}

	const UpdateOffersComments= (loop, rfq_offer_id, latest_aoq_details_id) => {
		const comments = document.getElementById("comments_"+loop).value;
			const formOffers= new FormData()
			formOffers.append('rfq_offer_id', rfq_offer_id)
			formOffers.append('comments', comments)
			axios.post("/api/update_offers_comments/", formOffers)
			ReloadAwarded(latest_aoq_details_id)
			getAOQPreviewDetails()
			// axios.post("/api/update_offers_comments/", formOffers).then(function (response) {
			// 	getUpdatedOffers(latest_aoq_details_id)
			// });
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
		axios.post(`/api/save_aoq/${props.id}`).then(function (response) {
			router.push(`/pur_aoq/print_te/${props.id}`)
		});
		
	}

	const openAOQ = (rfq_head_id) => {
		axios.post(`/api/open_aoq/${props.id}`).then(function () {
			router.push('/pur_quote/view/'+rfq_head_id+'/'+props.id)
		});
	}

	const openDraftAlert = () => {
		axios.post(`/api/update_aoq_draft/${props.id}`)
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
		axios.get(`/api/cancel_aoq/${props.id}`).then(function () {
			router.push('/pur_aoq')
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
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Abstract of Quotation <small>Awarding</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/pur_aoq">Abstract of Quotation</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Awarding</li>
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
									<span class="text-sm text-gray-700 font-bold pr-1">PR No: </span>
									<span class="text-sm text-gray-700">{{ head.pr_no }}</span>
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
							<div class="row">
								<div class="col-lg-12">
									<span class="text-sm text-gray-700 font-bold pr-1">End-Use:</span>
									<span class="text-sm text-gray-700">{{ head.enduse }}</span>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<span class="text-sm text-gray-700 font-bold pr-1">Purpose: </span>
									<span class="text-sm text-gray-700">{{ head.purpose }}</span>
								</div>
							</div>
							<div>
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
												<td class="uppercase bg-gray-100 p-1 align-top" width="30%">Item Description</td>
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
											 <template v-for="(ai, itemno) in aoq_items">
												<tr>
													<td class="p-1 align-top text-center" rowspan="4">{{ itemno + 1 }}</td>
													<td class="p-1 align-top" rowspan="4">{{ ai.item_description }}</td>
													<td class="p-1 align-top text-center" rowspan="4">{{ parseFloat(ai.quantity).toFixed(2) }}</td>
													<td class="p-1 align-top text-center" rowspan="4">{{ ai.uom }}</td>
												</tr>
												<!-- loop here if 3 and below offers here -->
												<template v-for="(ao, i) in aoq_offers">
													<tr v-if="(ai.rfq_details_id == ao.rfq_details_id)">
														<!-- loop offers per vendor here -->
															<td class="p-1" width="30%" >
																{{ ao.offer }}
															</td>
															<td width="11%" :class="(ao.min_price == ao.unit_price && head.status != 'Cancelled') ? 'p-1 align-top bg-yellow-300' : 'p-1 align-top '">
																<div class="flex justify-between space-x-1">
																	<span>{{ (ao.unit_price != 0) ? ao.offer_currency : '' }}</span>
																	<span>{{ (ao.unit_price != 0) ? parseFloat(ao.unit_price).toFixed(2): '' }}</span>
																</div>
															</td>
															<td width="11%" :class="(ao.awarded == 1 && head.status != 'Cancelled') ? 'p-1 align-top bg-lime-500' : 'p-1 align-top'">
																<div class="flex justify-between space-x-1">
																	<span>{{ (ao.unit_price != 0) ? ao.offer_currency: '' }}</span>
																	<span>{{ (ao.unit_price != 0) ? parseFloat(ao.unit_price *  ai.quantity).toFixed(2): '' }}</span>
																</div>
															</td>
															<td class="p-1 align-top text-center" width="3%"  v-if="(head.status != 'Awarded' && ao.unit_price != 0)">
																<input type="radio" :name="'awarded'+ itemno" :id="'awarded_'+ i" v-model = "ao.awarded" value="1" @click="UpdateOffersAwarded(i,ao.rfq_offer_id,ai.pr_details_id,latest_aoq_details_id)">
															</td>
															<td class="p-1 align-top text-center" width="3%" v-else>
																<!-- <input type="radio" :name="'awarded'+ itemno" :id="'awarded_'+ i" v-model = "ao.awarded" value="1" disbaled> -->
															</td>
															
															<td class="p-1 align-top" width="10%" v-if="(head.status != 'Awarded')">
																<textarea placeholder="Comments" class="w-full" :id="'comments_'+ i" v-model = "ao.comments" @blur="UpdateOffersComments(i,ao.rfq_offer_id,latest_aoq_details_id)"></textarea>
															</td>
															<td class="p-1 align-top" width="10%" v-else>
																<textarea placeholder="Comments" class="w-full" :id="'comments_'+ i" v-model = "ao.comments" readonly></textarea>
															</td>
															<!-- <input type="hidden" v-model = "ao.rfq_offer_id"> -->
														<!-- loop offers per vendor here -->
													</tr>
												</template>
												
											<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
											</template>
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
									</div>
								</div>
								<br>
								<div class="row my-2" v-if="(count_canvassed_aoq_v == count_aoq_vendors)"> 
									<div class="col-lg-12 col-md-12">
										<div class="flex justify-between space-x-2">
											<div class="flex justify-between space-x-1">
												<button type="submit" class="btn btn-danger mr-2 w-36" @click="CancelAlert()" v-if="(head.status != 'Awarded')">Cancel</button>
												<button type="submit" @click="openPreview()" class="btn btn-info w-26">Preview</button>
												<button type="submit" @click="openAOQ(head.rfq_head_id)" class="btn btn-warning ">Open AOQ</button>
												<!-- <button type="submit" @click="openAddVendor()" class="btn btn-info w-26">Add Vendor</button> -->
											</div>
											<div class="flex justify-between space-x-1" v-if="(head.status != 'Awarded')">
												<button type="submit" class="btn btn-warning w-26 !text-white" @click="openDraftAlert()">Save as Draft</button>
												<button @click="getAOQDoneTEDetails(previous.id)" type="submit" class="btn btn-primary w-26" title="Previous Vendor" v-if="(latest_aoq_details_id != props.aoq_details_id)">Back</button>
												<button v-if="(max_id == latest_aoq_details_id) && vendordets.count_awarded == 0" type="submit" id="saveaoqbtn" @click="openSaveAlert()" class="btn btn-primary w-26" style="pointer-events: none;">Save AOQ</button> 
												<button v-if="(max_id == latest_aoq_details_id) && vendordets.count_awarded != 0" type="submit" id="saveaoqbtn" @click="openSaveAlert()" class="btn btn-primary w-26">Save AOQ</button> 
												<button v-if="(max_id != latest_aoq_details_id)" @click="getAOQDoneTEDetails(next.id)" type="submit" class="btn btn-primary w-26" title="Next Vendor">Next</button>
											</div>
											<div class="flex justify-between space-x-1" v-else>
												<button @click="getAOQDoneTEDetails(previous.id)" type="submit" class="btn btn-primary w-26" title="Previous Vendor" v-if="(latest_aoq_details_id != props.aoq_details_id)">Back</button>
												<button v-else @click="getAOQDoneTEDetails(next.id)" type="submit" class="btn btn-primary w-26" title="Next Vendor">Next</button>
											</div>
										</div>
									</div>
								</div>
								<div class="row" v-else>
									<div class="col-lg-12">
										<div class="flex justify-center space-x-1">
											<button type="submit" @click="openAOQ(head.rfq_head_id)" class="btn btn-warning ">Update Vendor/s</button>
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
			<div class="modal pt-4 px-3" :class="{ show:showPreview }">
				<div @click="closeModal()" class="w-full h-full fixed"></div>
				<div class="modal__content w-10/12 mb-5">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">Preview AOQ</span>
							<a href="#" class="text-gray-600" @click="closeModal()">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<div class="modal_s_items">
					<div class="overflow-x-scroll">
						<div class="">
					<table class="w-full !text-xs mb-3 ">
						<tr>
							<td class="font-bold pr-1" width="8%">PR No: </td>
							<td class="">{{ previewhead.pr_no }}</td>
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
							<td class="font-bold pr-1">End-Use:</td>
							<td class="">{{ previewhead.enduse }}</td>
						</tr>
						<tr>
							<td class="font-bold pr-1">Purpose:</td>
							<td class="">{{ previewhead.purpose }}</td>
						</tr>
							</table>
					<table class="table-bordered !text-xs mb-3" width="250%">
						<tr>
							<td class="bg-gray-50 " colspan="4"></td>
							<!-- loop vendors here start -->
							<template v-for="av in aoq_vendor">
								<td class="bg-gray-50 p-1 text-center py-2" colspan="5">
								<p class="m-0 text-xs font-bold">{{ av.vendor_name }}</p>
								<!-- <p class="m-0 text-xs font-bold">MF Computer Solutions, Inc.</p>
								<p class="m-0 text-xs font-bold">Nexus Industrial Prime Solutions Corp.</p> -->
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
							<td class="uppercase bg-gray-100 p-1 align-top" width="10%">Item Description</td>
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
						<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->
						<template v-for="(pai, index) in preview_aoq_items">
							<tr>
								<td class="p-1 align-top text-center" rowspan="4">{{ index + 1 }}</td>
								<td class="p-1 align-top" rowspan="4">{{ pai.item_description }}</td>
								<td class="p-1 align-top text-center" rowspan="4">{{ pai.quantity }}</td>
								<td class="p-1 align-top text-center" rowspan="4">{{ pai.uom }}</td>
							</tr>
							<!-- loop here if 3 and below offers here -->
								<tr>	
									<!-- loop offers per vendor here -->
									<template v-for="fo in first_offers">
										<template v-if="pai.pr_details_id == fo.pr_details_id">
											<td class="p-1">
												{{ fo.offer }}
											</td>
											<td :class="(pai.min_price == fo.unit_price && head.status != 'Cancelled') ? 'p-1 align-top bg-yellow-300' : 'p-1 align-top '">
												<div class="flex justify-between space-x-1">
												<span>{{ (fo.unit_price != 0) ? fo.currency : '' }}</span>
												<span>{{  (fo.unit_price != 0) ? parseFloat(fo.unit_price).toFixed(2) : ''  }}</span>
												</div>
											</td>
											<td colspan="2" :class="(fo.awarded == 1 && head.status != 'Cancelled') ? 'p-1 align-top bg-lime-500' : 'p-1 align-top '">
												<div class="flex justify-between space-x-1">
													<span>{{ (fo.unit_price != 0) ? fo.currency : '' }}</span>
													<span>{{  (fo.unit_price != 0) ? parseFloat(fo.unit_price * pai.quantity).toFixed(2) : ''  }}</span>
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
										<template v-if="pai.pr_details_id == so.pr_details_id">
											<td class="p-1">
												{{ so.offer }}
											</td>
											<td :class="(pai.min_price == so.unit_price && head.status != 'Cancelled') ? 'p-1 align-top bg-yellow-300' : 'p-1 align-top '">
												<div class="flex justify-between space-x-1">
												<span>{{ (so.unit_price != 0) ? so.currency : '' }}</span>
												<span>{{  (so.unit_price != 0) ? parseFloat(so.unit_price).toFixed(2) : ''  }}</span>
												</div>
											</td>
											<td :class="(so.awarded == 1 && head.status != 'Cancelled') ? 'p-1 align-top bg-lime-500' : 'p-1 align-top '" colspan="2">
												<div class="flex justify-between space-x-1">
													<span>{{ (so.unit_price != 0) ? so.currency : '' }}</span>
													<span>{{  (so.unit_price != 0) ? parseFloat(so.unit_price * pai.quantity).toFixed(2) : ''  }}</span>
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
										<template v-if="pai.pr_details_id == to.pr_details_id">
											<td class="p-1">
												{{ to.offer }}
											</td>
											<td :class="(pai.min_price == to.unit_price && head.status != 'Cancelled') ? 'p-1 align-top bg-yellow-300' : 'p-1 align-top '">
												<div class="flex justify-between space-x-1">
												<span>{{ (to.unit_price != 0) ? to.currency : '' }}</span>
												<span>{{  (to.unit_price != 0) ? parseFloat(to.unit_price).toFixed(2) : ''  }}</span>
												</div>
											</td>
											<td :class="(to.awarded == 1 && head.status != 'Cancelled') ? 'p-1 align-top bg-lime-500' : 'p-1 align-top '" colspan="2">
												<div class="flex justify-between space-x-1">
													<span>{{ (to.unit_price != 0) ? to.currency : '' }}</span>
													<span>{{ (to.unit_price != 0) ? parseFloat(to.unit_price * pai.quantity).toFixed(2) : ''  }}</span>
												</div>
											</td>
											
											<td class="p-1 align-top">{{ to.remarks }}</td>
										</template>
									</template>
									<!-- loop offers per vendor here -->
								</tr>
							</template>
							<!-- loop here if 3 and below offers here -->
						<!-- loop here if it is per item row (rowspan should not be equal to offers just add 1 (ie: 4-rowspan = 3-offers)) -->

						<tr class="!border-0">
							<td class="!border-0" colspan="4"><br></td>
							<td class="!border-0" colspan="4"><br></td>
						</tr>
						<tr>
							<td colspan="4" class="p-0 !border-0">
								<table class="w-full">
									<tr>
										<td class="!border-0 text-right px-2" colspan="">Legend:</td>
										<td class="!border-0" colspan="2"></td>
										<td class="!border-0" width="50%"></td>
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
									<tr v-if="av.rfq_vendor_id == at.rfq_vendor_id">
										<td class="!border-0 text-start" >{{letters[termno]}}. {{ at.terms }}</td>
										<span hidden>{{ termno++ }}</span> 
									</tr>
									</template>
								</table>
							</td>
						</tr>
						<tr>
							<td><br></td>
						</tr>
					</table>
								<table class="!text-xs" width="250%">
									<tr>
										<td></td>
										<td width="12%">Prepared by:</td>
										<td></td>
										<td width="12%">Received and Checked by:</td>
										<td></td>
										<td width="12%">Award Recommended by:</td>
										<td></td>
										<td width="12%">Recommending Approval:</td>
										<td></td>
										<td width="12%">Aprroved by:</td>
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
							</div>
						</div>
						<div class="col-lg-12">
							<div class="flex justify-center space-x-1 my-3">
								<a :href="'/export-aoq/'+previewhead.aoq_head_id" class="btn btn-primary mr-2 w-44">Export</a>
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
									<h5 class="leading-tight">You have successfully created an AOQ.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <a href="/pur_aoq/new" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Create New</a> -->
									<a href="/pur_po/new" class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full">Proceed to PO</a>
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
									<a href="/pur_aoq/new" class="btn !text-white !bg-yellow-400 btn-sm !rounded-full w-full">Create New</a>
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
				<div @click="closeModal()" class="w-full h-full fixed"></div>
				<div class="modal__content w-8/12 mb-5">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">Before saving AOQ please fill out the following fields.</span>
							<a href="#" class="text-gray-600" @click="closeModal()">
								<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
							</a>
						</div>
					</div>
					<hr class="mt-0">
					<div class="modal_s_items">
						<div class="row">
							<div class="col-lg-4 col-md-4">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Date Needed</label>
									<input type="date" class="form-control" placeholder="" >
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Prepared by</label>
									<select class="form-control" placeholder="" >
										<option value="">--Select Employee--</option>
									</select>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Received and Checked by</label>
									<select class="form-control" placeholder="" >
										<option value="">--Select Employee--</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-4">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Award Recommended by</label>
									<select class="form-control" placeholder="" >
										<option value="">--Select Employee--</option>
									</select>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Recommending Approval</label>
									<select class="form-control" placeholder="" >
										<option value="">--Select Employee--</option>
									</select>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label class="text-gray-500 m-0" for="">Aprroved by</label>
									<select class="form-control" placeholder="" >
										<option value="">--Select Employee--</option>
									</select>
								</div>
							</div>
						</div>
						<!-- <div class="row">
							<div class="col-lg-12">
								<table class="w-full table-bordered text-sm" >
									<tr class="bg-gray-50">
										<td class="p-1" width="2%"><input type="checkbox" name="check"></td>
										<td class="p-1">Vendor</td>
									</tr>
									<tr >
										<td class="p-1"><input type="checkbox" name="check"></td>
										<td class="p-1">Vendor</td>
									</tr>
								</table>
							</div>
						</div> -->
						<hr>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn btn-primary mr-2 w-44" @click="openSuccessAlert()">Save</button>
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
									<button class="btn btn-danger btn-sm !rounded-full w-full"  @click="openAOQ(head.rfq_head_id)">Update Vendor/s</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
	
</template>