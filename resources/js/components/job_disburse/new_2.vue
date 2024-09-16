<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, CheckIcon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
	const vendor =  ref();
	const preview =  ref();
    const joi_det = ref(false)
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
                        <h3 class="card-title !text-lg m-0 uppercase font-bold text-gray-600">JO Request for Disbursement <small>New</small></h3>
                    </span>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb !mb-0 !text-xs px-2 py-1 !bg-transparent">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                            <li class="breadcrumb-item"><a href="/job_disburse">JO  Request for Disbursement</a></li>
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
                                    <label class="text-gray-500 m-0" for="">Choose JOI Number</label>
                                    <div class="input-group col-xs-12">
                                        <select class="form-control file-upload-info" >
                                            <option value="">JOI-1992-991</option>
                                        </select>
                                        <span class="input-group-append">
                                            <button class="btn btn-primary" type="button" @click="joi_det = !joi_det">Select</button>
                                        </span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="border-dashed">		
                            <div class="" v-show="joi_det">
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
                                                    <td class="uppercase p-1 text-center" width="12%">Remarks</td>
                                                </tr>
                                                <tr class="">
                                                    <td class="border-y-none p-1 text-left" colspan="6">
                                                        <span>Payment for:</span>
                                                    </td>
                                                    <td class="border-y-none p-1 text-right"></td>
                                                </tr>
                                                <!-- <tr>
                                                    <td  class="!border-none p-1" colspan="6">
                                                        <span class="font-bold">Supply of manpower/labor, laboratory tools/equipment, and
															technical expertise for the following:</span>
                                                    </td>
                                                    <td class="border-y-none p-1 text-center"></td>
                                                </tr> -->
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
                                                    <td class="border-y-none" colspan="6"><br></td>
                                                    <td class="border-y-none"></td>
                                                </tr>
                                                <tr class="">
                                                    <td class="" colspan="6"></td>
                                                    <td class=""></td>
                                                </tr>
                                                <tr class="">
                                                    <!--plus one sa rowspan if may additional nga description and amount-->
                                                    <td class="border-r-none align-top p-2" colspan="4" width="65%" rowspan="8"> 
                                                        <p class="m-0 mb-1 leading-none !text-xs"><span class="mr-2 uppercase">JOI Number:</span>JOI-CENPRI-1001  / JOI-8277207273</p>
                                                        <p class="m-0 mb-1 leading-none !text-xs"><span class="mr-2 uppercase">DR Number:</span>DR-CENPRI-1001</p>
                                                    </td>
                                                    <td class="border-l-none border-y-none text-right" colspan="2">
                                                        <div class="flex justify-end space-x-3">
                                                            <span class="p-1">First Payment</span>
                                                            <div class="flex ">
                                                                <input type="text" class="w-32 text-right border p-1" placeholder="Description">
                                                            </div>
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
                                                    <td class="border-l-none border-y-none text-right p-1" colspan="2">EWT Vat 3%</td>
                                                    <td class="p-1 text-right font-bold !text-xs">1000.00</td>
                                                </tr>
                                                <tr class="">
                                                    <td class="border-l-none border-y-none text-right p-1" colspan="2">Retention 5%</td>
                                                    <td class="p-1 text-right font-bold !text-xs">1000.00</td>
                                                </tr>
                                                <tr class="">
                                                    <td class="border-l-none border-y-none p-0 text-right" colspan="2">
                                                        <div class="flex justify-between space-x-1">
                                                            <button class="btn btn-xs btn-primary p-0 px-1">
                                                                <PlusIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></PlusIcon>
                                                            </button>
                                                            <button class="btn btn-xs btn-danger  p-0 px-1">
                                                                <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
                                                            </button>
                                                            <input type="text" class="w-full text-left bg-yellow-100 p-1" placeholder="Description">
                                                        </div>
                                                    </td>
                                                    <td class="p-0 border-y-none">
                                                        <div class="flex justify-between w-full">
                                                            <button class="btn btn-xs p-0 !bg-yellow-100 ">
                                                            </button>
                                                            <input type="text" class="w-full text-right bg-yellow-100 p-1" placeholder="Amount">
                                                        </div>
                                                    </td>                                                    
                                                </tr>
                                                <tr class="">
                                                    <td class="border-l-none border-y-none text-right p-0" colspan="2">
                                                        <div class="flex justify-end space-x-1">
                                                            <!-- <span class="flex space-x-1">
                                                                <span class="pb-.5">Show</span>
                                                                <input type="checkbox" class="" alt="show">
                                                            </span> -->
                                                            <span class="p-1">EWT</span>
                                                            <div class="flex ">
                                                                <select name="" id="" class="w-14 border p-1">
                                                                    <option value="">VAT</option>
                                                                    <option value="">NON-VAT</option>
                                                                </select>
                                                                <input type="text" class="w-10 text-center border p-1" placeholder="%">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="p-0 pr-0 border-y-none">
                                                        <div class="flex justify-between w-full">
                                                            <span class="p-1">₱</span>
                                                            <input type="text" class="w-20 text-right border p-1" placeholder="00">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="">
                                                    <td class="border-l-none border-y-none text-right p-0" colspan="2">
                                                        <div class="flex justify-end space-x-1">
                                                            <span class="p-1">Retention</span>
                                                            <div class="flex ">
                                                                <input type="text" class="w-10 text-center border p-1" placeholder="%">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="p-0 border-y-none">
                                                        <div class="flex justify-between w-full">
                                                            <span class="p-1">₱</span>
                                                            <input type="text" class="w-20 text-right border p-1" placeholder="00">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="">
                                                    <td class="border-l-none border-y-none p-1 text-right font-bold " colspan="2">Total Amount Due</td>
                                                    <td class="p-1 text-right font-bold !text-sm">1000.00</td>
                                                </tr>
                                                <tr class="">
                                                    <td class="border-l-none border-y-none p-0 text-right  " colspan="2">Remaining Balance</td>
                                                    <td class="px-1 text-right font-bold !text-xs">1000.00</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7">
                                                        <div class="flex justify-start space-x-1">
                                                            <span class="p-1">Notes:</span>
                                                            <textarea class="w-full bg-yellow-100 p-1" placeholder="Add notes"></textarea>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <br>
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
                                        <td class="text-center p-1"><input class="w-full text-center" placeholder="Employee Name"></td>
                                        <td></td>
                                        <td class="text-center p-1"><input class="w-full text-center" placeholder="Employee Name"></td>
                                        <td></td>
                                        <td class="text-center p-1"><input class="w-full text-center" placeholder="Employee Name"></td>
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
                                        <td class="text-center p-1"><input class="w-full text-center" placeholder="Employee Name"></td>
                                        <td></td>
                                        <td class="text-center p-1"><input class="w-full text-center" placeholder="Employee Name"></td>
                                        <td></td>
                                        <td class="text-center p-1"><input class="w-full text-center" placeholder="Employee Name"></td>
                                    </tr>
                                </table>
                                <hr	class="border-dashed">
                                <div>
                                    <div class="row my-2"> 
                                        <div class="col-lg-12 col-md-12">
                                            <div class="flex justify-center space-x-2">
                                                <button type="submit" class="btn btn-primary mr-2 w-36" @click="openSuccessAlert()">Save</button>
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
									<a href="/job_issue/view" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Back to JOI</a>
									<a href="/job_disburse/view2" class="btn !bg-gray-100 btn-sm !rounded-full w-full">Close</a>
								</div>
							</div>
						</div>
					</div> 
				</div>
			</div>
		</Transition>
	</navigation>
	
</template>