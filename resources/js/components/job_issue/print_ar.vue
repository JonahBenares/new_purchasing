<script setup>
	import navigation from '@/layouts/navigation.vue';
	import printheader from '@/layouts/print_header.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, Bars4Icon} from '@heroicons/vue/24/solid'
    import { reactive, ref, onMounted } from "vue"
    import { useRouter } from "vue-router"
	const vendor =  ref();
	const preview =  ref();
    const dangerAlert = ref(false)
	const dangerAlert_item = ref(false)
	const drawer_dr = ref(false)
	const drawer_rfd = ref(false)
	const drawer_revise = ref(false)
	const hideModal = ref(true)
	const hideAlert = ref(true)
	const joi_dr=ref([])
	const joi_head=ref([])
	const jor_head=ref([])
	const joi_labor_details=ref([])
	const joi_material_details=ref([])
	const joi_dr_labor=ref([])
	const joi_dr_material=ref([])
	const prepared_by=ref('')
	const labor_sum_delivery=ref([])
	const material_sum_delivery=ref([])
	const labor_sum_received=ref([])
	const material_sum_received=ref([])
	const props = defineProps({
		id:{
			type:String,
			default:''
		}
	})
    onMounted(async () => {
		joView()
	})
	const joView = async () => {
		let response = await axios.get("/api/jo_viewdetails/"+props.id);
		joi_dr.value = response.data.joi_dr;
		joi_head.value = response.data.joi_head;
		jor_head.value = response.data.jor_head;
		joi_labor_details.value = response.data.joi_labor_details;
        joi_material_details.value = response.data.joi_material_details;
		joi_dr_labor.value=response.data.joi_dr_labor;
		joi_dr_material.value=response.data.joi_dr_material;
        prepared_by.value = response.data.prepared_by;
		joi_dr_labor.value.forEach(function (val, index, theArray) {
			labor_sum_delivery.value[index]=val.delivered_qty
			labor_sum_received.value[index]=val.received_qty
		});
		joi_dr_material.value.forEach(function (val, indexes, theArray) {
			material_sum_delivery.value[indexes]=val.delivered_qty
			material_sum_received.value[indexes]=val.received_qty
		});
	}
    const openDangerPO = () => {
		dangerAlert.value = !dangerAlert.value
	}
    const openDangerItem = () => {
		dangerAlert_item.value = !dangerAlert_item.value
	}
    const closeAlert = () => {
		dangerAlert_item.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
	}
	const openDrawerDR = () => {
		drawer_dr.value = !drawer_dr.value
	}
    const openDrawerRFD = () => {
		drawer_rfd.value = !drawer_rfd.value
	}
    const openDrawerRevise = () => {
		drawer_revise.value = !drawer_revise.value
	}
	const closeModal = () => {
		drawer_dr.value = !hideModal.value
		drawer_rfd.value = !hideModal.value
		drawer_revise.value = !hideModal.value
	}
    const printDiv = () => {
		window.print();
	}
