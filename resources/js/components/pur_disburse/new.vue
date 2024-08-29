<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
	const vendor =  ref();
	const preview =  ref();
    const po_det = ref(false)
	const save_button =  ref()
	const print_button =  ref(false)
	const hide_printButton = ref()
	const successAlert = ref(false)
	const hideAlert = ref(true)
	const openSuccessAlert = () => {
		successAlert.value = !successAlert.value
	}
	const closeSuccessAlert = () => {
		successAlert.value = !hideAlert.value
		print_button.value = !print_button.value
		save_button.value = hideAlert.value
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
                            <div class="row">							
                                <div class="col-lg-6 offset-lg-3 col-md-3">
                                    <div class="form-group">
                                    <label class="text-gray-500 m-0" for="">Choose PO Number</label>
                                    <div class="input-group col-xs-12">
                                        <select class="form-control file-upload-info" >
                                            <option value="">PO-1992-991</option>
                                        </select>
                                        <span class="input-group-append">
                                            <button class="btn btn-primary" type="button" @click="po_det = !po_det">Select</button>
                                        </span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="border-dashed">		
                            <div class="" v-show="po_det">
                                <table class="w-full text-sm table-borsdered">
                                    <tr>
                                        <td class="px-1 font-bold" width="10%">Company:</td>
                                        <td class="p-0" width="56%"><input type="text" class="w-full px-1 border-b"></td>
                                        <td class="px-1 font-bold pl-4" width="12%">APV/RFD No.:</td>
                                        <td class="p-0" width="25%"><input type="text" class="w-full px-1 border-b"></td>
                                    </tr>
                                    <tr>
                                        <td class="px-1 font-bold">Pay To:</td>
                                        <td class="p-0"><input type="text" class="w-full px-1 border-b"></td>
                                        <td class="px-1 font-bold pl-4">Date:</td>
                                        <td class="p-0"><input type="date" class="w-full px-1 border-b"></td>
                                    </tr>
                                    <tr>
                                        <td class="px-1 font-bold">Check Name:</td>
                                        <td class="p-0"><input type="text" class="w-full px-1 border-b"></td>
                                        <td class="px-1 font-bold pl-4">Due Date:</td>
                                        <td class="p-0"><input type="date" class="w-full px-1 border-b"></td>
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
                                                    <input type="text" class="w-full px-1 border-b">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-1 font-bold pl-4">Check Due:</td>
                                        <td class="p-0"><input type="date" class="w-full px-1 border-b"></td>
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
                                                            <textarea class="w-full bg-yellow-50 p-1" placeholder="Add notes"></textarea>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr	class="border-dashed">
                                <div class="block" :class="{ hidden:save_button }">
                                    <div class="row my-2"> 
                                        <div class="col-lg-12 col-md-12">
                                            <div class="flex justify-center space-x-2">
                                                <button type="submit" class="btn btn-primary mr-2 w-36" @click="openSuccessAlert()">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class=" !hidden" :class="{ show:print_button }">
                                    <div class="row my-2"> 
                                        <div class="col-lg-12 col-md-12">
                                            <div class="flex justify-center space-x-2">
                                                <button type="submit" class="btn btn-primary mr-2 w-36">Print</button>
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
				<div @click="closeAlert" class="w-full h-full fixed backdrop-blur-sm bg-white/30"></div>
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
									<h5 class="leading-tight">You have successfully created a RFD.</h5>
								</div>
							</div>
						</div>
						<br>
						<div class="row mt-4"> 
							<div class="col-lg-12 col-md-12">
								<div class="flex justify-center space-x-2">
									<a href="/pur_po/view" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Back to PO</a>
									<button class="btn !bg-gray-100 btn-sm !rounded-full w-full" @click="closeSuccessAlert()">Close</button>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
	
</template>