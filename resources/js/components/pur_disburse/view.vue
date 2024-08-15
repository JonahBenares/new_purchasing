<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon} from '@heroicons/vue/24/solid'
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

</script>
<template>
	<navigation>
		<div class="row">
            <div class="col-lg-12">
                <div class="flex justify-between mb-3 px-2">
                    <span class="">
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">Request for Disbursement <small>New</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/pur_disburse">Request for Disbursement</a></li>
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
					<div class="pt-1">					
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
                                                <input type="radio" name="cc">
                                                <span class="font-bold">Check</span>
                                                <input type="radio" name="cc">
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
                                                <td class="uppercase p-1 text-center" width="12%">Total</td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-y-none p-1 text-left" colspan="6">
                                                    <span>Payment for:</span>
                                                </td>
                                                <td class="border-y-none p-1 text-right"></td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-y-none p-1 text-left" colspan="6">
                                                    5 pc/s Monitor Brand:Asus @ 100.00 per pc/s
                                                </td>
                                                <td class="border-y-none p-1 text-center">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>500.00</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-y-none p-1 text-left" colspan="6">
                                                    5 pc/s Mouse Brand:A4tech @ 100.00 per pc/s
                                                </td>
                                                <td class="border-y-none p-1 text-center">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>500.00</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-y-none p-1 text-left" colspan="6">
                                                    5 pc/s Keyboard Brand:A4tech @ 100.00 per pc/s
                                                </td>
                                                <td class="border-y-none p-1 text-center">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>500.00</span>
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
                                                <td class="border-r-none align-top p-2" colspan="4" width="65%" rowspan="7">
                                                    <p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Requestor:</span>Henne Tanan</p>
                                                    <p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">End-use:</span>IT Department</p>
                                                    <p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">Purpose:</span>Replace damage monitor, mouse and keyboard</p>
                                                    <br>
                                                    <p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">PO Number:</span>PO-CENPRI-1001</p>
                                                    <p class="m-0 mb-1 !text-xs"><span class="mr-2 uppercase">DR Number:</span>DR-CENPRI-1001</p>
                                                </td>
                                                <td class="border-l-none border-y-none p-0 text-right p-0.5 pr-1" colspan="2" >Shipping Cost</td>
                                                <td class="p-1 border-y-none">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>500.00</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right" colspan="2">Packing and Handling Fee</td>
                                                <td class="p-1 border-y-none">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>500.00</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right" colspan="2">Less: Discount</td>
                                                <td class="p-1 border-y-none">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>500.00</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right font-bold" colspan="2">Sub Total</td>
                                                <td class="p-1 border-t">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>500.00</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right" colspan="2">Less: 0% EWT</td>
                                                <td class="p-1 border-y-none">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>500.00</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right" colspan="2">Vatable</td>
                                                <td class="p-1 border-y-none">
                                                    <div class="flex justify-between w-full">
                                                        <span>₱</span>
                                                        <span>500.00</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="">
                                                <td class="border-l-none border-y-none p-1 text-right font-bold " colspan="2">Total Amount Due</td>
                                                <td class="p-1 text-right font-bold !text-sm">1000.00</td>
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
                            
                            <hr	class="border-dashed">
                            <div class="row my-2"> 
                                <div class="col-lg-12 col-md-12">
                                    <div class="flex justify-center space-x-2">
                                        <!-- <div class="flex justify-between space-x-1">
                                            <button type="submit" @click="openPreview()" class="btn btn-info w-26">Preview</button>
                                            <button type="submit" @click="openAddVendor()" class="btn btn-info w-26">Add Vendor</button>
                                        </div> -->
                                        <div class="flex justify-between space-x-1">
                                            <!-- kung wala pa na save -->
                                            <!-- <button type="submit" class="btn btn-primary w-26">Back</button> -->
                                            <!-- <button type="submit" class="btn btn-warning w-26 !text-white" >Save as Draft</button> -->
                                            <a href="#" type="submit" class="btn btn-primary w-36">Print</a>
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