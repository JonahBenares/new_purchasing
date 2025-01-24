<script setup>
	import navigation from '@/layouts/navigation.vue';
	import printheader from '@/layouts/print_header.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref, onMounted } from "vue"
    import { useRouter } from "vue-router"
	import moment from 'moment'
	const printDiv = () => {
		window.print();
	}
	const props = defineProps({
		id:{
			type:String,
			default:''
		}
	})
	const po_dr =  ref([]);
	const po_dr_items =  ref([]);
	const vendor =  ref([]);
	const enduse =  ref('');
	const purpose =  ref('');
	const requestor =  ref('');
	const prepared_by =  ref('');
	const offer =  ref([]);
	const uom =  ref([]);
	const total_sumdelivered =  ref([]);
	onMounted(async () => {
		drLoad()
	})
	const drLoad = async () => {
		let response = await axios.get("/api/get_dr_view/"+props.id);
		po_dr.value = response.data.po_dr;
		po_dr_items.value = response.data.po_dr_items;
		vendor.value = response.data.vendor;
		enduse.value = response.data.enduse;
		purpose.value = response.data.purpose;
		requestor.value = response.data.requestor;
		prepared_by.value = response.data.prepared_by;
		po_dr_items.value.forEach(function (val, index, theArray) {
			getOffer(val.po_details_id,val.rfq_offer_id,index)
			checkRemainingQty(val.po_details_id,val.quantity,index)
		});
	}
	// const getOffer = async (rfq_offer_id,count) => {
	// 	let response = await axios.get("/api/get_offer/"+rfq_offer_id);
	// 	offer.value[count] = response.data.offer.offer;
	// 	uom.value[count] = response.data.offer.uom;
	// }
	const getOffer = async (po_details_id,rfq_offer_id,count) => {
		let response = await axios.get("/api/get_offer/"+po_details_id+'/'+rfq_offer_id);
		if(rfq_offer_id!=0){
			offer.value[count] = response.data.offer.offer;
			uom.value[count] = response.data.offer.uom;
		}else{
			offer.value[count] = response.data.offer.item_description;
			uom.value[count] = response.data.offer.uom;
		}
	}

	const checkRemainingQty = async (po_details_id,qty,count) => {
		let response = await axios.get("/api/check_remaining_dr_balance/"+po_details_id);
		total_sumdelivered.value[count] = response.data.balance_sum
	}
</script>
<template>
	<navigation>
		<div class="row" id="breadcrumbs">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Delivery Receipt <small>View</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/pur_dr">Delivery Receipt</a></li>
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
					<hr class="border-dashed mt-0">
					<div class="pt-1" id="printable">
						<div class="hidden print:block">
							<printheader ></printheader>
							<div class="flex justify-center mt-1">
								<span class="uppercase">Delivery Receipt</span>
								<!-- <span class="uppercase" v-if="po_head.method == 'DPO'">Direct</span>
								<span class="uppercase" v-if="po_head.method == 'RPO'">Repeat Order</span> -->
							</div>
							<hr class="print:block border-dashed mt-2">
						</div>
						<div class="row">
							<div class="col-lg-8 col-sm-8 col-md-8">
								<span class="text-sm text-gray-700 font-bold pr-1">PO No: </span>
								<span class="text-sm text-gray-700">{{po_dr.po_no}}{{ (po_dr.revision_no!=0 && po_dr.revision_no!=null) ? '.r'+po_dr.revision_no : '' }}</span>
							</div>
							<div class="col-lg-4 col-sm-4 col-md-4">
								<span class="text-sm text-gray-700 font-bold pr-1">DR No: </span>
								<span class="text-sm text-gray-700">{{po_dr.dr_no}}</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-8 col-sm-8 col-md-8">
								<span class="text-sm text-gray-700 font-bold pr-1">PR No: </span>
								<span class="text-sm text-gray-700">{{po_dr.pr_no}}</span>
							</div>
							<div class="col-lg-4 col-sm-4 col-md-4">
								<span class="text-sm text-gray-700 font-bold pr-1">Date: </span>
								<span class="text-sm text-gray-700">{{moment(po_dr.dr_date).format('MMM. DD,YYYY')}}</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-8 col-sm-8 col-md-8">
								<span class="text-sm text-gray-700 font-bold pr-1">End-use: </span>
								<span class="text-sm text-gray-700">{{enduse}}</span>
							</div>
							<div class="col-lg-4 col-sm-4 col-md-4">
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
								<table class="w-full table-bordered text-xs mt-3">
									<tr class="bg-gray-100">
										<td class="p-1 uppercase text-center" width="2%">#</td>
										<td class="p-1 uppercase" width="25%">Supplier</td>
										<td class="p-1 uppercase" width="25%">Description</td>
										<td class="p-1 uppercase text-center" width="7%" v-if="po_dr.received==0">To Deliver</td>
										<td class="p-1 uppercase text-center" width="8%">DLVRD Qty</td>
										<td class="p-1 uppercase text-center" width="5%">Received</td>
										<td class="p-1 uppercase text-center" width="5%">UOM</td>
										<td class="p-1 uppercase text-center" width="5%">Remarks</td>
									</tr>
									<tr v-for="(pdi,index) in po_dr_items">
										<td class="p-1 text-center">{{ index+1 }}</td>
										<td class="p-1 ">{{vendor.vendor_name}} ({{ vendor.identifier }})</td>
										<td class="p-1 ">{{offer[index]}}</td>
										<td class="p-1 text-center">{{ pdi.to_deliver }}</td>
										<td class="p-1 text-center" v-if="po_dr.received==0">{{ total_sumdelivered[index] }}</td>
										<td class="p-1 text-center" v-if="pdi.received_qty!=0">{{ pdi.received_qty }}</td>
										<td class="p-1 text-center" v-else></td>
										<td class="p-1 text-center">{{ uom[index] }}</td>
										<td class="p-1 text-center"></td>
										<!-- <td class="p-1 text-center">{{ pdi.to_deliver }}</td> -->
										<!-- <td class="p-1 text-center">{{ pdi.delivered_qty }}</td> -->
										<!-- <td class="p-1 text-center">{{ pdi.quantity }}</td> -->
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
										<td class="text-center p-1">{{ prepared_by }}</td>
										<td></td>
										<td class="text-center p-1">{{po_dr.driver}}</td>
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
						<hr class="border-dashed">
						<div class="row my-2 po_buttons" > 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button type="submit" class="btn btn-primary mr-2 w-36"  @click="printDiv()">Print</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				</div>
			</div>
		</div>
	</navigation>
</template>