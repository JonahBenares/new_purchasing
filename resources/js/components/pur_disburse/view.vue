<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, CheckIcon} from '@heroicons/vue/24/solid';
    import { reactive, ref, onMounted } from "vue"
    import { useRouter } from "vue-router"
	const preview =  ref();
    const pr_head = ref([]);
    const po_head=ref([]);
    const po_details = ref([])
    const vendor = ref([])
	const rfd_head =  ref([]);
	const rfd_payments =  ref([]);
    // let formatter=ref('');
    const prepared_by=ref('');
	const showAddVendor = ref(false)
	const showPreview = ref(false)
	const hideModal = ref(true)
    const formatter = new Intl.NumberFormat('en-US', { 
        minimumFractionDigits: 4 
    });
    const props = defineProps({
		id:{
			type:String,
			default:''
		}
	});
	onMounted(async () => {
		POviewRFD()
	})
    const POviewRFD = async () => {
		let response = await axios.get("/api/rfd_displayview/"+props.id);
        pr_head.value = response.data.pr_head;
        po_head.value = response.data.po_head;
        po_details.value = response.data.po_details;
        vendor.value = response.data.vendor;
        rfd_head.value=response.data.rfd_head;
        rfd_payments.value=response.data.rfd_payments;
        prepared_by.value=response.data.prepared_by;
	}

	const openAddVendor = () => {
		showAddVendor.value = !showAddVendor.value
	}
	const openPreview = () => {
		showPreview.value = !showPreview.value
	}
	const closeModal = () => {
		showAddVendor.value = !hideModal.value
		showPreview.value = !hideModal.value
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
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Request for Disbursement <small>View</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/pur_disburse">Request for Disbursement</a></li>
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
                        <div class="" >
                            <table class="w-full text-sm table-borsdered">
                                <tr>
                                    <td class="px-1 font-bold" width="10%">Company:</td>
                                    <td class="p-0" width="56%"><input type="text" class="w-full px-1 border-b bg-white" v-model="rfd_head.company" disabled></td>
                                    <td class="px-1 font-bold pl-4" width="12%">APV/RFD No.:</td>
                                    <td class="p-0" width="25%"><input type="text" class="w-full px-1 border-b bg-white" v-model="rfd_head.apv_no" disabled></td>
                                </tr>
                                <tr>
                                    <td class="px-1 font-bold">Pay To:</td>
                                    <td class="p-0"><input type="text" class="w-full px-1 border-b bg-white" v-model="rfd_head.pay_to" disabled></td>
                                    <td class="px-1 font-bold pl-4">Date:</td>
                                    <td class="p-0"><input type="text" class="w-full px-1 border-b" v-model="rfd_head.rfd_date" disabled></td>
                                </tr>
                                <tr>
                                    <td class="px-1 font-bold">Check Name:</td>
                                    <td class="p-0"><input type="text" class="w-full px-1 border-b bg-white" v-model="rfd_head.check_name" disabled></td>
                                    <td class="px-1 font-bold pl-4">Due Date:</td>
                                    <td class="p-0"><input type="text" class="w-full px-1 border-b" v-model="rfd_head.due_date" disabled></td>
                                </tr>
                                <tr>
                                    <td class="p-1" colspan="2">
                                        <div class="flex justify-start space-x-10">
                                            <div class="flex justify-between space-x-2 ml-5">
                                                <span class="border-b w-5"></span>
                                                <span class="font-bold">Check</span>
                                                <span class="border-b w-5 text-green-600" v-if="rfd_head.mode==1">
                                                    <CheckIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></CheckIcon>
                                                </span>
                                                <span class="font-bold">Cash</span>
                                                <span class="border-b w-5 text-green-600" v-if="rfd_head.mode==2">
                                                    <CheckIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></CheckIcon>
                                                </span>
                                            </div>
                                            <div class="flex justify-between space-x-1 w-full">
                                                <span class="font-bold w-20">Bank No.:</span>
                                                <input type="text" class="w-full px-1 border-b bg-white" v-model="rfd_head.bank_no" disabled>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-1 font-bold pl-4">Check Due:</td>
                                    <td class="p-0"><input type="text"  class="w-full px-1 border-b bg-white" v-model="rfd_head.check_due" disabled></td>
                                </tr>
                            </table>
                            <br>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="border-2">
                                        <table class="table-bordered w-full text-xs">
                                            <tr class="bg-gray-100">
                                                <td class="uppercase p-1 text-center" colspan="6">Explanation</td>
                                                <td class="uppercase p-1 text-center" width="12%">Total</td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-y-none p-1 text-left" colspan="6">
                                                    <span>Payment for:</span>
                                                </td>
                                                <td class="border-y-none p-1 text-right"></td>
                                            </tr>
                                            <tr class="" v-for="pd in po_details">
                                                <td class="border-y-none p-1 text-left" colspan="6">
                                                    {{ pd.quantity }} {{pd.uom}} {{pd.item_description}}, @ {{formatter.format(pd.unit_price)}} per {{pd.uom}}
                                                </td>
                                                <td class="border-y-none p-1 text-center">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>{{formatter.format(pd.total_cost)}}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-y-none" colspan="6"><br></td>
                                                <td class="border-y-none"></td>
                                            </tr>
                                            <tr class="">
                                                <td class="" colspan="6"></td>
                                                <td class=""></td>
                                            </tr>
                                            <tr class="">
                                                <span hidden>{{ rowspan=9+rfd_payments.length }}</span>
                                                <td class="border-r-none align-top p-2" colspan="4" width="65%" :rowspan="rowspan">
                                                    <p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Requestor:</span>{{pr_head.requestor}}</p>
                                                    <p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">End-use:</span>{{pr_head.enduse}}</p>
                                                    <p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Purpose:</span>{{pr_head.purpose}}</p>
                                                    <br>
                                                    <p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">PO Number:</span>{{po_head.po_no}}</p>
                                                </td>
                                                <!-- <td class="border-l-none border-y-none p-0 text-right p-0.5 pr-1" colspan="2" >Shipping Cost</td>
                                                <td class="p-1 border-y-none">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>{{po_head.shipping_cost}}</span>
                                                    </div>
                                                </td> -->
                                            </tr>
                                            <tr v-for="(pl,index) in rfd_payments">
                                                <td class="border-l-none border-y-none p-1 text-right" colspan="2">{{pl.payment_description}}</td>
                                                <td class="p-0 align-top" width="1">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span><input type="text" min="0" @keypress="isNumber($event)" class="w-full text-right p-1" id="check_amount" v-model="pl.payment_amount" placeholder="Payment Amount" readonly></span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right" colspan="2">Shipping Cost</td>
                                                <td class="p-1 border-y-none">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>{{formatter.format(po_head.shipping_cost)}}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right" colspan="2">Packing and Handling Fee</td>
                                                <td class="p-1 border-y-none">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>{{ formatter.format(po_head.handling_fee) }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right" colspan="2">Less: Discount</td>
                                                <td class="p-1 border-y-none">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>{{ formatter.format(po_head.discount) }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right font-bold" colspan="2">Sub Total</td>
                                                <td class="p-1 border-t">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>{{ formatter.format(rfd_head.sub_total) }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right" colspan="2">Less: {{vendor.ewt}}% EWT</td>
                                                <td class="p-1 border-y-none">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>{{formatter.format(rfd_head.ewt_amount)}}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right" colspan="2">Vatable</td>
                                                <td class="p-1 border-y-none">
                                                    <!-- <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>500.00</span>
                                                    </div> -->
                                                </td>
                                            </tr>
                                            <!-- <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right font-bold " colspan="2">Total Amount Due</td>
                                                <td class="p-1 text-right font-bold !text-sm">{{formatter.format(rfd_head.grand_total)}}</td>
                                            </tr> -->
                                            <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right font-bold " colspan="2">Total Amount Due</td>
                                                <td class="p-1 text-right font-bold !text-sm">{{ formatter.format(rfd_head.balance) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="8">
                                                    <div class="flex justify-start space-x-1">
                                                        <span class="p-1">Notes:</span>
                                                        <span class="p-1">{{rfd_head.notes}}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <table class="w-full text-xs">
                                <tr>
                                    <td class="" width="30%">Prepared by</td>
                                    <td width="5%"></td>
                                    <td class="" width="30%">Checked by</td>
                                    <td width="5%"></td>
                                    <td class="" width="30%">Noted by</td>
                                </tr>
                                <tr>
                                    <td class="text-center border-b"><br></td>
                                    <td></td>
                                    <td class="text-center border-b"></td>
                                    <td></td>
                                    <td class="text-center border-b"></td>
                                </tr>
                                <tr>
                                    <td class="text-center p-1">{{prepared_by}}</td>
                                    <td></td>
                                    <td class="text-center p-1">{{rfd_head.checked_by_name}}</td>
                                    <td></td>
                                    <td class="text-center p-1">{{rfd_head.noted_by_name}}</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><br><br></td>
                                    <td></td>
                                    <td class="text-center"></td>
                                    <td></td>
                                    <td class="text-center"></td>
                                </tr>
                                <tr>
                                    <td class="" width="30%">Endorsed by</td>
                                    <td width="5%"></td>
                                    <td class="" width="30%">Approved by</td>
                                    <td width="5%"></td>
                                    <td class="" width="30%">Payment Received by</td>
                                </tr>
                                <tr>
                                    <td class="text-center border-b"><br></td>
                                    <td></td>
                                    <td class="text-center border-b"></td>
                                    <td></td>
                                    <td class="text-center border-b"></td>
                                </tr>
                                <tr>
                                    <td class="text-center p-1">{{rfd_head.endorsed_by_name}}</td>
                                    <td></td>
                                    <td class="text-center p-1">{{rfd_head.approved_by_name}}</td>
                                    <td></td>
                                    <td class="text-center p-1">{{rfd_head.received_by_name}}</td>
                                </tr>
                            </table>
                            <hr	class="border-dashed">
                            <div class="row my-2 po_buttons"> 
                                <div class="col-lg-12 col-md-12">
                                    <div class="flex justify-center space-x-2">
                                        <div class="flex justify-between space-x-1">
                                            <button class="btn btn-primary w-36"  @click="printDiv()">Print</button>
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