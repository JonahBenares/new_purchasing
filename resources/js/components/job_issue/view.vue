<script setup>
	import navigation from '@/layouts/navigation.vue';
	import{Bars3Icon, PlusIcon, XMarkIcon, Bars4Icon} from '@heroicons/vue/24/solid'
    import { reactive, ref } from "vue"
    import { useRouter } from "vue-router"
	const vendor =  ref();
	const preview =  ref();

	const drawer_dr = ref(false)
	const drawer_rfd = ref(false)
	const drawer_revise = ref(false)
	const hideModal = ref(true)
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
                        <hr class="border-dashed mt-0">
                        <div class="pt-1" id="printable">
                            <div>
                                <div class="row">
									<div class="col-lg-1 col-sm-1 col-md-1">
										<span class="text-sm">TO:</span>
									</div>
									<div class="col-lg-11 col-sm-11 col-md-11">
										<p class="m-0 font-bold capitalize">MF Computer Solutions, Inc.</p>
										<p class="m-0">Beverly Marie Dy</p>
										<p class="m-0">Taculing Road, Bacolod City 6100</p>
										<p class="m-0">(034) 434 9823 / 704-2063</p>
									</div>
								</div>
								<hr class="border-dashed">
								<div class="row">
									<div class="col-lg-6 col-sm-6 col-md-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-40">Date Needed: </span>
											<input type="text" class="border-b bg-white w-full" disabled>
										</div>
									</div>
									<div class="col-lg-6 col-sm-6 col-md-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-52">Completion of Work: </span>
											<input type="text" class="border-b bg-white w-full" disabled>
										</div>	
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-sm-6 col-md-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-40">Date Prepared: </span>
											<input type="text" class="border-b bg-white w-full" disabled>
										</div>
									</div>
									<div class="col-lg-6 col-sm-6 col-md-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-52">CENPRI JOR No: </span>
											<input type="text" class="border-b bg-white w-full" disabled>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6 col-sm-6 col-md-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-40">Start of Work: </span>
											<input type="text" class="border-b bg-white w-full" disabled>
										</div>
									</div>
									<div class="col-lg-6 col-sm-6 col-md-6">
										<div class="flex">
											<span class="text-sm text-gray-700 font-bold pr-1 !w-52">JO No: </span>
											<input type="text" class="border-b bg-white w-full" disabled>
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
															<p class="text-sm font-bold text-gray-600 text-center m-0">Calibration and Servicing of UG 40 Mechanical Hydraulic Governor</p>
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
													<tr class="">
														<td class="border-y-none p-1" colspan="3">
															<span class="font-bold">Supply of manpower/labor, laboratory tools/equipment, and
															technical expertise for the following:</span>
															<br>1. 1. Standard governor overhauling/dismantling, cleaning and replacement of parts as seen necessary (i.e. gaskets, bearings, o-rings, etc.)
															<br>2. Inspection and checking of all parts for wear, cracks, corrosion and other damages.
															<br>3. Repair and replacement of parts as seen upon inspection.
															<br>4. Setting of internal parts and mounting of the governor.
															<br>5. Calibration and bench testing for:
															<br>5.1. Speed Setting and Indicator
															<br>5.2. Speed Droop Setting and Indicator
															<br>5.3. Load Limit Setting and Indicator
															<br>6. Functional test of shut-down solenoid valve
															<br>7. Testing and Commissioning
															<br>8. Submission of inspection, service, commissioning and bench testing reports.
															<br>9. Other works necessary for job completion.
														</td>
														<td class="border-y-none p-1 text-center">
                                                            <input type="text" class="w-full text-center" value="5" placeholder="00">
                                                        </td>
														<td class="border-y-none p-1 text-center">pc</td>
														<td class="border-y-none p-1 text-right">100.00</td>
														<td class="border-y-none p-1 text-right">500.00</td>
													</tr>
													<tr class="bg-gray-100">
														<td class="p-1 text-center" width="3%">#</td>
														<td class="p-1" colspan="2">Materials:</td>
                                                        <td class="uppercase p-1 text-center" width="7%">Qty</td>
														<td class="uppercase p-1 text-center" width="7%">Unit</td>
														<td class="uppercase p-1 text-center" width="10%">Unit Price</td>
														<td class="uppercase p-1 text-center" width="10%">Total</td>
													</tr>
													<tr class="">
														<td class="border-y-none p-1 text-center">1</td>
														<td class="border-y-none p-1" colspan="2">Monitor</td>
														<td class="border-y-none p-1 text-center">
                                                            <input type="text" class="w-full text-center" value="5" placeholder="00">
                                                        </td>
														<td class="border-y-none p-1 text-center">lot</td>
														<td class="border-y-none p-1 text-right">100.00</td>
														<td class="border-y-none p-1 text-right">500.00</td>
													</tr>
													<tr class="">
														<td class="border-y-none p-1 text-center">2</td>
														<td class="border-y-none p-1" colspan="2">Mouse</td>
														<td class="border-y-none p-1 text-center">
                                                            <input type="text" class="w-full text-center" value="5" placeholder="00">
                                                        </td>
														<td class="border-y-none p-1 text-center">pc</td>
														<td class="border-y-none p-1 text-right">100.00</td>
														<td class="border-y-none p-1 text-right">500.00</td>
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
															<p class="m-0 !text-xs leading-none"><span class="mr-2 uppercase">JOR Number:</span>PR-19772-8727</p>
															<p class="m-0 !text-xs leading-none"><span class="mr-2 uppercase">Requestor:</span>Henne Tanan</p>
															<p class="m-0 !text-xs leading-none"><span class="mr-2 uppercase">End-use:</span>IT Department</p>
															<p class="m-0 !text-xs leading-none"><span class="mr-2 uppercase">Purpose:</span>Replace damage monitor, mouse and keyboard</p>
														</td>
														<td class="border-l-none border-y-none p-0 text-right p-0.5 pr-1" colspan="2" >Total Labor</td>
														<td class="p-0"><input disabled type="text" class="w-full bg-white p-0.5 text-right pr-1" value="200.00"></td>
													</tr>
													
													<tr class="">
														<td class="border-l-none border-y-none p-1 text-right" colspan="2">VAT %</td>
														<td class="p-0">
															<div class="flex">
																<input disabled type="text" class="w-10 bg-white border-r text-center" placeholder="%" value="">
																<input disabled type="text" class="w-full bg-white p-1 text-right" value="">
															</div>
														</td>
													</tr>
													<tr class="">
														<td class="border-l-none border-y-none p-1 text-right" colspan="2">Discount Labor</td>
														<td class="p-0"><input disabled type="text" class="w-full bg-white p-1 text-right" value="200.00"></td>
													</tr>
													<tr class="">
														<td class="border-l-none border-y-none p-1 text-right" colspan="2">Discount Material</td>
														<td class="p-0"><input disabled type="text" class="w-full bg-white p-1 text-right" value="100.00"></td>
													</tr>
													<tr class="">
														<td class="border-l-none border-y-none p-1 text-right font-bold" colspan="2">GRAND TOTAL</td>
														<td class="p-1 text-right font-bold !text-sm">1000.00</td>
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
                                                            <span>Exclusive of VAT</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top text-center" width="4%">4.</td>
                                                    <td class="align-top  pl-1" colspan="2">
                                                        <div class="flex justify-start space-x-1">
                                                            <span>Payment </span>
                                                            <span> COD</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top text-center" width="4%">5.</td>
                                                    <td class="align-top  pl-1" colspan="2">
                                                        <div class="flex justify-start space-x-1">
                                                            <span>Delivery Term </span>
                                                            <span> Sample</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top text-center" width="4%">6.</td>
                                                    <td class="align-top  pl-1" colspan="2">sample term</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-lg-6  col-sm-6 col-md-6">
                                            <table class="table-bordsered !text-xs w-full">
                                                <tr>
                                                    <td class="p-1 uppercase" colspan="3">Other Instructions</td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top text-center" width="4%">1.</td>
                                                    <td class="px-1" colspan="2">sample instructions</td>
                                                </tr>
                                                <tr>
                                                    <td class="align-top text-center" width="4%">2.</td>
                                                    <td class="px-1" colspan="2">sample instructions -Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</td>
                                                </tr>
                                                <tr>
													<td colspan="2" class="p-1">Sample Notes</td>
													<td class="p-0 align-top po_buttons" width="1">
														<button type="button" @click="removeOthers(indexes)" class="btn btn-danger p-1">
															<XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="menu-icon w-3 h-3 "></XMarkIcon>
														</button>
													</td>
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
                                                            <span>PHP</span>
                                                            <span>18,999.99</span>
                                                        </div>
                                                    </td>
                                                    <td width="14%"></td>
                                                    <td width="8%" class="font-bold text-sm text-gray-500">Conforme:</td>
                                                    <td  width="30%" class="border-b border-gray-400 px-4"> </td>
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
                                                    <td class="text-center p-1">Henne Tanant</td>
                                                    <td></td>
                                                    <td class="text-center p-1">Beverly Sy</td>
                                                    <td></td>
                                                    <td class="text-center p-1">Jonah Marie Dy</td>
                                                    <td></td>
                                                    <td class="text-center p-1">Glenn Paulate</td>
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
                                    <div class="po_buttons text-xs">
                                        <span class="w-full block">Internal Comment:</span>
                                        <span class="w-full block">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. </span>
                                        <hr	class="border-dashed">
                                    </div>
                                    
                                    <div class="row my-2 po_buttons" > 
                                        <div class="col-lg-12 col-md-12">
                                            <div class="flex justify-between space-x-2">
                                                <div class="flex justify-between">
                                                    <a href="/job_issue/edit" type="submit" class="btn btn-info w-26 !rounded-r-none">Revise JOI</a>
                                                    <button class="btn btn-info !text-white px-2 !pt-[0px] pb-0 !rounded-l-none" @click="openDrawerRevise()">
                                                        <Bars4Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></Bars4Icon >
                                                    </button>
                                                </div>
                                                <div class="flex justify-between space-x-1">
                                                    <div class="flex justify-between">
                                                        <a href="/job_disburse/new" class="btn btn-warning !text-white w-26 !rounded-r-none">Print RFD</a>
                                                        <a href="/job_disburse/new2" class="btn btn-warning !text-white w-26 !rounded-r-none">Print RFD 2</a>
                                                        <button class="btn btn-warning !text-white px-2 !pt-[0px] pb-0 !rounded-l-none" @click="openDrawerRFD()">
                                                            <Bars4Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></Bars4Icon >
                                                        </button>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <a href="/job_dr/new" class="btn btn-warning !text-white w-26 !rounded-r-none">Print DR</a>
                                                        <button class="btn btn-warning !text-white px-2 !pt-[0px] pb-0 !rounded-l-none" @click="openDrawerDR()">
                                                            <Bars4Icon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></Bars4Icon >
                                                        </button>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary w-36" @click="printDiv()">Print JOI</button>
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
                            <span class="font-bold ">DR List</span>
                            <a href="#" class="text-gray-600" @click="closeModal">
                                <XMarkIcon fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"></XMarkIcon>
                            </a>
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="modal_s_items ">
                        <div class="">
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">JOI-88270-7662 (Main)</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">JOI-88270-7662.r1</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">JOI-88270-7662.r2</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">JOI-88270-7662.r3</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">JOI-88270-7662.r4</a>
                            <a href="" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">JOI-88270-7662.r5</a>
                            <a href="#"  @click="closeModal" class="text-gray-500 block hover:!no-underline hover:bg-gray-100 px-3 py-2 border-b text-sm">JOI-88270-7662.r6 (Current)</a>
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
                    </div> s
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
	</navigation>
</template>