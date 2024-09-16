<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, CheckIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
	const vendor =  ref();
	const preview =  ref();

	const showAddVendor = ref(false)
	const showPreview = ref(false)
	const hideModal = ref(true)
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
                        <div class="" >
                            <table class="w-full text-sm table-borsdered">
                                <tr>
                                    <td class="px-1 font-bold" width="10%">Company:</td>
                                    <td class="p-0" width="56%"><input type="text" class="w-full px-1 border-b bg-white" disabled></td>
                                    <td class="px-1 font-bold pl-4" width="12%">APV/RFD No.:</td>
                                    <td class="p-0" width="25%"><input type="text" class="w-full px-1 border-b bg-white" disabled></td>
                                </tr>
                                <tr>
                                    <td class="px-1 font-bold">Pay To:</td>
                                    <td class="p-0"><input type="text" class="w-full px-1 border-b bg-white" disabled></td>
                                    <td class="px-1 font-bold pl-4">Date:</td>
                                    <td class="p-0"><input type="text" class="w-full px-1 border-b"></td>
                                </tr>
                                <tr>
                                    <td class="px-1 font-bold">Check Name:</td>
                                    <td class="p-0"><input type="text" class="w-full px-1 border-b bg-white" disabled></td>
                                    <td class="px-1 font-bold pl-4">Due Date:</td>
                                    <td class="p-0"><input type="text" class="w-full px-1 border-b"></td>
                                </tr>
                                <tr>
                                    <td class="p-1" colspan="2">
                                        <div class="flex justify-start space-x-10">
                                            <div class="flex justify-between space-x-2 ml-5">
                                                <span class="border-b w-5"></span>
                                                <span class="font-bold">Check</span>
                                                <span class="border-b w-5 text-green-600">
                                                    <CheckIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-4 h-4 "></CheckIcon>
                                                </span>
                                                <span class="font-bold">Cash</span>
                                            </div>
                                            <div class="flex justify-between space-x-1 w-full">
                                                <span class="font-bold w-20">Bank No.:</span>
                                                <input type="text" class="w-full px-1 border-b bg-white" disabled>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-1 font-bold pl-4">Check Due:</td>
                                    <td class="p-0"><input type="text"  class="w-full px-1 border-b bg-white" disabled></td>
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
                                                    <span class="font-bold">Supply of manpower/labor, laboratory tools/equipment, and
                                                        technical expertise for the following:</span>
                                                </td>
                                                <td class="!border-none p-1 text-center"></td>
                                                <td class="!border-none p-1 text-center"></td>
                                                <td class="!border-none p-1 text-right">Total Amount of JO</td>
                                                <td class="border-y-none p-1 text-center">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>500.00</span>
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
                                                <td class="border-r-none align-top p-2" colspan="4" width="65%" rowspan="9"> 
                                                    <p class="m-0 mb-1 leading-none !text-xs"><span class="mr-2 uppercase">JOI Number:</span>JOI-CENPRI-1001 / JOI-8277207273</p>
                                                    <p class="m-0 mb-1 leading-none !text-xs"><span class="mr-2 uppercase">DR Number:</span>DR-CENPRI-1001</p>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right" colspan="2">
                                                    First Payment (Details)
                                                </td>
                                                <td class="p-1 border-y-none">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>500.00</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right" colspan="2">
                                                    <div class="flex justify-end space-x-1">
                                                        <span>EWT</span>
                                                        <span>VAT</span>
                                                        <span>3%</span>
                                                    </div>
                                                </td>
                                                <td class="p-1 border-y-none">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>500.00</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right" colspan="2">
                                                    <div class="flex justify-end space-x-1">
                                                        <span>Retention</span>
                                                        <span>5%</span>
                                                    </div>
                                                </td>
                                                <td class="p-1 border-y-none">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>500.00</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right" colspan="2">
                                                    Second Payment 
                                                </td>
                                                <td class="p-1 border-y-none">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>500.00</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right" colspan="2">
                                                    <div class="flex justify-end space-x-1">
                                                        <span>EWT</span>
                                                        <span>VAT</span>
                                                        <span>3%</span>
                                                    </div>
                                                </td>
                                                <td class="p-1 border-y-none">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>500.00</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right" colspan="2">
                                                    <div class="flex justify-end space-x-1">
                                                        <span>Retention</span>
                                                        <span>2%</span>
                                                    </div>
                                                </td>
                                                <td class="p-1 border-y-none">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>500.00</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right font-bold " colspan="2">Total Amount Due</td>
                                                <td class="p-1 text-right font-bold !text-sm">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>1000.00</span>
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
                                                        <span>500.00</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="7">
                                                    <div class="flex justify-start space-x-1">
                                                        <span class="p-1">Notes:</span>
                                                        <span class="p-1">sample note</span>
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
                                    <td class="text-center p-1"><input class="w-full"></td>
                                    <td></td>
                                    <td class="text-center p-1"><input class="w-full"></td>
                                    <td></td>
                                    <td class="text-center p-1">Jonah Marie Dy</td>
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
                                    <td class="text-center p-1">Henne Tanant</td>
                                    <td></td>
                                    <td class="text-center p-1">Beverly Sy</td>
                                    <td></td>
                                    <td class="text-center p-1">Jonah Marie Dy</td>
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