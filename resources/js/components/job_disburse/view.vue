<script setup>
	import navigation from '@/layouts/navigation.vue';
    import printheader from '@/layouts/print_header.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, CheckIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref, onMounted } from "vue"
    import { useRouter } from "vue-router"
	const preview =  ref();
    const jor_head = ref([]);
    const joi_head=ref([]);
    const joi_labor = ref([])
    const joi_material = ref([])
    const vendor = ref([])
	const rfd_head =  ref([]);
	const rfd_payments =  ref([]);
    const prepared_by=ref('');
	const showAddVendor = ref(false)
	const showPreview = ref(false)
	const hideModal = ref(true)
    const formatter = new Intl.NumberFormat('en-US', { 
        minimumFractionDigits: 4 
    });
    let less_labor=ref(0);
    let less_material=ref(0);
    const props = defineProps({
		id:{
			type:String,
			default:''
		}
	});
	onMounted(async () => {
		JOIviewRFD()
	})
    const JOIviewRFD = async () => {
		let response = await axios.get("/api/rfd_joi_displayview/"+props.id);
        jor_head.value = response.data.jor_head;
        joi_head.value = response.data.joi_head;
        joi_labor.value = response.data.joi_labor;
        joi_material.value = response.data.joi_material;
        vendor.value = response.data.vendor;
        rfd_head.value=response.data.rfd_head;
        rfd_payments.value=response.data.rfd_payments;
        prepared_by.value=response.data.prepared_by;
        var percent=vendor.value.ewt/100
        var percent_material=1/100
        var total=0;
        var totalm=0;
        joi_labor.value.forEach(function (val, index, theArray) {
            total += parseFloat(val.total_cost);
        });
        joi_material.value.forEach(function (val, index, theArray) {
            totalm += parseFloat(val.total_cost);
        });
        if(vendor.value.vat==1){
            less_labor.value = (parseFloat(total)/1.12)*percent
            less_material.value = (parseFloat(totalm)/1.12)*percent_material
        }else{
            less_labor.value = parseFloat(total)*percent
            less_material.value = parseFloat(totalm)*percent_material
        }
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
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Request for Disbursement <small>New</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/job_disburse">JO Request for Disbursement</a></li>
                            <li class="breadcrumb-item active" aria-current="page">New</li>
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
								<span class="uppercase">REQUEST FOR DISBURSEMENT</span>
								<!-- <span class="uppercase" v-if="po_head.method == 'DPO'">Direct</span>
								<span class="uppercase" v-if="po_head.method == 'RPO'">Repeat Order</span> -->
							</div>
							<hr class="print:block border-dashed mt-2">
						</div>						
                        <div class="" >
                            <table class="w-full text-sm table-borsdered">
                                <tr>
                                    <td class="px-1 font-bold" width="10%">Company:</td>
                                    <td class="p-0" width="56%"><input type="text" class="w-full px-1 border-b bg-white" v-model="rfd_head.company" disabled></td>
                                    <td class="px-1 font-bold pl-4" width="12%">APV/RFD No.:</td>
                                    <td class="p-0" width="25%"><input type="text" class="w-full px-1 border-b bg-white" v-model="rfd_head.rfd_no" disabled></td>
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
                                        <table class="table-bordered w-full !text-xs">
                                            <tr class="bg-gray-100">
                                                <td class="uppercase p-1 text-center" colspan="6">Explanation</td>
                                                <td class="uppercase p-1 text-center" width="12%">Remarks</td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-y-none p-1 text-left" colspan="6">
                                                    <span>Payment for:</span>
                                                </td>
                                                <td class="border-y-none p-1 text-right"></td>
                                            </tr>
                                            <tr class="">
                                                <td class="!border-none p-1" colspan="3">
                                                    <span class="font-bold">{{jor_head.general_description  }}</span>
                                                </td>
                                                <td class="!border-none p-1 text-center"></td>
                                                <td class="!border-none p-1 text-center"></td>
                                                <td class="!border-none p-1 text-right">Total Amount of JO</td>
                                                <td class="border-y-none p-1 text-center">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>{{formatter.format(joi_head.grand_total)}}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="" v-if="vendor.ewt!=0 && joi_head.grand_total==0"> 
                                                <td clas s="!border-none p-1" colspan="4"></td>
                                                <td class="!border-none p-1 text-center"></td>
                                                <td class="!border-none p-1 text-center"></td>
                                                <td class="!border-none p-1 text-right">{{vendor.ewt}}% Labor EWT</td>
                                                <td class="border-y-none p-1 text-center">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>{{formatter.format(less_labor)}}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="" v-if="joi_head.grand_total==0">
                                                <td class="!border-none p-1" colspan="4"></td>
                                                <td class="!border-none p-1 text-center"></td>
                                                <td class="!border-none p-1 text-center"></td>
                                                <td class="!border-none p-1 text-right">1% Materials EWT</td>
                                                <td class="border-y-none p-1 text-center">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>{{formatter.format(less_material)}}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-y-none p-1 text-left" colspan="6">
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <br>
                                                </td>
                                                <td class="border-y-none p-1 text-center">
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="" colspan="6"></td>
                                                <td class=""></td>
                                            </tr>
                                            <tr class="">
                                                
                                                <span hidden v-if="rfd_payments.length==1">{{ rowspan=6 }}</span>
                                                <span hidden v-else>{{ rowspan=6+rfd_payments.length+1 }}</span>
                                                <td class="border-r-none align-top p-2" colspan="4" width="65%" :rowspan="rowspan"> 
                                                    <p class="m-0 mb-1 leading-none !text-xs"><span class="mr-2 uppercase">JOI Number:</span>{{jor_head.site_jor}}  / {{ joi_head.joi_no }}</p>
                                                </td>
                                            </tr>
                                            <span hidden>{{total_due=0}}</span>
                                            <template v-for="(p, indexes) in rfd_payments">
                                                <span hidden> 
                                                    {{ total_due += p.payment_amount - p.ewt_amount - p.retention_amount}} 
                                                </span>
                                                <tr class="">
                                                    <td class="border-l-none border-y-none p-1 text-right" colspan="2">
                                                        {{p.payment_description}}
                                                    </td>
                                                    <td class="p-1 border-y-none">
                                                        <div class="flex justify-between w-full">
                                                            <span>₱</span>
                                                            <span>{{formatter.format(p.payment_amount)}}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="">
                                                    <td class="border-l-none border-y-none p-1 text-right" colspan="2">
                                                        <div class="flex justify-end space-x-1">
                                                            <span>EWT</span>
                                                            <span>{{ (p.ewt_vat==1) ? 'VAT' : 'NON-VAT' }}</span>
                                                            <span v-if="p.ewt_vat==1">{{p.ewt_percent}}%</span>
                                                        </div>
                                                    </td>
                                                    <td class="p-1 border-y-none">
                                                        <div class="flex justify-between w-full">
                                                            <span>₱</span>
                                                            <span>{{formatter.format(p.ewt_amount)}}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="">
                                                    <td class="border-l-none border-y-none p-1 text-right" colspan="2">
                                                        <div class="flex justify-end space-x-1">
                                                            <span>Retention</span>
                                                        </div>
                                                    </td>
                                                    <td class="p-1 border-y-none">
                                                        <div class="flex justify-between w-full">
                                                            <span>₱</span>
                                                            <span>{{formatter.format(p.retention_amount)}}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                            <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right font-bold " colspan="2">Total Amount Due</td>
                                                <td class="p-1 text-right font-bold !text-sm">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>{{formatter.format(total_due)}}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right" colspan="2">
                                                    <div class="flex justify-end space-x-1">
                                                        <span>Remaining Balance</span>
                                                    </div>
                                                </td>
                                                <td class="p-1 border-y-none">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>{{formatter.format(rfd_head.balance)}}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="7">
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
                                    <td class="text-center p-1">{{prepared_by}}"></td>
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
                                            <a href="#" type="submit" class="btn btn-primary w-36" @click="printDiv()">Print</a>
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