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
	const joi_dr =  ref([]);
	const joi_dr_labor =  ref([]);
	const joi_dr_material =  ref([]);
	const joi_vendor =  ref([]);
	const enduse =  ref('');
	const purpose =  ref('');
	const requestor =  ref('');
	const project_activity =  ref('');
	const general_description =  ref('');
	const prepared_by =  ref('');
	const offer_labor =  ref([]);
	const uom_labor =  ref([]);
	const offer_material =  ref([]);
	const uom_material =  ref([]);
	const total_labor_sumdelivered =  ref([]);
	const total_material_sumdelivered =  ref([]);
	onMounted(async () => {
		drLoad()
	})
	const drLoad = async () => {
		let response = await axios.get("/api/get_jo_dr_view/"+props.id);
		joi_dr.value = response.data.joi_dr;
		joi_dr_labor.value = response.data.joi_dr_labor;
		joi_dr_material.value = response.data.joi_dr_material;
		joi_vendor.value = response.data.joi_vendor;
		enduse.value = response.data.enduse;
		purpose.value = response.data.purpose;
		requestor.value = response.data.requestor;
		project_activity.value = response.data.project_activity;
		general_description.value = response.data.general_description;
		prepared_by.value = response.data.prepared_by;
		joi_dr_labor.value.forEach(function (val, index, theArray) {
			getLaborOffer(val.jo_rfq_labor_offer_id,index)
			checkRemainingLaborQty(val.joi_labor_details_id,val.quantity,index)
		});
		joi_dr_material.value.forEach(function (val, indexe, theArray) {
			getMaterialOffer(val.jo_rfq_material_offer_id,indexe)
			checkRemainingMaterialQty(val.joi_material_details_id,val.quantity,indexe)
		});
	}

	const getLaborOffer = async (jo_rfq_labor_offer_id,count) => {
		let response = await axios.get("/api/get_offer_labor/"+jo_rfq_labor_offer_id);
		offer_labor.value[count] = response.data.offer.offer;
		uom_labor.value[count] = response.data.offer.uom;
	}

	const getMaterialOffer = async (jo_rfq_labor_offer_id,count) => {
		let response = await axios.get("/api/get_offer_material/"+jo_rfq_labor_offer_id);
		offer_material.value[count] = response.data.offer.offer;
		uom_material.value[count] = response.data.offer.uom;
	}

	const checkRemainingLaborQty = async (joi_labor_details_id,qty,count) => {
		let response = await axios.get("/api/check_remaining_dr_labor_balance/"+joi_labor_details_id);
		total_labor_sumdelivered.value[count] = response.data.balance_sum
	}

	const checkRemainingMaterialQty = async (joi_material_details_id,qty,count) => {
		let response = await axios.get("/api/check_remaining_dr_material_balance/"+joi_material_details_id);
		total_material_sumdelivered.value[count] = response.data.balance_sum
	}
</script>
<template>
	<navigation>
		<div class="row" id="breadcrumbs" >
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Delivery Receipt <small>View</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item active"><a href="/job_dr">JO Delivery Receipt</a></li>
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
							<div class="col-lg-8 col-md-8 col-sm-8">
								<span class="text-sm text-gray-700 font-bold pr-1">JOI No: </span>
								<span class="text-sm text-gray-700">{{joi_dr.joi_no}}</span>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4">
								<span class="text-sm text-gray-700 font-bold pr-1">DR No: </span>
								<span class="text-sm text-gray-700">{{joi_dr.dr_no}}</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-8 col-md-8 col-sm-8">
								<span class="text-sm text-gray-700 font-bold pr-1">JOR No: </span>
								<span class="text-sm text-gray-700">{{joi_dr.jor_no}}</span>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4">
								<span class="text-sm text-gray-700 font-bold pr-1">Date: </span>
								<span class="text-sm text-gray-700">{{moment(joi_dr.dr_date).format('MMM. DD,YYYY')}}</span>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-4">
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
								<table class="w-full table-bordered text-xs mt-3">
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
										<td class="p-1 text-center">{{ index+1 }}</td>
										<td class="p-1 ">{{joi_vendor.vendor_name}} ({{ joi_vendor.identifier }})</td>
										<td class="p-1 ">{{  offer_labor[index] }}</td>
										<td class="p-1 text-center">{{  jdl.delivered_qty }}</td>
										<td class="p-1 text-center">{{ total_labor_sumdelivered[index] }}</td>
										<td class="p-1 text-center" v-if="jdl.received_qty!=0">{{ jdl.received_qty }}</td>
										<td class="p-1 text-center" v-else></td>
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
										<td class="p-1 text-center">{{  jdm.delivered_qty }}</td>
										<td class="p-1 text-center">{{  total_material_sumdelivered[indexes] }}</td>
										<td class="p-1 text-center" v-if="jdm.received_qty!=0">{{ jdm.received_qty }}</td>
										<td class="p-1 text-center" v-else></td>
										<td class="p-1 text-center">{{ uom_material[indexes] }}</td>
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
										<td class="text-center p-1">{{joi_dr.driver}}</td>
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
									<button type="submit" class="btn btn-primary mr-2 w-36" @click="printDiv()">Print</button>
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