</script>
<template>
    <navigation>
		<div class="row" id="breadcrumbs">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Issuance <small>View</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/job_issue">JO Issuance</a></li>
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
                        <div class="print:hidden mb-2">
                            <span class="font-bold text-gray-500 text-base">ACKNOWLEDGEMENT RECEIPT</span>
                        </div>
                        <hr class="border-dashed mt-0">
                        <div class="pt-1" id="printable">
                            <div class="hidden print:block">
								<printheader ></printheader>
								<div class="flex justify-center mt-1">
									<span class="uppercase">ACKNOWLEDGEMENT RECEIPT</span>
								</div>
								<hr class="print:block border-dashed mt-2">
							</div>
                            <div>
								<div class="row">
									<div class="col-lg-8 col-sm-8 col-md-8">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-32">DR No: </span>
											<input type="text" class="border-b bg-white w-full font-bold text-sm" v-model="joi_dr.dr_no" disabled>
										</div>
									</div>
									<div class="col-lg-4 col-sm-4 col-md-4">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-36">Date: </span>
											<input type="text" class="border-b bg-white w-full text-sm" v-model="joi_head.date_prepared" disabled>
										</div>	
									</div>
								</div>
								<div class="row">
									<div class="col-lg-8 col-sm-8 col-md-8">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-32">JO No.: </span>
											<span class="border-b bg-white w-full text-sm" disabled>{{ joi_head.joi_no }}{{ (joi_head.revision_no!=0 && joi_head.revision_no!=null) ? '.r'+joi_head.revision_no : '' }}</span>
											<!-- <input type="text" class="border-b bg-white w-full text-sm" v-model="joi_head.joi_no" disabled> -->
										</div>
									</div>
									<div class="col-lg-4 col-sm-4 col-md-4">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-36">JOR No: </span>
											<input type="text" class="border-b bg-white w-full text-sm" v-model="joi_head.jor_no" disabled>
										</div>
									</div>
								</div>
                                <div class="row">
									<div class="col-lg-8 col-sm-8 col-md-8">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-32">Requested By: </span>
											<input type="text" class="border-b bg-white w-full text-sm" v-model="jor_head.requestor" disabled>
										</div>
									</div>
								</div>
								<div class="" >
									<br>
									<div class="row">
										<div class="col-lg-12">
											<div class="border-2">
												<table class="table-bordered w-full !text-xs">
													<tr class="!border-b-3">
														<td colspan="7" class="py-2">
															<p class="text-sm font-bold text-gray-600 text-center m-0">{{ jor_head.project_activity }}</p>
															<p class="text-xs text-gray-600 text-center m-0">Project Title/Description</p>
														</td>
													</tr>
													<tr class="bg-gray-100">
														<td class="uppercase p-1 text-center" width="1%">#</td>
														<td class="uppercase p-1" width="20%">Supplier </td>
														<td class="uppercase p-1" width="20%">Description</td>
														<td class="uppercase p-1 text-center" width="5%">DLVRD</td>
														<td class="uppercase p-1 text-center" width="5%">RCVD</td>
														<td class="uppercase p-1 text-center" width="5%">UOM</td>
														<td class="uppercase p-1 text-center" width="12%">Remarks</td>
													</tr>
													<tr class="" v-for="(jld,index) in joi_labor_details">
														<td class="p-1 text-center">{{ index + 1}}</td>
														<td class="p-1 ">{{ joi_head.vendor_name }}</td>
														<td class="p-1 ">{{ jld.item_description }}</td>
														<td class="p-1 text-center">{{labor_sum_delivery[index]}}</td>
														<td class="p-1 text-center">{{labor_sum_received[index]}}</td>
														<td class="p-1 text-center">{{ jld.uom }}</td>
														<td class="p-1 "></td>
													</tr>
													<tr class="" v-if="joi_material_details.length!=0">
														<td class="p-1 font-bold" colspan="7">Materials:</td>
													</tr>
													<tr class="" v-for="(jmd,indexes) in joi_material_details">
														<td class="p-1 text-center">{{ indexes + 1}}</td>
														<td class="p-1 ">{{ joi_head.vendor_name }}</td>
														<td class="p-1 ">{{ jmd.item_description }}</td>
														<td class="p-1 text-center">{{material_sum_delivery[indexes]}}</td>
														<td class="p-1 text-center">{{material_sum_received[indexes]}}</td>
														<td class="p-1 text-center">{{ jmd.uom }}</td>
														<td class="p-1 "></td>
													</tr>
												</table>
											</div>
										</div>
									</div>
                                    
                                    <div class="row mt-4 mb-4">
                                        <div class="col-lg-12">
                                            <table class="w-full text-xs">
                                                <tr>
                                                    <td class="text-center" width="20%">Prepared by</td>
                                                    <td width="2%"></td>
                                                    <td class="text-center" width="20%">Received by</td>
                                                    <td width="2%"></td>
                                                    <td class="text-center" width="20%">Noted by</td>
                                                    <td width="2%"></td>
                                                    <td class="text-center" width="20%">Complete & accepted by end-user</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center border-b"><br></td>
                                                    <td></td>
                                                    <td class="text-center border-b"></td>
                                                    <td></td>
                                                    <td class="text-center border-b"></td>
                                                    <td></td>
                                                    <td class="text-center border-b"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center p-1">{{ prepared_by }}</td>
                                                    <td></td>
                                                    <td class="text-center p-1"></td>
                                                    <td></td>
                                                    <td class="text-center p-1"></td>
                                                    <td></td>
                                                    <td class="text-center p-1">{{ jor_head.requestor }}</td>
                                                </tr>
												<tr>
                                                    <td class="text-center p-1"></td>
                                                    <td></td>
                                                    <td class="text-center p-1">Print Name & Signature with Date Received</td>
                                                    <td></td>
                                                    <td class="text-center p-1"></td>
                                                    <td></td>
                                                    <td class="text-center p-1"></td>
                                                </tr>
                                            </table>
											<br>
											<table class="w-full text-xs">
                                                <tr>
                                                    <td class="text-center" width="20%">Witnessed by</td>
                                                    <td width="2%"></td>
                                                    <td class="text-center" width="20%"></td>
                                                    <td width="2%"></td>
                                                    <td class="text-center" width="20%"></td>
                                                    <td width="2%"></td>
                                                    <td class="text-center" width="20%"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center border-b"><br></td>
                                                    <td></td>
                                                    <td class="text-center"></td>
                                                    <td></td>
                                                    <td class="text-center"></td>
                                                    <td></td>
                                                    <td class="text-center"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center p-1"></td>
                                                    <td></td>
                                                    <td class="text-center p-1"></td>
                                                    <td></td>
                                                    <td class="text-center p-1"></td>
                                                    <td></td>
                                                    <td class="text-center p-1"></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <br>
                                    <hr	class="border-dashed">
                                    
                                    <div class="row my-2 po_buttons" > 
                                        <div class="col-lg-12 col-md-12">
                                            <div class="flex justify-center space-x-2">
                                                <div class="flex justify-start space-x-1">
                                                    <!-- <a href="#" class="btn btn-primary" onclick="close_window();return false;">Back</a> -->
                                                    <div class="flex justify-between">
                                                        <a href="#" @click="printDiv()" type="submit" class="btn btn-primary !text-white w-26">Print</a>
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
			</div>
		</div>
	</navigation>
</template>