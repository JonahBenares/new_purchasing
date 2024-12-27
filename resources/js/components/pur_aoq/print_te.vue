<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, CheckIcon} from '@heroicons/vue/24/solid'
	import axios from 'axios';
    import { onMounted, ref } from "vue"
	import { useRouter } from "vue-router";
	const router = useRouter();

	let head=ref([]);
	let aoq_vendor=ref([]);
	let aoq_items=ref([]);
	// let aoq_offers=ref([]);
	let first_offers=ref([]);
	let second_offers=ref([]);
	let third_offers=ref([]);
	let vendor_list=ref([]);
	let vendor_terms=ref([]);
	let all_terms=ref([]);
	let vendor_details=ref('');
	let pr_items_data=ref('');
	let letters=ref([]);
	let itemoffers=ref([]);
	let vendor_aoq_items=ref([]);
	let currency=ref([]);
	let count_rfq_vendors=ref(0);
	let count_canvassed_aoq_v=ref(0);
	let count_aoq_vendors=ref(0);
	let aoq_details_id=ref(0);

	const props = defineProps({
        id:{
            type:String,
            default:''
        },	
    })

	onMounted(async () => {
		getAOQHeadDetails()
	})

	const getAOQHeadDetails = async () => {
		let response = await axios.get(`/api/aoq_head_details/${props.id}`)
		head.value = response.data.aoq_head_data
		aoq_details_id.value = response.data.aoq_details_id
		aoq_vendor.value = response.data.aoq_vendor_data
		aoq_items.value = response.data.aoq_items_data
		// aoq_offers.value = response.data.aoq_offers_data
		first_offers.value = response.data.first_offers
		second_offers.value = response.data.second_offers
		third_offers.value = response.data.third_offers
		vendor_list.value = response.data.vendor_list
		vendor_terms.value = response.data.vendor_terms
		all_terms.value = response.data.all_terms
		pr_items_data.value = response.data.pr_items_data
		letters.value=response.data.letters
		currency.value = response.data.currency
		count_rfq_vendors.value = response.data.count_rfq_vendors
		count_canvassed_aoq_v.value = response.data.count_canvassed_aoq_v
		count_aoq_vendors.value = response.data.count_aoq_vendors

		if(count_canvassed_aoq_v.value != count_aoq_vendors.value){
			openAOQAlert.value = !openAOQAlert.value
		}else{
			openAOQAlert.value = !hideModal.value
		}
	}

	const VendorItemsOffer = async () => {
		let ven = vendor_details.value
		const v = ven.split("_")
		let rfq_vendor_id= v[0]
		
		let response = await axios.get('/api/vendor_offers/'+rfq_vendor_id+'/'+head.value.rfq_head_id)
		vendor_aoq_items.value = response.data.vendor_aoq_items
		itemoffers.value = response.data.itemoffers
		document.getElementById("display_offers").style.display="block"
		document.getElementById("addnewvendor").disabled = false;
	}

	const SaveAdditionalVendor = () => {
		AddVendorAlert.value = !hideModal.value
		const formAdditional= new FormData()
			let ven = vendor_details.value
			const v = ven.split("_")
			let rfq_vendor_id= v[0]

			formAdditional.append('aoq_head_id', props.id)
			formAdditional.append('rfq_vendor_id', rfq_vendor_id)

			// var count_offers=document.getElementsByClassName('offers_');
			// for(var o=0;o<count_offers.length;o++){
			// 	var rfq_offer_id =document.getElementsByClassName("offerid_")[o].value;
			// 	var offers =document.getElementsByClassName("offers_")[o].value;
			// 	var unit_price=document.getElementsByClassName("unitprice_")[o].value;
			// 	var currency=document.getElementsByClassName("currency_")[o].value;
			// 		const vendor_o = {
			// 			rfq_offer_id:rfq_offer_id,
			// 			offer:offers,
			// 			unit_price:unit_price,
			// 			currency:currency,
			// 		}
			// 		vendoroffers.value.push(vendor_o)
			// }
			formAdditional.append('itemoffers', JSON.stringify(itemoffers.value))
			axios.post("/api/add_aoq_vendor", formAdditional).then(function () {
			closeModal()
				getAOQHeadDetails()
			});
	}

	

	const AddBtn = () => {
		var count_offers=document.getElementsByClassName('offers_');
		var not_empty_offers = 0;
			for(var x=0;x<count_offers.length;x++){
				var offers = document.getElementsByClassName('offers_')[x].value
				if(offers != "") {
					not_empty_offers++;
				}
			}

			if(not_empty_offers>=1){
				document.getElementById("addnewvendor").disabled = false;
			}else{
				document.getElementById("addnewvendor").disabled = true;
			}
	}

	// const ExportAOQ = async () => {
	// 	axios.get(`/api/export_aoq/${props.id}`)
	// }

	const showModal = ref(false)
	const cancelAlert = ref(false)
	const AddVendorAlert = ref(false)
	const donetealert = ref(false)
	const openAOQAlert = ref(false)
	const printbutton = ref(false)
	const signatories = ref(false)
	const showAddVendor = ref(false)
	const hideModal = ref(true)
	const openModel = () => {
		showModal.value = !showModal.value
	}
	const closeModal = () => {
		showModal.value = !hideModal.value
		showAddVendor.value = !hideModal.value
		cancelAlert.value = !hideModal.value
		AddVendorAlert.value = !hideModal.value
		donetealert.value = !hideModal.value
		openAOQAlert.value = !hideModal.value
	}

	const DoneTEAlert = () => {
		donetealert.value = !donetealert.value
	}

	const DoneTE = () => {
		axios.post(`/api/done_te_aoq/${props.id}`).then(function () {
			router.push('/pur_aoq/view/'+props.id+'/'+aoq_details_id.value)
		});
	}

	const CancelAlert = () => {
		cancelAlert.value = !cancelAlert.value
	}

	const CancelTransaction = () => {
		axios.get(`/api/cancel_aoq/${props.id}`).then(function () {
			router.push('/pur_aoq')
		});
	}

	const openAOQ = (rfq_head_id) => {
		axios.post(`/api/open_aoq/${props.id}`).then(function (response) {
			router.push('/pur_quote/view/'+rfq_head_id+'/'+props.id)
		});
	}

	const openAddVendor = () => {
		showAddVendor.value = !showAddVendor.value
	}

	const SaveAdditionalVendorAlert = () => {
		AddVendorAlert.value = !AddVendorAlert.value
	}



	
	
