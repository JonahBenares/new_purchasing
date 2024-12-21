<script setup>
	import navigation from '@/layouts/navigation.vue';
	import printheader from '@/layouts/print_header.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, Bars4Icon} from '@heroicons/vue/24/solid'
    import { reactive, ref, onMounted } from "vue"
    import { useRouter } from "vue-router"
    import moment from 'moment'
	const vendor =  ref();
	const preview =  ref();
	const error =  ref('');
	const success =  ref('');
    const successAlert = ref(false)
    const dangerAlert = ref(false)
	const dangerAlert_item = ref(false)
	const drawer_dr = ref(false)
	const drawer_rfd = ref(false)
	const drawer_revise = ref(false)
	const hideModal = ref(true)
	const hideAlert = ref(true)
	const po_head = ref([])
	const pr_head = ref([])
	const po_vendor = ref([])
	const po_details = ref([])
	const po_terms = ref([])
	const po_instructions = ref([])
    const prepared_by =  ref('');
    const checked_by =  ref('');
    const recommended_by =  ref('');
    const approved_by =  ref('');
    const cancelled_by =  ref('');
    let po_details_id_view=ref("")
    let cancel_reason=ref("")
    let cancel_all_reason=ref("")

    const po_head_rev = ref([])
	const po_details_rev = ref([])
	const po_terms_rev = ref([])
	const po_instructions_rev = ref([])
    
    const props = defineProps({
		id:{
			type:String,
			default:''
		}
	})
    onMounted(async () => {
		poView()
	})
    const formatNumber = (number) => {
        return number.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
    const poView = async () => {
		let response = await axios.get("/api/view_revision_data/"+props.id);
		po_head.value = response.data.po_head_rev;
		pr_head.value = response.data.pr_head;
		po_vendor.value = response.data.po_vendor;
		po_details.value = response.data.po_details_rev;
		po_terms.value = response.data.po_terms_rev;
		po_instructions.value = response.data.po_instructions_rev;
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
        successAlert.value = !hideAlert.value
		dangerAlert_item.value = !hideAlert.value
		dangerAlert.value = !hideAlert.value
        document.getElementById('cancel_check').placeholder=""
        document.getElementById('cancel_check').style.backgroundColor = '#FFFFFF';
        cancel_reason.value=''
        document.getElementById('cancel_all_check').placeholder=""
        document.getElementById('cancel_all_check').style.backgroundColor = '#FFFFFF';
        cancel_all_reason.value=''
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

    const internalComment = () => {
		const formData= new FormData()
        formData.append('internal_comment', po_head.value.internal_comment)
        var api='insert_internalcomment/'+props.id;
		axios.post('/api/'+api,formData).then(function () {
		}, function (err) {
			error.value = err.response.data.message;
		});
	}

    const cancelPOitems = (option, id) => {
		if(option=='yes'){
			if(cancel_reason.value!=''){
				const formData= new FormData()
				formData.append('cancel_reason', cancel_reason.value)
				axios.post(`/api/cancel_po_items/`+id,formData).then(function (response) {
                    dangerAlert_item.value = !hideAlert.value
                    success.value='Successfully cancelled item!'
                    successAlert.value = !successAlert.value
                    cancel_reason.value=''
                    document.getElementById('cancel_check').placeholder=""
                    document.getElementById('cancel_check').style.backgroundColor = '#FFFFFF';
                    poView()
                    setTimeout(() => {
                        closeAlert()
                    }, 2000);
				})
			}else{
				document.getElementById('cancel_check').placeholder="Cancel Reason must not be empty!"
				document.getElementById('cancel_check').style.backgroundColor = '#FAA0A0';
			}
		}else{
			po_details_id_view.value=id
			dangerAlert_item.value = !dangerAlert_item.value
		}
	}

    const cancelAllPO = (option) => {
		if(option=='yes'){
			if(cancel_all_reason.value!=''){
				const formData= new FormData()
				formData.append('cancel_all_reason', cancel_all_reason.value)
				axios.post(`/api/cancel_all_po/`+props.id,formData).then(function (response) {
                    dangerAlert.value = !hideAlert.value
                    success.value='Successfully cancelled PO!'
                    successAlert.value = !successAlert.value
                    cancel_all_reason.value=''
                    document.getElementById('cancel_all_check').placeholder=""
                    document.getElementById('cancel_all_check').style.backgroundColor = '#FFFFFF';
                    poView()
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
<template>
	<navigation>
		<div class="row" id="breadcrumbs">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Purchase Order <small>Revised View</small></h3>
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600" v-if="po_head.method == 'DPO'">Direct PO <small>Revised View</small></h3>
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600" v-if="po_head.method == 'RPO'">Repeat Order <small>Revised View</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/pur_po">Purchase Order</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Revised View</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
		<div class="row">
			<div class="col-12 grid-margin stretch-card">
				<div class="card">
                    <div class="py-2 px-2 bg-red-500" v-if="po_head.status=='Cancelled'">
						<span class="font-bold text-white">CANCELLED || Cancelled By: {{ cancelled_by }}  || Cancelled Date: {{moment().format('MMM. DD,YYYY')}} </span>
					</div>
                    <div class="card-body">
                        <hr class="border-dashed mt-0">
                        <div class="pt-1" id="printable">
                            <div class="hidden print:block">
								<printheader ></printheader>
								<div class="flex justify-center mt-1">
									<span class="uppercase">Purchase Order</span>
                                    <!-- <span class="uppercase" v-if="po_head.method == 'DPO'">Direct</span>
									<span class="uppercase" v-if="po_head.method == 'RPO'">Repeat Order</span> -->
								</div>
								<hr class="print:block border-dashed mt-2">
							</div>
                            <div class="print:block hidden print:flex print:justify-center h-full" v-if="po_head.status=='Cancelled'">
								<img src="../../../images/bg_cancelled.png" alt="" class="absolute h-[420px] align-center opacity-100">
							</div>
                            <div>
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <span class="text-sm text-gray-700 font-bold pr-1">PO No: </span>
                                        <span class="text-sm text-gray-700">{{po_head.po_no}}{{ (po_head.revision_no!=0) ? '.r'+po_head.revision_no : '' }} </span>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Date: </span>
                                        <span class="text-sm text-gray-700">{{moment(po_head.po_date).format('MMMM DD,YYYY')}}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Supplier: </span>
                                        <span class="text-sm text-gray-700">{{po_head.vendor_name}} ({{ po_vendor.identifier }})</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Address:</span>
                                        <span class="text-sm text-gray-700">{{ po_vendor.address }}</span>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Telephone: </span>
                                        <span class="text-sm text-gray-700">{{po_vendor.phone }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Contact Person: </span>
                                        <span class="text-sm text-gray-700">{{po_vendor.contact_person}}</span>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4">
                                        <span class="text-sm text-gray-700 font-bold pr-1">Telefax: </span>
                                        <span class="text-sm text-gray-700">{{po_vendor.fax}}</span>
                                    </div>
                                </div>
                                
                                <div class="" >
                                    <br>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="border-2">
                                                <table class="table-bordered w-full text-xs bg-transparent">
                                                    <tr class="bg-gray-100 print:bg-transparent">
                                                        <td class="uppercase p-1 text-center" width="3%">#</td>
                                                        <td class="uppercase p-1 text-center" width="7%">Qty</td>
                                                        <td class="uppercase p-1 text-center" width="7%">Unit</td>
                                                        <td class="uppercase p-1" colspan="2">Description</td>
                                                        <td class="uppercase p-1 text-center" width="12%">Unit Price</td>
                                                        <td class="uppercase p-1 text-center" width="12%">Total</td>
                                                    </tr>
                                                    <tr class="" v-for="(pd,indeex) in po_details">
                                                        <td :class="(po_head.status=='Cancelled') ? 'border-y-none p-1 text-center bg-red-100 print:!text-red-500 print:!bg-transparent' : 'border-y-none p-1 text-center'">{{indeex+1}}</td>
                                                        <td :class="(po_head.status=='Cancelled') ? 'border-y-none p-1 text-center bg-red-100 print:!text-red-500 print:!bg-transparent' : 'border-y-none p-1 text-center'">{{pd.quantity}}</td>
                                                        <td :class="(po_head.status=='Cancelled') ? 'border-y-none p-1 text-center bg-red-100 print:!text-red-500 print:!bg-transparent' : 'border-y-none p-1 text-center'">{{pd.uom}}</td>
                                                        <td :class=" (po_head.status=='Cancelled') ? 'border-y-none p-1 bg-red-100 print:!text-red-500 print:!bg-transparent' : 'border-y-none p-1'" colspan="2">
                                                            <div class="flex justify-between space-x-1">
                                                                <span class="w-full">{{pd.item_description}}</span>
                                                            </div>
                                                        </td>
                                                        <td :class="(po_head.status=='Cancelled') ? 'border-y-none p-1 text-right bg-red-100 print:!text-red-500 print:!bg-transparent' : 'border-y-none p-1 text-right'">{{ formatNumber(pd.unit_price) }} {{pd.currency}}</td>
                                                        <td :class="(po_head.status=='Cancelled') ? 'border-y-none p-1 text-right bg-red-100 print:!text-red-500 print:!bg-transparent' : 'border-y-none p-1 text-right'">{{ formatNumber(pd.total_cost) }}</td>
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
                                                        <td class="border-r-none align-top p-2" colspan="4" width="65%" rowspan="5">
                                                            <p class="m-0 mb-1 text-xs leading-none"><span class="mr-2 uppercase">PR Number:</span>{{po_head.pr_no}}</p>
                                                            <p class="m-0 mb-1 text-xs leading-none"><span class="mr-2 uppercase">Requestor:</span>{{pr_head.requestor}}</p>
                                                            <p class="m-0 mb-1 text-xs leading-none"><span class="mr-2 uppercase">End-use:</span>{{pr_head.enduse}}</p>
                                                            <p class="m-0 mb-1 text-xs leading-none"><span class="mr-2 uppercase">Purpose:</span>{{pr_head.purpose}}</p>
                                                        </td>
                                                        <td class="border-l-none border-y-none p-0 text-right p-0.5 pr-1" colspan="2" >Shipping Cost</td>
                                                        <td class="p-1 text-right ">{{ formatNumber(po_head.shipping_cost ?? 0) }}</td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="border-l-none border-y-none p-1 text-right" colspan="2">Packing and Handling Fee</td>
                                                        <td class="p-1 text-right ">{{ formatNumber(po_head.handling_fee ?? 0) }}</td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="border-l-none border-y-none p-1 text-right" colspan="2">Less: Discount</td>
                                                        <td class="p-1 text-right ">{{formatNumber(po_head.discount ?? 0 ) }}</td>
                                                    </tr>
                                                    <tr class="" v-if="po_head.vat==1">
                                                        <td class="border-l-none border-y-none p-1 text-right" colspan="2">VAT</td>
                                                        <td class="p-0">
                                                            <div class="flex">
                                                                <input type="text" class="w-10 bg-white border-r text-center" disabled :value="po_head.vat_percent+'%'">
                                                                <input type="text" class="w-10 bg-white border-r text-center" disabled value="12" hidden>
                                                                <input type="text" class="w-full bg-white p-1 text-right" disabled :value="formatNumber(po_head.vat_amount ?? 0)">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr class="" v-else-if="po_head.vat==0 || po_head.vat==2">
                                                        <td class="border-l-none border-y-none p-1 text-right" colspan="2">NON-VAT</td>
                                                        <td class="p-0">
                                                            <div class="flex">
                                                                <input type="text" class="w-full bg-white p-1 text-right" disabled value="0.00">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr class="">
                                                        <td class="border-l-none border-y-none p-1 text-right font-bold" colspan="2">GRAND TOTAL</td>
                                                        <td class="p-1 text-right font-bold !text-sm">{{ formatNumber(po_head.grand_total ?? 0) }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-2">
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <table class="table-bordsered text-xs w-full">
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
                                                            <span v-if="po_head.vat_in_ex==1">Inclusive of VAT</span>
                                                            <span v-else-if="po_head.vat_in_ex==2">Exclusive of VAT</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr v-for="(pt,index) in po_terms">
                                                    <td class="align-top text-center" width="4%">{{ index + 4 }}.</td>
                                                    <td class="align-top  pl-1" colspan="2">
                                                        <div class="flex justify-start space-x-1">
                                                            <span>{{pt.terms}}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6" v-if="po_instructions.length!=0">
                                            <table class="table-bordsered text-xs w-full">
                                                <tr>
                                                    <td class="p-1 uppercase" colspan="3">Other Instructions</td>
                                                </tr>
                                                <tr v-for="(pi,indexin) in po_instructions">
                                                    <td class="px-1" colspan="2">{{pi.instructions}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
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
                                                    <td class="text-right" colspan="2">Conforme: </td>
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
                                    <div class="po_buttons text-xs" v-if="po_head.status!='Cancelled'">
                                        <span class="w-full block">Internal Comment:</span>
                                        {{ (po_head.internal_comment!='null') ? po_head.internal_comment : '' }}
                                        <!-- <textarea class="bg-yellow-50" @keyup="internalComment()" v-model="po_head.internal_comment" rows="5" placeholder="Write internal comment here..." style="width:100%!important"></textarea> -->
                                        <hr	class="border-dashed">
                                    </div>
                                    <div class="row my-2 po_buttons"> 
                                        <div class="col-lg-12 col-md-12">
                                            <div class="flex justify-between space-x-2">
                                                <div class="flex justify-between space-x-1">
                                                    <!-- <button type="button" class="btn btn-danger w-36"  @click="cancelAllPO('no')" v-if="po_head.status!='Cancelled'">Cancel PO</button>
                                                    <div class="flex justify-between" v-if="po_head.status!='Cancelled'">
                                                        <a :href="'/pur_po/edit/'+props.id" type="button" class="btn btn-info w-26 !rounded-r-none">Revise PO</a>
                                                        <button class="btn btn-info !text-white px-2 !pt-[0px] pb-0 !rounded-l-none" @click="openDrawerRevise()">
                                                            <Bars4Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></Bars4Icon >
                                                        </button>
                                                    </div> -->
                                                    
                                                </div>
                                                <div class="flex justify-between space-x-1">
                                                    <!-- <div class="flex justify-between">
                                                        <a href="/pur_disburse/new" class="btn btn-warning !text-white w-26 !rounded-r-none">Print RFD</a>
                                                        <button class="btn btn-warning !text-white px-2 !pt-[0px] pb-0 !rounded-l-none" @click="openDrawerRFD()">
                                                            <Bars4Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></Bars4Icon >
                                                        </button>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <a href="/pur_dr/new" class="btn btn-warning !text-white w-26 !rounded-r-none">Print DR</a>
                                                        <button class="btn btn-warning !text-white px-2 !pt-[0px] pb-0 !rounded-l-none" @click="openDrawerDR()">
                                                            <Bars4Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></Bars4Icon >
                                                        </button>
                                                    </div> -->
                                                    <button type="button" class="btn btn-primary w-36"  @click="printDiv()">Print PO</button>
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
									<h5 class="leading-tight">{{ success }}</h5>
								</div>
							</div>
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
            <div class="modal pt-0 px-0 !bg-transparent" :class="{ show:drawer_revise }">
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
                        <div class="">
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">PO-88270-7662 (Main)</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">PO-88270-7662.r1</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">PO-88270-7662.r2</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">PO-88270-7662.r3</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">PO-88270-7662.r4</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">PO-88270-7662.r5</a>
                            <a href="#"  @click="closeModal" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">PO-88270-7662.r6 (Current)</a>
                        </div>
                        <!-- <div>
                            <p class="text-center text-sm">No Data</p>
                        </div> -->
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
                        <div class="">
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">DR-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">DR-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">DR-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">DR-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">DR-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">DR-88270-7662</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">DR-88270-7662</a>
                        </div>
                        <!-- <div>
                            <p class="text-center text-sm">No Data</p>
                        </div> -->
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
                        <!-- <div>
                            <p class="text-center text-sm">No Data</p>
                        </div> -->
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
										Are you sure you want to cancel this item?<br>
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
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="cancelPOitems('yes',po_details_id_view)">Yes</button>
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
									<button class="btn btn-danger btn-sm !rounded-full w-full" @click="cancelAllPO('yes')">Yes</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
</template>