<script setup>
	import navigation from '@/layouts/navigation.vue';
	import printheader from '@/layouts/print_header.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, Bars4Icon} from '@heroicons/vue/24/solid'
    import { reactive, ref, onMounted} from "vue"
    import { useRouter } from "vue-router"
    import moment from 'moment'
	const vendor =  ref();
	const preview =  ref();
	const success =  ref();
	const error =  ref();
    const successAlert = ref(false)
    const dangerAlert = ref(false)
	const dangerAlert_item = ref(false)
	const drawer_dr = ref(false)
	const drawer_rfd = ref(false)
	const drawer_revise = ref(false)
	const hideModal = ref(true)
	const hideAlert = ref(true)
    const joi_dr = ref([])
    const joi_head_rev = ref([])
    const joi_head = ref([])
    const jor_head = ref([])
    const joi_vendor = ref([])
    const joi_labor_details = ref([])
    const joi_details_view = ref([])
    const joi_material_details = ref([])
    const joi_material_details_view = ref([])
    const joi_terms = ref([])
    const joi_instructions = ref([])
    const prepared_by = ref('')
    const checked_by = ref(0)
    const recommended_by = ref(0)
    const approved_by = ref(0)
    const cancelled_by = ref(0)
    let jo_details_id_view=ref("")
    let cancel_reason=ref("")
    let cancel_all_reason=ref("")
    let selection=ref("")
    const props = defineProps({
		id:{
			type:String,
			default:''
		}
	})
    onMounted(async () => {
		joView()
	})
    const formatNumber = (number) => {
        return number.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    const joView = async () => {
		let response = await axios.get("/api/jo_viewdetails/"+props.id);
		joi_dr.value = response.data.joi_dr_array;
		joi_head.value = response.data.joi_head;
		jor_head.value = response.data.jor_head;
		joi_vendor.value = response.data.joi_vendor;
		joi_labor_details.value = response.data.joi_labor_details;
		joi_details_view.value = response.data.joi_details_view;
        joi_material_details.value = response.data.joi_material_details;
		joi_material_details_view.value = response.data.joi_material_details_view;
		joi_terms.value = response.data.joi_terms;
		joi_instructions.value = response.data.joi_instructions;
		prepared_by.value = response.data.prepared_by;
		checked_by.value = response.data.checked_by;
		recommended_by.value = response.data.recommended_by;
		approved_by.value = response.data.approved_by;
		cancelled_by.value = response.data.cancelled_by;
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
        cancel_all_reason.value=''
	}
	const openDrawerDR = () => {
		drawer_dr.value = !drawer_dr.value
	}
    const openDrawerRFD = () => {
		drawer_rfd.value = !drawer_rfd.value
	}
    const openDrawerRevise = async (id) => {
		drawer_revise.value = !drawer_revise.value
        let response = await axios.get("/api/old_jo_revision_data/"+id);
		joi_head_rev.value = response.data.joi_head_rev;
	}
	const closeModal = () => {
		drawer_dr.value = !hideModal.value
		drawer_rfd.value = !hideModal.value
		drawer_revise.value = !hideModal.value
	}
    const printDiv = () => {
		window.print();
	}

    const cancelJOitems = (option, id, selections) => {
        selection.value=selections
        if(selections=='labor'){
            if(option=='yes'){
                if(cancel_reason.value!=''){
                    const formData= new FormData()
                    formData.append('cancel_reason', cancel_reason.value)
                    axios.post(`/api/cancel_jo_items/`+id,formData).then(function (response) {
                        dangerAlert_item.value = !hideAlert.value
                        success.value='Successfully cancelled item!'
                        successAlert.value = !successAlert.value
                        cancel_reason.value=''
                        document.getElementById('cancel_check').placeholder=""
                        document.getElementById('cancel_check').style.backgroundColor = '#FFFFFF';
                        joView()
                        setTimeout(() => {
                            closeAlert()
                        }, 2000);
                    })
                }else{
                    document.getElementById('cancel_check').placeholder="Cancel Reason must not be empty!"
                    document.getElementById('cancel_check').style.backgroundColor = '#FAA0A0';
                }
            }else{
                jo_details_id_view.value=id
                dangerAlert_item.value = !dangerAlert_item.value
            }
        }else{
            if(option=='yes'){
                if(cancel_reason.value!=''){
                    const formData= new FormData()
                    formData.append('cancel_reason', cancel_reason.value)
                    axios.post(`/api/cancel_jo_material_items/`+id,formData).then(function (response) {
                        dangerAlert_item.value = !hideAlert.value
                        success.value='Successfully cancelled item!'
                        successAlert.value = !successAlert.value
                        cancel_reason.value=''
                        document.getElementById('cancel_check').placeholder=""
                        document.getElementById('cancel_check').style.backgroundColor = '#FFFFFF';
                        joView()
                        setTimeout(() => {
                            closeAlert()
                        }, 2000);
                    })
                }else{
                    document.getElementById('cancel_check').placeholder="Cancel Reason must not be empty!"
                    document.getElementById('cancel_check').style.backgroundColor = '#FAA0A0';
                }
            }else{
                jo_details_id_view.value=id
                dangerAlert_item.value = !dangerAlert_item.value
            }
        }
	}

    const cancelAllJO = (option) => {
		if(option=='yes'){
			if(cancel_all_reason.value!=''){
				const formData= new FormData()
				formData.append('cancel_all_reason', cancel_all_reason.value)
				axios.post(`/api/cancel_all_jo/`+props.id,formData).then(function (response) {
                    dangerAlert.value = !hideAlert.value
                    success.value='Successfully cancelled JO!'
                    successAlert.value = !successAlert.value
                    cancel_all_reason.value=''
                    document.getElementById('cancel_all_check').placeholder=""
                    document.getElementById('cancel_all_check').style.backgroundColor = '#FFFFFF';
                    joView()
                    setTimeout(() => {
                        closeAlert()
                    }, 2000);
				})
			}else{
				document.getElementById('cancel_all_check').placeholder="Cancel Reason must not be empty!"
				document.getElementById('cancel_all_check').style.backgroundColor = '#FAA0A0';
			}
		}else{
			dangerAlert.value = !dangerAlert.value
		}
	}
</script>
<style>
    @media print {
        .print-only {
            display: block !important; /* Show only during print */
        }
        .no-print {
            display: none !important; /* Hide during print */
        }
        .in-print-only {
            display: block !important; /* Show only during print */
        }
    }
    
</style>
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
        <div class="row d-flex" id="proBanner" v-if="joi_head.grand_total>=10000">
            <div class="col-md-12 mb-3">
                <div class="card bg-gradient-primary border-0">
                    <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between flex-wrap">
                        <div class="flex justify-start space-x-2">
                            <span class="text-white">
                                <PrinterIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-5 h-5 "></PrinterIcon>
                            </span>
                            <p class="mb-0 text-white font-weight-medium">Please Print COC!</p>
                        </div>
                        <div class="d-flex">
                            <a href="" target="_blank" class="btn btn-outline-light mr-2 bg-gradient-danger border-0">View COC</a>
                            <button id="bannerClose" class="btn border-0 p-0 ml-auto">
                            <i class="mdi mdi-close text-white"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
                    <div class="py-2 px-2 bg-red-500" v-if="joi_head.status=='Cancelled'">
						<span class="font-bold text-white">CANCELLED || Cancelled By: {{ cancelled_by }}  || Cancelled Date: {{moment().format('MMM. DD,YYYY')}} </span>
					</div>
                    <div class="card-body">
                        <hr class="border-dashed mt-0">
                        <div class="pt-1" id="printable">
                            <div class="hidden print:block">
								<printheader ></printheader>
								<div class="flex justify-center mt-1">
									<span class="uppercase">Job Order</span>
								</div>
								<hr class="print:block border-dashed mt-2">
							</div>
                            <div>
                                <div class="row">
									<div class="col-lg-1 col-sm-1 col-md-1">
										<span class="text-sm">TO:</span>
									</div>
									<div class="col-lg-11 col-sm-11 col-md-11">
										<p class="m-0 font-bold capitalize">{{ joi_vendor.vendor_name }} ({{ joi_vendor.identifier }})</p>
										<p class="m-0">{{ joi_vendor.contact_person }}</p>
										<p class="m-0">{{ joi_vendor.address }}</p>
										<p class="m-0">{{ joi_vendor.phone }}</p>
									</div>
								</div>
								<hr class="border-dashed print:block">
								<div class="row">
									<div class="col-lg-6 col-sm-6 col-md-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-40">Date Needed: </span>
											<input type="text" class="border-b bg-white w-full" disabled :value="moment(joi_head.date_needed).format('MMM. DD,YYYY')">
										</div>
									</div>
									<div class="col-lg-6 col-sm-6 col-md-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-52">Completion of Work: </span>
											<input type="text" class="border-b bg-white w-full" disabled :value="moment(joi_head.completion_work).format('MMM. DD,YYYY')">
										</div>	
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-sm-6 col-md-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-40">Date Prepared: </span>
											<input type="text" class="border-b bg-white w-full" disabled :value="moment(joi_head.date_prepared).format('MMM. DD,YYYY')">
										</div>
									</div>
									<div class="col-lg-6 col-sm-6 col-md-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-52">CENPRI JOR No: </span>
											<input type="text" class="border-b bg-white w-full" disabled :value="jor_head.site_jor">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-sm-6 col-md-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-40">Start of Work: </span>
											<input type="text" class="border-b bg-white w-full" disabled :value="moment(joi_head.start_work).format('MMM. DD,YYYY')">
										</div>
									</div>
									<div class="col-lg-6 col-sm-6 col-md-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-52">JO No: </span>
											<input type="text" class="border-b bg-white w-full" disabled :value="joi_head.joi_no">
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
															<p class="text-sm font-bold text-gray-600 text-center m-0">{{jor_head.project_activity}}</p>
															<p class="text-xs text-gray-600 text-center m-0">Project Title/Description</p>
														</td>
													</tr>
													<tr class="bg-gray-100">
														<td class="uppercase p-1" colspan="3">Scope of Work</td>
														<td class="uppercase p-1 text-center" width="7%">Qty</td>
														<td class="uppercase p-1 text-center" width="7%">Unit</td>
														<td class="uppercase p-1 text-center" width="10%">Unit Price</td>
														<td class="uppercase p-1 text-center" width="10%">Total</td>
													</tr>
                                                    <tr>
														<td colspan="7"><span class="font-bold">{{ jor_head.general_description}} </span></td>
													</tr>
                                                    <span hidden>{{ grand_totall=0 }}</span>
                                                    <span hidden>{{ cancelled_qty=0 }}</span>
													<tr class="" v-for="jld in joi_labor_details" v-if="joi_head.status!='Cancelled'">
                                                        <span hidden>{{ cancelled_qty+=(jld.status=='Cancelled') ? jld.total_cost : 0 }}</span>
                                                        <span hidden>{{ grand_totall+=jld.unit_price * jld.quantity  }}</span>
														<td :class="(jld.status=='Cancelled') ? 'border-y-none p-1 bg-red-100 print:!text-red-500 print:!bg-transparent print:hidden' : 'border-y-none p-1'" colspan="3">
                                                            <div class="flex justify-between space-x-2">
                                                                <div class="w-full">
                                                                    {{ jld.item_description }}
                                                                </div>
                                                                <a href="#" @click="cancelJOitems('no',jld.id,'labor')" class="!text-red-500 cursor-pointer po_buttons" v-if="joi_details_view.length>1 && jld.status!='Cancelled'">
                                                                    <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
                                                                </a>
                                                                <!-- <a @click="openDangerItem()" class="!text-red-500 cursor-pointer po_buttons">
                                                                    <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
                                                                </a> -->
                                                            </div>
															
														</td>
														<td :class="(jld.status=='Cancelled') ? 'border-y-none p-1 text-center bg-red-100 print:!text-red-500 print:!bg-transparent print:hidden' : 'border-y-none p-1 text-center'">
                                                            {{jld.quantity}}
                                                        </td>
														<td :class="(jld.status=='Cancelled') ? 'border-y-none p-1 text-center bg-red-100 print:!text-red-500 print:!bg-transparent print:hidden' : 'border-y-none p-1 text-center'">{{jld.uom}}</td>
														<td :class="(jld.status=='Cancelled') ? 'border-y-none p-1 text-right bg-red-100 print:!text-red-500 print:!bg-transparent print:hidden' : 'border-y-none p-1 text-right'">{{formatNumber(jld.unit_price)}} {{ jld.currency }}</td>
														<td :class="(jld.status=='Cancelled') ? 'border-y-none p-1 text-right bg-red-100 print:!text-red-500 print:!bg-transparent print:hidden' : 'border-y-none p-1 text-right'">{{formatNumber(jld.total_cost)}}</td>
													</tr>
                                                    <tr class="" v-for="jld in joi_labor_details" v-else>
                                                        <span hidden>{{ cancelled_qty+=(jld.status=='Cancelled') ? jld.total_cost : 0 }}</span>
                                                        <span hidden>{{ grand_totall+=jld.unit_price * jld.quantity  }}</span>
														<td :class="(jld.status=='Cancelled') ? 'border-y-none p-1 bg-red-100 print:!text-red-500 print:!bg-transparent ' : 'border-y-none p-1'" colspan="3">
                                                            <div class="flex justify-between space-x-2">
                                                                <div class="w-full">
                                                                    {{ jld.item_description }}
                                                                </div>
                                                                <a href="#" @click="cancelJOitems('no',jld.id,'labor')" class="!text-red-500 cursor-pointer po_buttons" v-if="joi_details_view.length>1 && jld.status!='Cancelled'">
                                                                    <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
                                                                </a>
                                                                <!-- <a @click="openDangerItem()" class="!text-red-500 cursor-pointer po_buttons">
                                                                    <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
                                                                </a> -->
                                                            </div>
															
														</td>
														<td :class="(jld.status=='Cancelled') ? 'border-y-none p-1 text-center bg-red-100 print:!text-red-500 print:!bg-transparent ' : 'border-y-none p-1 text-center'">
                                                            {{jld.quantity}}
                                                        </td>
														<td :class="(jld.status=='Cancelled') ? 'border-y-none p-1 text-center bg-red-100 print:!text-red-500 print:!bg-transparent ' : 'border-y-none p-1 text-center'">{{jld.uom}}</td>
														<td :class="(jld.status=='Cancelled') ? 'border-y-none p-1 text-right bg-red-100 print:!text-red-500 print:!bg-transparent ' : 'border-y-none p-1 text-right'">{{formatNumber(jld.unit_price)}} {{ jld.currency }}</td>
														<td :class="(jld.status=='Cancelled') ? 'border-y-none p-1 text-right bg-red-100 print:!text-red-500 print:!bg-transparent ' : 'border-y-none p-1 text-right'">{{formatNumber(jld.total_cost)}}</td>
													</tr>
													<tr class="bg-gray-100">
														<td class="p-1 text-center" width="3%">#</td>
														<td class="p-1" colspan="2">Materials:</td>
                                                        <td class="uppercase p-1 text-center" width="7%">Qty</td>
														<td class="uppercase p-1 text-center" width="7%">Unit</td>
														<td class="uppercase p-1 text-center" width="10%">Unit Price</td>
														<td class="uppercase p-1 text-center" width="10%">Total</td>
													</tr>
                                                    <span hidden>{{ grand_totalm=0 }}</span>
                                                    <span hidden>{{ cancelled_m_qty=0 }}</span>
													<tr class="" v-for="(jmd, index) in joi_material_details" v-if="joi_head.status!='Cancelled'">
                                                        <span hidden>{{ cancelled_m_qty+=(jmd.status=='Cancelled') ? jmd.total_cost : 0 }}</span>
                                                        <span hidden>{{ grand_totalm+=jmd.unit_price * jmd.quantity  }}</span>
														<td :class="(jmd.status=='Cancelled') ? 'border-y-none p-1 text-center bg-red-100 print:!text-red-500 print:!bg-transparent print:hidden' : 'border-y-none p-1 text-center'">{{ index+1 }}</td>
														<td :class="(jmd.status=='Cancelled') ? 'border-y-none p-1 bg-red-100 print:!text-red-500 print:!bg-transparent print:hidden' : 'border-y-none p-1'"  colspan="2">
                                                            <div class="flex justify-between space-x-2">
                                                                <div class="w-full">
                                                                    {{jmd.item_description}}
                                                                </div>
                                                                <!-- <a @click="openDangerItem()" class="!text-red-500 cursor-pointer po_buttons">
                                                                    <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
                                                                </a> -->
                                                                <a href="#" @click="cancelJOitems('no',jmd.id,'materials')" class="!text-red-500 cursor-pointer po_buttons" v-if="joi_material_details_view.length>1 && jmd.status!='Cancelled'">
                                                                    <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
                                                                </a>
                                                            </div>
                                                        </td>
														<td :class="(jmd.status=='Cancelled') ? 'border-y-none p-1 text-center bg-red-100 print:!text-red-500 print:!bg-transparent print:hidden' : 'border-y-none p-1 text-center'">
                                                            {{jmd.quantity}}
                                                        </td>
														<td :class="(jmd.status=='Cancelled') ? 'border-y-none p-1 text-center bg-red-100 print:!text-red-500 print:!bg-transparent print:hidden' : 'border-y-none p-1 text-center'">{{jmd.uom}}</td>
														<td :class="(jmd.status=='Cancelled') ? 'border-y-none p-1 text-right bg-red-100 print:!text-red-500 print:!bg-transparent print:hidden' : 'border-y-none p-1 text-right'">{{formatNumber(jmd.unit_price ?? 0)}} {{ jmd.currency }}</td>
														<td :class="(jmd.status=='Cancelled') ? 'border-y-none p-1 text-right bg-red-100 print:!text-red-500 print:!bg-transparent print:hidden' : 'border-y-none p-1 text-right'">{{formatNumber(jmd.total_cost ?? 0)}}</td>
													</tr>
                                                    <tr class="" v-for="(jmd, index) in joi_material_details" v-else>
                                                        <span hidden>{{ cancelled_m_qty+=(jmd.status=='Cancelled') ? jmd.total_cost : 0 }}</span>
                                                        <span hidden>{{ grand_totalm+=jmd.unit_price * jmd.quantity  }}</span>
														<td :class="(jmd.status=='Cancelled') ? 'border-y-none p-1 text-center bg-red-100 print:!text-red-500 print:!bg-transparent ' : 'border-y-none p-1 text-center'">{{ index+1 }}</td>
														<td :class="(jmd.status=='Cancelled') ? 'border-y-none p-1 bg-red-100 print:!text-red-500 print:!bg-transparent ' : 'border-y-none p-1'"  colspan="2">
                                                            <div class="flex justify-between space-x-2">
                                                                <div class="w-full">
                                                                    {{jmd.item_description}}
                                                                </div>
                                                                <!-- <a @click="openDangerItem()" class="!text-red-500 cursor-pointer po_buttons">
                                                                    <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
                                                                </a> -->
                                                                <a href="#" @click="cancelJOitems('no',jmd.id,'materials')" class="!text-red-500 cursor-pointer po_buttons" v-if="joi_material_details_view.length>1 && jmd.status!='Cancelled'">
                                                                    <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
                                                                </a>
                                                            </div>
                                                        </td>
														<td :class="(jmd.status=='Cancelled') ? 'border-y-none p-1 text-center bg-red-100 print:!text-red-500 print:!bg-transparent ' : 'border-y-none p-1 text-center'">
                                                            {{jmd.quantity}}
                                                        </td>
														<td :class="(jmd.status=='Cancelled') ? 'border-y-none p-1 text-center bg-red-100 print:!text-red-500 print:!bg-transparent ' : 'border-y-none p-1 text-center'">{{jmd.uom}}</td>
														<td :class="(jmd.status=='Cancelled') ? 'border-y-none p-1 text-right bg-red-100 print:!text-red-500 print:!bg-transparent ' : 'border-y-none p-1 text-right'">{{formatNumber(jmd.unit_price ?? 0)}} {{ jmd.currency }}</td>
														<td :class="(jmd.status=='Cancelled') ? 'border-y-none p-1 text-right bg-red-100 print:!text-red-500 print:!bg-transparent ' : 'border-y-none p-1 text-right'">{{formatNumber(jmd.total_cost ?? 0)}}</td>
													</tr>
													<tr class="">
														<td class=""></td>
														<td class=""></td>
														<td class=""></td>
														<td class=""></td>
														<td class=""></td>
														<td class=""></td>
														<td class=""></td>
													</tr>
													<tr class="">
														<td class="border-r-none align-top p-2" colspan="4" width="65%" rowspan="6"></td>
														<td class="border-l-none border-y-none p-0 text-right p-0.5 pr-1" colspan="2" >Total Labor</td>
														<td class="p-0"><input disabled type="text" class="w-full bg-white p-0.5 text-right pr-1" :value="formatNumber(grand_totall ?? 0)"></td>
													</tr>
													<tr class="">
														<td class="border-l-none border-y-none p-1 text-right" colspan="2">Total Materials</td>
														<td class="p-0"><input disabled type="text" class="w-full bg-white p-1 text-right" :value="formatNumber(grand_totalm ?? 0)"></td>
													</tr>
													
													<tr class="">
														<td class="border-l-none border-y-none p-1 text-right" colspan="2">Discount Labor</td>
														<td class="p-0"><input disabled type="text" class="w-full bg-white p-1 text-right" :value="formatNumber(joi_head.discount ?? 0)"></td>
													</tr>
													<tr class="">
														<td class="border-l-none border-y-none p-1 text-right" colspan="2">Discount Material</td>
														<td class="p-0"><input disabled type="text" class="w-full bg-white p-1 text-right" :value="formatNumber(joi_head.discount_material ?? 0)"></td>
													</tr>
                                                    <tr class="">
														<td class="border-l-none border-y-none p-1 text-right" colspan="2">VAT %</td>
														<td class="p-0">
															<div class="flex">
																<input disabled type="text" class="w-10 bg-white border-r text-center" placeholder="%" :value="formatNumber(joi_head.vat ?? 0)">
																<input disabled type="text" class="w-full bg-white p-1 text-right" :value="formatNumber(joi_head.vat_amount ?? 0)">
															</div>
														</td>
													</tr>
													<tr class="">
														<td class="border-l-none border-y-none p-1 text-right font-bold" colspan="2">GRAND TOTAL</td>

														<td class="p-1 text-right font-bold !text-sm no-print">{{formatNumber(joi_head.grand_total ?? 0)}}</td>
														<td class="p-1 text-right font-bold !text-sm print-only in-print-only" style="display: none;" v-if="joi_head.status!='Cancelled'">{{formatNumber(joi_head.grand_total - (cancelled_qty + cancelled_m_qty) ?? 0)}}</td>
                                                        <td class="p-1 text-right font-bold !text-sm print-only in-print-only" style="display: none;" v-else>{{formatNumber(joi_head.grand_total ?? 0)}}</td>
													</tr>
												</table>
											</div>
										</div>
									</div>
                                    
                                    <div class="row mt-2">
                                        <div class="col-lg-6  col-sm-6 col-md-6">
                                            <table class="table-bordsered !text-xs w-full">
                                                <tr>
                                                    <td class="p-1 uppercase" colspan="3">Terms and Conditions</td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top text-center" width="4%">1.</td>
                                                    <td class="align-top px-1" colspan="2">PO No. must appear on all copies of Invoices, Delivery Receipt & Correspondences submitted.</td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top text-center" width="4%">2.</td>
                                                    <td class="align-top px-1" colspan="2">Sub-standard items shall be returned to supplier @ no cost to CENPRI.</td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top text-center" width="4%">3.</td>
                                                    <td class="align-top pl-1" colspan="2">
                                                        <div class="flex justify-start space-x-1">
                                                            <span >Price is </span>
                                                            <span v-if="joi_head.vat_in_ex==1">Inclusive of VAT</span>
                                                            <span v-else-if="joi_head.vat_in_ex==2">Exclusive of VAT</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr v-for="(jt,indexte) in joi_terms">
                                                    <td class="align-top text-center" width="4%">{{ indexte + 4 }}.</td>
                                                    <td class="align-top  pl-1" colspan="2">
                                                        <div class="flex justify-start space-x-1">
                                                            <span>{{jt.terms}}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-lg-6  col-sm-6 col-md-6">
                                            <table class="table-bordsered !text-xs w-full">
                                                <tr>
                                                    <td class="p-1 uppercase" colspan="3">Other Instructions</td>
                                                </tr>
                                                <tr v-for="(pi,indexin) in joi_instructions">
                                                    <td class="px-1" colspan="2">{{pi.instructions}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <table class="w-full ">
                                                <tr>
                                                    <td></td>
                                                    <td width="12%" class="font-bold text-sm text-gray-500"> Total Project Cost:</td>
                                                    <td  width="20%" class="border-b border-gray-400 px-4 font-bold text-base text-gray-500"> 
                                                        <div class="flex justify-between  text-lg">
                                                            <span></span>
                                                            <span class="no-print">{{formatNumber(joi_head.grand_total ?? 0)}}</span>
                                                            <span class="print-only in-print-only" style="display: none;" v-if="joi_head.status!='Cancelled'">{{formatNumber(joi_head.grand_total - (cancelled_qty + cancelled_m_qty) ?? 0)}}</span>
                                                            <span class="print-only in-print-only" style="display: none;" v-else>{{formatNumber(joi_head.grand_total ?? 0)}}</span>
                                                        </div>
                                                    </td>
                                                    <td width="14%"></td>
                                                    <td width="8%" class="font-bold text-sm text-gray-500">Conforme:</td>
                                                    <td  width="30%" class="border-b border-gray-400 px-4"><input type="text" class="w-full text-center text-sm capitalize" v-model="joi_head.conforme" readonly></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-xs text-center">Contractor's Signature Over Printed Name</td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row mt-4 mb-4">
                                        <div class="col-lg-12">
                                            <table class="w-full text-xs">
                                                <tr>
                                                    <td class="text-center" width="20%">Prepared by</td>
                                                    <td width="2%"></td>
                                                    <td class="text-center" width="20%">Reviewed/Checked by</td>
                                                    <td width="2%"></td>
                                                    <td class="text-center" width="20%">Recommended by</td>
                                                    <td width="2%"></td>
                                                    <td class="text-center" width="20%">Approved by</td>
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
                                                    <td class="text-center p-1">{{prepared_by}}</td>
                                                    <td></td>
                                                    <td class="text-center p-1">{{checked_by}}</td>
                                                    <td></td>
                                                    <td class="text-center p-1">{{recommended_by}}</td>
                                                    <td></td>
                                                    <td class="text-center p-1">{{approved_by}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center"><br><br></td>
                                                    <td></td>
                                                    <td class="text-center"></td>
                                                    <td></td>
                                                    <td class="text-center"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right" colspan="2">Work Completion Verified by: </td>
                                                    <td class="text-center border-b" colspan="3"></td>
                                                    <td class="text-center"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right" colspan="2"></td>
                                                    <td class="text-center p-1" colspan="3">Signature over Printed Name</td>
                                                    <td class="text-center"></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <hr	class="border-dashed">
                                    <!-- <div class="po_buttons text-xs">
                                        <span class="w-full block">Internal Comment:</span>
                                        <span class="w-full block">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. </span>
                                        <hr	class="border-dashed">
                                    </div> -->
                                    
                                    <div class="row my-2 po_buttons" > 
                                        <div class="col-lg-12 col-md-12">
                                            <div class="flex justify-between space-x-2">
                                                <div class="flex justify-start space-x-1">
                                                    <button type="button" class="btn btn-danger w-36"  @click="cancelAllJO('no')" v-if="joi_head.status!='Cancelled'">Cancel JOI</button>
                                                    <div class="flex justify-between"  v-if="joi_head.status!='Cancelled'">
                                                        <a :href="'/job_issue/edit/'+props.id" class="btn btn-info w-26 !rounded-r-none">Revise JOI</a>
                                                        <button class="btn btn-info !text-white px-2 !pt-[0px] pb-0 !rounded-l-none" @click="openDrawerRevise(props.id)">
                                                            <Bars4Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></Bars4Icon >
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="flex justify-between space-x-1">
                                                    <a href="/job_issue/print_ar" class="btn btn-warning text-white">Print AC</a>
                                                    <a href="/job_issue/print_coc" class="btn btn-warning text-white">Print COC</a>
                                                    <div class="flex justify-between">
                                                        <!-- <a href="/job_disburse/new" class="btn btn-warning !text-white  !rounded-r-none">Print RFD</a> -->
                                                        <a href="/job_disburse/new2" class="btn btn-warning !text-white  !rounded-r-none">Print RFD 2</a>
                                                        <button class="btn btn-warning !text-white px-2 !pt-[0px] pb-0 !rounded-l-none" @click="openDrawerRFD()">
                                                            <Bars4Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></Bars4Icon >
                                                        </button>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <a :href="'/job_dr/new/'+props.id" class="btn btn-warning !text-white w-26 !rounded-r-none">Print DR</a>
                                                        <button class="btn btn-warning !text-white px-2 !pt-[0px] pb-0 !rounded-l-none" @click="openDrawerDR()">
                                                            <Bars4Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></Bars4Icon >
                                                        </button>
                                                    </div>
                                                    <button type="button" class="btn btn-primary w-36" @click="printDiv()">Print JOI</button>
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
        <Transition
            enter-active-class="transition ease-out duration-500"
            enter-from-class="opacity-0 "
            enter-to-class="opacity-100 "
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 "
            leave-to-class="opacity-0 scale-95"
        >
            <div class="modal pt-0 px-0 !bg-transparent" :class="{ show:drawer_revise }">
                <div @click="closeModal" class="w-full h-screen fixed bg-transparent"></div>
                <div class="modal__content w-3/12 float-right min-h-[690px]">
                    <div class="row mb-3">
                        <div class="col-lg-12 flex justify-between">
                            <span class="font-bold ">Revise List</span>
                            <a href="#" class="text-gray-600" @click="closeModal">
                                <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
                            </a>
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="modal_s_items ">
                        <div class="" v-for="jhv in joi_head_rev">
                            <a :href="'/job_issue/view_revised/'+jhv.id" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">{{ jhv.joi_no }}{{ (jhv.revision_no!=0 && jhv.revision_no!='' && jhv.revision_no!=null) ? '.r'+jhv.revision_no : '' }}</a>
                        </div>
                        <div>
                            <a :href="'/job_issue/view/'+props.id"  @click="closeModal" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">{{ joi_head.joi_no }}{{ (joi_head.revision_no!=0 && joi_head.revision_no!='' && joi_head.revision_no!=null) ? '.r'+joi_head.revision_no : '' }} (Current)</a>
                        </div>
                    </div> 
                </div>
            </div>
        </Transition>
        <Transition
            enter-active-class="transition ease-out duration-500"
            enter-from-class="opacity-0 "
            enter-to-class="opacity-100 "
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 "
            leave-to-class="opacity-0 scale-95"
        >
            <div class="modal pt-0 px-0 !bg-transparent" :class="{ show:drawer_dr }">
                <div @click="closeModal" class="w-full h-screen fixed bg-transparent"></div>
                <div class="modal__content w-3/12 float-right min-h-[690px]">
                    <div class="row mb-3">
                        <div class="col-lg-12 flex justify-between">
                            <span class="font-bold ">DR List</span>
                            <a href="#" class="text-gray-600" @click="closeModal">
                                <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
                            </a>
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="modal_s_items ">
                        <div class="" v-for="jdr in joi_dr">
                            <a :href="'/job_dr/view/'+jdr.id" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">{{ jdr.dr_no }}</a>
                        </div>
                    </div> 
                </div>
            </div>
        </Transition>
        <Transition
            enter-active-class="transition ease-out duration-500"
            enter-from-class="opacity-0 "
            enter-to-class="opacity-100 "
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 "
            leave-to-class="opacity-0 scale-95"
        >
            <div class="modal pt-0 px-0 !bg-transparent !rounded-l-none" :class="{ show:drawer_rfd }">
                <div @click="closeModal" class="w-full h-screen fixed bg-tranparent"></div>
                <div class="modal__content w-3/12 float-right min-h-[690px]">
                    <div class="row mb-3">
                        <div class="col-lg-12 flex justify-between">
                            <span class="font-bold ">RFD List</span>
                            <a href="#" class="text-gray-600" @click="closeModal">
                                <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
                            </a>
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="modal_s_items ">
                        <div class="">
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">RFD-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">RFD-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">RFD-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">RFD-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">RFD-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">RFD-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">RFD-88270-7662</a>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert_item }">
				<div @click="closeAlert()" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
				<div class="modal__content !shadow-2xl !rounded-3xl !my-44 w-[500px] p-0">
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
									<h5 class="leading-tight">
										Are you sure you want to cancel this scope/item?<br>
										If yes, please state your reason.
									</h5>
									<label>Cancel Reason: </label>
									<textarea name="" id="cancel_check" class="form-control !border" rows="3" v-model="cancel_reason"></textarea>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-2"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full" @click="closeAlert()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="cancelJOitems('yes',jo_details_id_view,selection)">Yes</button>
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
			<div class="modal p-0 !bg-transparent" :class="{ show:dangerAlert }">
				<div @click="closeAlert()" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h5 class="leading-tight">
										Are you sure you want to cancel this PO?<br>
										If yes, please state your reason.
									</h5>
									<label>Cancel Reason: </label>
									<textarea name="" id="cancel_all_check" class="form-control !border" rows="3" v-model="cancel_all_reason"></textarea>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-2"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full" @click="closeAlert()">No</button>
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="cancelAllJO('yes')">Yes</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
</template>