</script>
<template>
	<navigation>
		<div class="bg-yellow-400 text-white px-3 py-2 font-bold" v-if="(head.status != 'Cancelled' && head.aoq_status == 'For TE')">For Technical Evaluation (AOQ - {{props.id}})</div>
		<div class="bg-blue-400 text-white px-3 py-2 font-bold" v-if="(head.status != 'Cancelled' && head.aoq_status == 'Done TE')">Done Technical Evaluation (AOQ - {{props.id}})</div>
		<div class="bg-lime-500 text-white px-3 py-2 font-bold" v-if="(head.status != 'Cancelled' && head.aoq_status == 'Awarded')">Awarded (AOQ - {{props.id}})</div>
		<div class="bg-red-500 text-white px-3 py-2 font-bold" v-if="(head.status == 'Cancelled')">CANCELLED (AOQ - {{props.id}}) Cancelled date: {{head.cancelled_date}}, Cancelled by: {{head.cancelled_name}}</div>
		<div class="bg-white p-4 ">
			<div class="overflow-x-scroll">
				<div class="">
					<table class="w-full !text-xs mb-3 ">
						<tr>
							<td class="font-bold pr-1" width="8%">PR No: </td>
							<td class="">{{ head.pr_no }}</td>
							<td class=" font-bold pr-1" width="8%">AOQ No: </td>
							<td class="">{{ head.aoq_no }}</td>
							<td class=" font-bold pr-1" width="8%">Requested By: </td>
							<td class="">{{ head.requestor }}</td>
						</tr>
						<tr>
							<td class="font-bold pr-1">Department: </td>
							<td class="">{{ head.department }}</td>
							<td class=" font-bold pr-1">Date: </td>
							<td class="">{{ head.aoq_date }}</td>
							<td class=" font-bold pr-1">Date Needed: </td>
							<td class="">{{ head.date_needed }}</td>
						</tr>
						<tr>
							<td class="font-bold pr-1">End-Use:</td>
							<td class="">{{ head.enduse }}</td>
						</tr>
						<tr>
							<td class="font-bold pr-1">Purpose:</td>
							<td class="">{{ head.purpose }}</td>
						</tr>
					</table>
					<table class="table-bordered !text-xs mb-3" width="150%">
						<tr>
							<td class="bg-gray-50 " colspan="4"></td>
							<!-- loop vendors here start -->
							<template v-for="av in aoq_vendor">
								<td class="bg-gray-50 p-1 text-center py-2" colspan="5">
								<p class="m-0 text-xs font-bold">{{ av.vendor_name }} ({{ av.vendor_identifier }})</p>
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
						<template v-for="(ai, index) in aoq_items">
							<tr>
								<td class="p-1 align-top text-center" rowspan="4">{{ index + 1 }}</td>
								<td class="p-1 align-top" rowspan="4">{{ ai.item_description }}</td>
								<td class="p-1 align-top text-center" rowspan="4">{{  parseFloat(ai.quantity).toFixed(2) }}</td>
								<td class="p-1 align-top text-center" rowspan="4">{{ ai.uom }}</td>
							</tr>
							<!-- loop here if 3 and below offers here -->
								<tr>	
									<!-- loop offers per vendor here -->
									<template v-for="fo in first_offers">
										<template v-if="ai.pr_details_id == fo.pr_details_id">
											<td class="p-1">
												{{ fo.offer }}
											</td>
											<td :class="(ai.min_price == fo.unit_price && head.status != 'Cancelled') ? 'p-1 align-top bg-yellow-300' : 'p-1 align-top '">
												<div class="flex justify-between space-x-1">
												<span>{{ (fo.unit_price != 0) ? fo.currency : '' }}</span>
												<span>{{  (fo.unit_price != 0) ? parseFloat(fo.unit_price).toFixed(2) : ''  }}</span>
												</div>
											</td>
											<td colspan="2" :class="(fo.awarded == 1 && head.status != 'Cancelled') ? 'p-1 align-top bg-lime-500' : 'p-1 align-top '">
												<div class="flex justify-between space-x-1">
													<span>{{ (fo.unit_price != 0) ? fo.currency : '' }}</span>
													<span>{{  (fo.unit_price != 0) ? parseFloat(fo.unit_price * ai.quantity).toFixed(2) : ''  }}</span>
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
										<template v-if="ai.pr_details_id == so.pr_details_id">
											<td class="p-1">
												{{ so.offer }}
											</td>
											<td :class="(ai.min_price == so.unit_price && head.status != 'Cancelled') ? 'p-1 align-top bg-yellow-300' : 'p-1 align-top '">
												<div class="flex justify-between space-x-1">
												<span>{{ (so.unit_price != 0) ? so.currency : '' }}</span>
												<span>{{  (so.unit_price != 0) ? parseFloat(so.unit_price).toFixed(2) : ''  }}</span>
												</div>
											</td>
											<td :class="(so.awarded == 1 && head.status != 'Cancelled') ? 'p-1 align-top bg-lime-500' : 'p-1 align-top '" colspan="2">
												<div class="flex justify-between space-x-1">
													<span>{{ (so.unit_price != 0) ? so.currency : '' }}</span>
													<span>{{  (so.unit_price != 0) ? parseFloat(so.unit_price * ai.quantity).toFixed(2) : ''  }}</span>
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
										<template v-if="ai.pr_details_id == to.pr_details_id">
											<td class="p-1">
												{{ to.offer }}
											</td>
											<td :class="(ai.min_price == to.unit_price && head.status != 'Cancelled') ? 'p-1 align-top bg-yellow-300' : 'p-1 align-top'">
												<div class="flex justify-between space-x-1">
												<span>{{ (to.unit_price != 0) ? to.currency : '' }}</span>
												<span>{{  (to.unit_price != 0) ? parseFloat(to.unit_price).toFixed(2) : ''  }}</span>
												</div>
											</td>
											<!-- <td class="p-1 align-top" colspan="2"> -->
											<td :class="(to.awarded == 1 && head.status != 'Cancelled') ? 'p-1 align-top bg-lime-500' : 'p-1 align-top '" colspan="2">
												<div class="flex justify-between space-x-1">
													<span>{{ (to.unit_price != 0) ? to.currency : '' }}</span>
													<span>{{  (to.unit_price != 0) ? parseFloat(to.unit_price * ai.quantity).toFixed(2) : ''  }}</span>
												</div>
											</td>
											<td class="p-1 align-top">{{ to.remarks }}</td>
										</template>
									</template>
								</tr>
							</template>

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
									<tr v-if="av.rfq_vendor_id == at.rfq_vendor_id">
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
							<td width="12%">Received and Checked by:</td>
							<td></td>
							<td width="12%">Award Recommended by:</td>
							<td></td>
							<td width="12%">Recommending Approval:</td>
							<td></td>
							<td width="12%">Approved by:</td>
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
								{{ head.prepared_by_name }}
							</td>
							<td></td>
							<td class="border-b">
								{{ head.received_by_name }}
							</td>
							<td></td>
							<td class="border-b">
								{{ head.award_recommended_by_name }}
							</td>
							<td></td>
							<td class="border-b">
								{{ head.recommended_by_name }}
							</td>
							<td></td>
							<td class="border-b">
								{{ head.approved_by_name }}
							</td>
							<td></td>
						</tr>
					</table>
					<br>
				</div>
			</div>
			<br>
			<div class="row" v-if="(count_canvassed_aoq_v == count_aoq_vendors)">
				<div class="col-lg-12" v-if="(head.status != 'Cancelled' && head.aoq_status != 'Awarded' && head.aoq_status != 'Done TE')">
					<div class="flex justify-between space-x-1">
						<div class="flex justify-start space-x-1">
							<button type="button" @click="CancelAlert()" class="btn btn-danger">Cancel</button>
							<button type="submit" @click="openAddVendor()" class="btn btn-info " v-if="count_rfq_vendors != 0">Add Vendor</button>
							<button type="submit" @click="openAOQ(head.rfq_head_id)" class="btn btn-warning ">Open AOQ</button>
						</div>
						<div class="flex justify-end space-x-1">
							<a :href="'/export-aoq/'+head.aoq_head_id" class="btn btn-primary ">Export</a>
							<button type="submit" @click="DoneTEAlert()" class="btn btn-success ">Done TE & Proceed</button>
						</div>
					</div>
				</div>
				<div class="col-lg-12" v-else>
					<div class="flex justify-center space-x-1">
						<a :href="'/export-aoq/'+head.aoq_head_id" class="btn btn-primary mr-2 w-44">Export</a>
						<!-- <button type="submit" @click="openAOQ(head.rfq_head_id)" class="btn btn-warning ">Open AOQ</button> -->
					</div>
				</div>
			</div>
			<div class="col-lg-12" v-else>
				<div class="flex justify-center space-x-1">
					<button type="submit" @click="openAOQ(head.rfq_head_id)" class="btn btn-warning ">Update Vendor/s</button>
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
				<div @click="closeModal()" class="w-full h-full fixed"></div>
				<div class="modal__content w-8/12 mb-5">
					<div class="row mb-3">
						<div class="col-lg-12 flex justify-between">
							<span class="font-bold ">Add Vendor</span>
							<a href="#" class="text-gray-600" @click="closeModal()">
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
									<select class="form-control" v-model="vendor_details" @change="VendorItemsOffer">
										<!-- <option value="" disabled>With RFQ</option> -->
										<option :value="v.id+'_'+v.vendor_name+'_'+v.identifier" v-for="v in vendor_list" :key="v.id">{{ v.vendor_name }} {{ (v.identifier) }}</option>
									</select>
								</div>
								<table class="table-bordered w-full !text-xs" style="display:none" id="display_offers">
									<tr>
										<td class="p-1 text-center" width="5%">No</td>
										<td class="p-1 text-center" width="10%">PR Qty</td>
										<td class="p-1 text-center" width="10%">Remaining Qty</td>
										<td class="p-1" width="35%">Item Description</td>
										<td class="p-1" width="35%">Brand/Offer</td>
										<td class="p-1 text-center" width="15%">Unit Price</td>
									</tr>
									<tr v-for="(vai, itemno) in vendor_aoq_items">
										<td class="p-1 align-top text-center">{{ itemno + 1 }}</td>
										<td class="p-1 align-top text-center">{{ parseFloat(vai.quantity).toFixed(2) }}</td>
										<td class="p-1 align-top text-center">{{ parseFloat(vai.remaining_qty).toFixed(2) }}</td>
										<td class="p-1 align-top">{{ vai.item_description }}</td>
										<td class="align-top">
											<div v-for="(io, o) in itemoffers">
												<textarea type="text" class="border-b p-1 w-full h-14 !align-top offers_" :id="'offers'+ o" v-model="io.offer" v-if="io.rfq_details_id == vai.rfq_details_id"></textarea>
												<input type="hidden" class="offerid_" v-if="io.rfq_details_id == vai.rfq_details_id" v-model="io.rfq_offer_id" >
											</div>
										</td>
										<td class="align-top">
										<div  v-for="io in itemoffers">
											<div class="!h-14 border-b" v-if="io.rfq_details_id == vai.rfq_details_id">
												<input type="text" class="border-b p-1 w-full !align-top text-center" placeholder="00.00" v-model="io.unit_price" >
												<select class="p-1 m-0 leading-none w-full text-center  block text-xs whitespace-nowrap currency_" v-model="io.offer_currency">
													<option v-for="cur in currency" v-bind:key="cur" v-bind:value="cur">{{ cur }}</option>
												</select>
												<input type="hidden" class="offerid_" v-if="io.rfq_details_id == vai.rfq_details_id" v-model="io.rfq_offer_id" >
											</div>
										</div>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<!-- <a href="/pur_aoq/view" class="btn btn-primary mr-2 w-44">Save</a> -->
									<button type="submit" class="btn btn-primary w-26" id = "addnewvendor" disabled @click="SaveAdditionalVendorAlert">Add</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:AddVendorAlert }">
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
									<h5 class="leading-tight">Are you sure you want to add this vendors?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full" @click="closeModal()">No</button>
									<button class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full" @click="SaveAdditionalVendor()">Yes</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:donetealert }">
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
									<h5 class="leading-tight">Are you sure you want to proceed?</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full" @click="closeModal()">No</button>
									<button class="btn !text-white !bg-green-500 btn-sm !rounded-full w-full" @click="DoneTE()">Yes</button>
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